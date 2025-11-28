<?php
// Kein Datenbankzugriff nötig – vermeidet Fehler, falls DB nicht erreichbar

require __DIR__ . '/../includes/admin-auth.php';

// Auth per Session oder Basic Header
enforce_admin_auth();

$pageTitle = 'Admin Dashboard';
include __DIR__ . '/../includes/header.php';
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Admin – NeonNest Designs</h1>
        <p class="section-subtitle">
            Verwalte Templates und Bestellungen im NeonNest Store.
        </p>
        <p class="section-subtitle" style="margin-top:0.5rem;">
            Angemeldet als Admin. <a href="/admin/logout.php">Logout</a>
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
