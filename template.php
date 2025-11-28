<?php
$pageTitle = 'Template Details';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$template = get_template_by_id($pdo, $id);

if (!$template) {
    http_response_code(404);
    die('Template nicht gefunden.');
}

$similar = get_similar_templates($pdo, $template['id'], $template['category']);
$pageTitle = $template['name'];
include __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <h1 class="section-title"><?php echo htmlspecialchars($template['name']); ?></h1>
        <p class="section-subtitle"><?php echo htmlspecialchars($template['short_description']); ?></p>

        <div class="grid grid-2" style="align-items:flex-start;">
            <div>
                <?php if (!empty($template['hero_image'])): ?>
                    <img src="<?php echo htmlspecialchars($template['hero_image']); ?>"
                         alt="<?php echo htmlspecialchars($template['name']); ?>"
                         style="width:100%;border-radius:20px;border:1px solid rgba(123,47,247,0.4);box-shadow:0 0 30px rgba(0,0,0,0.7);">
                <?php endif; ?>
            </div>
            <div>
                <div style="margin-bottom:1rem;">
                    <span class="price-tag" style="font-size:1.4rem;"><?php echo format_price($template['price']); ?></span>
                </div>
                <p><?php echo nl2br(htmlspecialchars($template['long_description'])); ?></p>

                <div style="margin-top:1.5rem;display:flex;gap:0.75rem;">
                    <a class="btn btn-secondary" href="https://example.com/demo/<?php echo urlencode($template['slug']); ?>" target="_blank" rel="noopener">
                        Live Demo (Dummy)
                    </a>
                    <form action="/cart.php" method="post">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="template_id" value="<?php echo (int)$template['id']; ?>">
                        <button class="btn btn-primary" type="submit">In den Warenkorb</button>
                    </form>
                </div>
            </div>
        </div>

        <?php if (!empty($similar)): ?>
            <hr style="border-color:rgba(160,160,160,0.3);margin:3rem 0 2rem;">
            <h2 class="section-title" style="font-size:1.6rem;">Ähnliche Templates</h2>
            <div class="grid grid-3">
                <?php foreach ($similar as $tpl): ?>
                    <article class="template-card">
                        <?php if (!empty($tpl['thumbnail_image'])): ?>
                            <img src="<?php echo htmlspecialchars($tpl['thumbnail_image']); ?>"
                                 alt="<?php echo htmlspecialchars($tpl['name']); ?>">
                        <?php endif; ?>
                        <div class="template-body">
                            <div class="template-title"><?php echo htmlspecialchars($tpl['name']); ?></div>
                            <div class="template-meta">
                                <span class="price-tag"><?php echo format_price($tpl['price']); ?></span>
                            </div>
                            <div style="margin-top:0.75rem;display:flex;gap:0.5rem;">
                                <a href="/template.php?id=<?php echo (int)$tpl['id']; ?>" class="btn btn-secondary">Details</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
