<?php
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';

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

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($orderId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :id");
    $stmt->execute([':id' => $orderId]);
    $order = $stmt->fetch();

    if (!$order) {
        die('Bestellung nicht gefunden.');
    }

    $itemsStmt = $pdo->prepare("
        SELECT oi.*, t.name, t.download_file
        FROM order_items oi
        JOIN templates t ON t.id = oi.template_id
        WHERE oi.order_id = :oid
    ");
    $itemsStmt->execute([':oid' => $orderId]);
    $items = $itemsStmt->fetchAll();
} else {
    $orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
}

$pageTitle = 'Admin Bestellungen';
include __DIR__ . '/../includes/header.php';
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Bestellungen</h1>
        <p class="section-subtitle">
            Übersicht über alle Bestellungen und dazugehörige Templates.
        </p>

        <?php if ($orderId === 0): ?>
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Bestellnummer</th>
                    <th>Kunde</th>
                    <th>E-Mail</th>
                    <th>Betrag</th>
                    <th>Status</th>
                    <th>Datum</th>
                    <th>Details</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><?php echo (int)$o['id']; ?></td>
                        <td><?php echo htmlspecialchars($o['order_number']); ?></td>
                        <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($o['customer_email']); ?></td>
                        <td><?php echo format_price($o['total_amount']); ?></td>
                        <td><?php echo htmlspecialchars($o['payment_status']); ?></td>
                        <td><?php echo htmlspecialchars($o['created_at']); ?></td>
                        <td><a href="/admin/orders.php?id=<?php echo (int)$o['id']; ?>">Öffnen</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="margin-bottom:1rem;">
                <a href="/admin/orders.php" class="btn btn-secondary">Zurück zur Übersicht</a>
            </div>

            <h2 style="font-size:1.3rem;">Bestellung #<?php echo htmlspecialchars($order['order_number']); ?></h2>
            <p>
                <strong>Kunde:</strong> <?php echo htmlspecialchars($order['customer_name']); ?><br>
                <strong>E-Mail:</strong> <?php echo htmlspecialchars($order['customer_email']); ?><br>
                <strong>Betrag:</strong> <?php echo format_price($order['total_amount']); ?><br>
                <strong>Status:</strong> <?php echo htmlspecialchars($order['payment_status']); ?><br>
                <strong>Datum:</strong> <?php echo htmlspecialchars($order['created_at']); ?>
            </p>

            <h3 style="font-size:1.1rem;margin-top:1.5rem;">Bestellte Templates</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>Template</th>
                    <th>Einzelpreis</th>
                    <th>Download</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo format_price($item['unit_price']); ?></td>
                        <td>
                            <?php if (!empty($item['download_file'])): ?>
                                <a href="<?php echo htmlspecialchars($item['download_file']); ?>">Download-Link</a>
                            <?php else: ?>
                                –
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
