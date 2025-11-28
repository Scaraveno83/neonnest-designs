<?php
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';

// Einfacher Basic-Auth-Check (zusätzlich zu .htaccess)
$validUser = 'admin';
$validPass = 'neonnest123';

if (!isset($_SERVER['PHP_AUTH_USER']) ||
    $_SERVER['PHP_AUTH_USER'] !== $validUser ||
    $_SERVER['PHP_AUTH_PW'] !== $validPass) {
    header('WWW-Authenticate: Basic realm="NeonNest Admin"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Unauthorized';
    exit;
}

$pageTitle = 'Admin Dashboard';
include __DIR__ . '/../includes/header.php';
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Admin – NeonNest Designs</h1>
        <p class="section-subtitle">
            Verwalte Templates und Bestellungen im NeonNest Store.
        </p>

        <div class="grid grid-2">
            <div class="why-item">
                <h2 class="why-title">Templates verwalten</h2>
                <p>Neue Templates anlegen, bestehende bearbeiten oder deaktivieren.</p>
                <a href="/admin/templates.php" class="btn btn-primary" style="margin-top:0.75rem;">Zu den Templates</a>
            </div>
            <div class="why-item">
                <h2 class="why-title">Bestellungen</h2>
                <p>Übersicht über alle Bestellungen inklusive Download-Dateien.</p>
                <a href="/admin/orders.php" class="btn btn-secondary" style="margin-top:0.75rem;">Zu den Bestellungen</a>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
