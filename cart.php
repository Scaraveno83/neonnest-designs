<?php
$pageTitle = 'Cart';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $templateId = (int)($_POST['template_id'] ?? 0);
    if ($templateId > 0) {
        add_to_cart($templateId, 1);
    }
    header('Location: /cart.php');
    exit;
}

if (isset($_GET['remove'])) {
    $templateId = (int)$_GET['remove'];
    if ($templateId > 0) {
        remove_from_cart($templateId);
    }
    header('Location: /cart.php');
    exit;
}

$items = get_cart_items($pdo);
$total = get_cart_total($pdo);

include __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Warenkorb</h1>
        <p class="section-subtitle">
            Deine ausgewählten NeonNest Templates. Digitale Downloads sind nach Zahlung sofort abrufbar.
        </p>

        <?php if (empty($items)): ?>
            <div class="alert alert-info">Dein Warenkorb ist aktuell leer.</div>
            <a href="/templates.php" class="btn btn-primary">Templates ansehen</a>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($items as $item): ?>
                    <div class="cart-item">
                        <div>
                            <strong><?php echo htmlspecialchars($item['name']); ?></strong><br>
                            <span style="font-size:0.85rem;color:#A0A0A0;">
                                <?php echo htmlspecialchars($item['category']); ?>
                            </span>
                        </div>
                        <div style="text-align:right;font-size:0.9rem;">
                            <div><?php echo (int)$item['quantity']; ?> × <?php echo format_price($item['price']); ?></div>
                            <div style="font-weight:600;"><?php echo format_price($item['line_total']); ?></div>
                            <a href="/cart.php?remove=<?php echo (int)$item['id']; ?>" style="font-size:0.8rem;color:#F72585;">
                                Entfernen
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:1rem;">
                <div style="font-size:1.1rem;">
                    Zwischensumme: <span class="price-tag"><?php echo format_price($total); ?></span>
                </div>
                <div>
                    <a href="/checkout.php" class="btn btn-primary">Weiter zum Checkout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
