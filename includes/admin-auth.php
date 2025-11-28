<?php
/**
 * Admin authentication helper supporting both Basic headers and form login.
 */

function start_admin_session(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function admin_expected_credentials(): array
{
    $user = getenv('ADMIN_USERNAME') ?: 'admin';
    $pass = getenv('ADMIN_PASSWORD') ?: 'neonnest123';

    return [$user, $pass];
}

function admin_is_logged_in(): bool
{
    start_admin_session();
    return !empty($_SESSION['admin_authenticated']);
}

function mark_admin_logged_in(): void
{
    start_admin_session();
    $_SESSION['admin_authenticated'] = true;
}

function clear_admin_session(): void
{
    start_admin_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function get_basic_auth_credentials(): array
{
    $user = $_SERVER['PHP_AUTH_USER'] ?? null;
    $pass = $_SERVER['PHP_AUTH_PW'] ?? null;

    $header = $_SERVER['HTTP_AUTHORIZATION']
        ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
        ?? $_SERVER['AUTHORIZATION']
        ?? '';

    if ($header !== '') {
        $normalized = ltrim($header);

        if (preg_match('/^basic\s*:?\s+(?<token>.+)$/i', $normalized, $matches)) {
            $decoded = base64_decode($matches['token'], true);
            if ($decoded !== false && strpos($decoded, ':') !== false) {
                [$user, $pass] = explode(':', $decoded, 2);
            }
        }
    }

    return [$user, $pass];
}

function credentials_valid(?string $user, ?string $pass): bool
{
    [$expectedUser, $expectedPass] = admin_expected_credentials();
    if ($user === null || $pass === null) {
        return false;
    }

    return hash_equals($expectedUser, $user) && hash_equals($expectedPass, $pass);
}

function attempt_basic_auth(): bool
{
    [$user, $pass] = get_basic_auth_credentials();
    if (!credentials_valid($user, $pass)) {
        return false;
    }

    mark_admin_logged_in();
    return true;
}

function enforce_admin_auth(): void
{
    if (admin_is_logged_in()) {
        return;
    }

    if (attempt_basic_auth()) {
        return;
    }

    header('Location: /admin/login.php');
    exit;
}

function admin_login(string $user, string $pass): bool
{
    if (!credentials_valid($user, $pass)) {
        return false;
    }

    mark_admin_logged_in();
    return true;
}

function admin_logout(): void
{
    clear_admin_session();
}