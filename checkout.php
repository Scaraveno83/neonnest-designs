<?php
$pageTitle = 'Checkout';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

$items = get_cart_items($pdo);
$total = get_cart_total($pdo);

if (empty($items)) {
    header('Location: /cart.php');
    exit;
}

$errors = [];
$name = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name === '') {
        $errors['name'] = 'Bitte gib deinen Namen ein.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Bitte gib eine gültige E-Mail-Adresse an.';
    }

    if (empty($errors)) {
        $pdo->beginTransaction();
        try {
            $orderNumber = generate_order_number();

            $stmt = $pdo->prepare("INSERT INTO orders 
                (order_number, customer_email, customer_name, total_amount, payment_status, created_at)
                VALUES (:onr, :email, :name, :total, :status, NOW())");
            $stmt->execute([
                ':onr' => $orderNumber,
                ':email' => $email,
                ':name' => $name,
                ':total' => $total,
                ':status' => 'paid' // In einer echten Integration nach PayPal/Stripe-Callback setzen
            ]);
            $orderId = (int)$pdo->lastInsertId();

            $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, template_id, unit_price)
                                       VALUES (:oid, :tid, :price)");
            foreach ($items as $item) {
                $itemStmt->execute([
                    ':oid' => $orderId,
                    ':tid' => $item['id'],
                    ':price' => $item['price']
                ]);
            }

            $pdo->commit();
            $_SESSION['cart'] = [];
            header('Location: /success.php?order=' . urlencode($orderNumber));
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors['general'] = 'Beim Speichern der Bestellung ist ein Fehler aufgetreten.';
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Checkout</h1>
        <p class="section-subtitle">
            Gib deine Daten ein, um deine digitalen Downloads freizuschalten.
            Die Zahlung ist hier als Demo integriert und wird direkt als „bezahlt“ markiert.
        </p>

        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($errors['general']); ?></div>
        <?php endif; ?>

        <div class="grid grid-2">
            <div>
                <h2 style="font-size:1.3rem;margin-bottom:1rem;">Rechnungsdaten</h2>
                <form class="form" method="post" action="/checkout.php">
                    <div class="form-group">
                        <label for="name">Name*</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
                        <?php if (!empty($errors['name'])): ?>
                            <div class="form-error"><?php echo htmlspecialchars($errors['name']); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">E-Mail*</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                        <?php if (!empty($errors['email'])): ?>
                            <div class="form-error"><?php echo htmlspecialchars($errors['email']); ?></div>
                        <?php endif; ?>
                    </div>

                    <p style="font-size:0.85rem;color:#A0A0A0;margin-bottom:0.75rem;">
                        In einer echten Umgebung würdest du hier zu PayPal oder Stripe (Sandbox) weitergeleitet werden.
                        In dieser Demo wird die Bestellung nach dem Klick direkt als bezahlt gespeichert.
                    </p>

                    <button class="btn btn-primary" type="submit">Zahlung abschließen &amp; Downloads erhalten</button>
                </form>
            </div>
            <div>
                <h2 style="font-size:1.3rem;margin-bottom:1rem;">Bestellübersicht</h2>
                <div class="cart-items">
                    <?php foreach ($items as $item): ?>
                        <div class="cart-item">
                            <div>
                                <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                                <span style="font-size:0.85rem;color:#A0A0A0;">
                                    <?php echo (int)$item['quantity']; ?> × <?php echo format_price($item['price']); ?>
                                </span>
                            </div>
                            <div style="font-weight:600;">
                                <?php echo format_price($item['line_total']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div style="font-size:1.1rem;margin-top:0.75rem;">
                    Gesamt: <span class="price-tag"><?php echo format_price($total); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
