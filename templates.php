<?php
$pageTitle = 'Templates';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

$templates = get_all_templates($pdo);
include __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Template Store</h1>
        <p class="section-subtitle">
            Wähle dein Neon-Template, passe Farben & Inhalte an und veröffentliche deinen Auftritt schneller.
        </p>

        <div class="grid grid-3">
            <?php foreach ($templates as $tpl): ?>
                <article class="template-card">
                    <?php if (!empty($tpl['thumbnail_image'])): ?>
                        <img src="<?php echo htmlspecialchars($tpl['thumbnail_image']); ?>"
                             alt="<?php echo htmlspecialchars($tpl['name']); ?>">
                    <?php endif; ?>
                    <div class="template-body">
                        <div class="template-title"><?php echo htmlspecialchars($tpl['name']); ?></div>
                        <div style="font-size:0.9rem;color:#A0A0A0;">
                            <?php echo htmlspecialchars($tpl['short_description']); ?>
                        </div>
                        <div class="template-meta">
                            <span class="price-tag"><?php echo format_price($tpl['price']); ?></span>
                            <span><?php echo htmlspecialchars($tpl['category']); ?></span>
                        </div>
                        <div style="margin-top:0.75rem;display:flex;gap:0.5rem;">
                            <a href="/template.php?id=<?php echo (int)$tpl['id']; ?>" class="btn btn-secondary">Details</a>
                            <form action="/cart.php" method="post">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="template_id" value="<?php echo (int)$tpl['id']; ?>">
                                <button class="btn btn-primary" type="submit">In den Warenkorb</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
