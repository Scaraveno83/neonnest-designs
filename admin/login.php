<?php
require __DIR__ . '/../includes/admin-auth.php';

if (admin_is_logged_in()) {
    header('Location: /admin/index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (admin_login($username, $password)) {
        header('Location: /admin/index.php');
        exit;
    }

    $error = 'Login fehlgeschlagen. Bitte Zugangsdaten prüfen.';
}

$pageTitle = 'Admin Login';
include __DIR__ . '/../includes/header.php';
?>
<section class="section">
    <div class="container" style="max-width:640px;">
        <h1 class="section-title">Admin Login</h1>
        <p class="section-subtitle">Melde dich an, um den Admin-Bereich zu nutzen.</p>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form class="form" method="post" action="/admin/login.php" style="margin-top:1.25rem;">
            <div class="form-group">
                <label for="username">Benutzername</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Passwort</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Anmelden</button>
        </form>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>