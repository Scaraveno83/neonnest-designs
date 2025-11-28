<?php
$pageTitle = 'Erfolg';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

$orderNumber = $_GET['order'] ?? '';

if ($orderNumber === '') {
    http_response_code(400);
    die('Bestellnummer fehlt.');
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_number = :onr");
$stmt->execute([':onr' => $orderNumber]);
$order = $stmt->fetch();

if (!$order || $order['payment_status'] !== 'paid') {
    http_response_code(404);
    die('Bestellung nicht gefunden oder nicht bezahlt.');
}

$itemStmt = $pdo->prepare("
    SELECT oi.*, t.name, t.download_file 
    FROM order_items oi
    JOIN templates t ON t.id = oi.template_id
    WHERE oi.order_id = :oid
");
$itemStmt->execute([':oid' => $order['id']]);
$items = $itemStmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Danke für deine Bestellung!</h1>
        <p class="section-subtitle">
            Deine Zahlung wurde registriert. Unten findest du die Download-Links zu deinen Templates.
            Eine Bestellbestätigung kann zusätzlich per E-Mail an <?php echo htmlspecialchars($order['customer_email']); ?> gesendet werden.
        </p>

        <div style="margin-bottom:1.5rem;">
            <strong>Bestellnummer:</strong> <?php echo htmlspecialchars($order['order_number']); ?><br>
            <strong>Gesamtbetrag:</strong> <?php echo format_price($order['total_amount']); ?>
        </div>

        <h2 style="font-size:1.4rem;margin-bottom:1rem;">Deine Downloads</h2>
        <ul style="list-style:none;padding:0;">
            <?php foreach ($items as $item): ?>
                <li style="margin-bottom:0.75rem;">
                    <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                    <?php if (!empty($item['download_file'])): ?>
                        <a href="<?php echo htmlspecialchars($item['download_file']); ?>" class="btn btn-secondary">
                            Template herunterladen
                        </a>
                    <?php else: ?>
                        <span style="font-size:0.85rem;color:#A0A0A0;">Kein Download hinterlegt.</span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <div style="margin-top:1.5rem;">
            <a href="/templates.php" class="btn btn-primary">Weitere Templates ansehen</a>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
