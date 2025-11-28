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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$template = get_template_by_id($pdo, $id);

if (!$template) {
    die('Template nicht gefunden.');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $short = trim($_POST['short_description'] ?? '');
    $long = trim($_POST['long_description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if ($name === '' || $slug === '') {
        $errors[] = 'Name und Slug dürfen nicht leer sein.';
    }

    $thumbPath = $template['thumbnail_image'];
    $heroPath = $template['hero_image'];
    $downloadPath = $template['download_file'];

    if (!empty($_FILES['thumbnail']['name'])) {
        $targetDir = '/assets/img/';
        $fileName = 'thumb_' . time() . '_' . basename($_FILES['thumbnail']['name']);
        $thumbPath = $targetDir . $fileName;
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $thumbPath);
    }

    if (!empty($_FILES['hero']['name'])) {
        $targetDir = '/assets/img/';
        $fileName = 'hero_' . time() . '_' . basename($_FILES['hero']['name']);
        $heroPath = $targetDir . $fileName;
        move_uploaded_file($_FILES['hero']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $heroPath);
    }

    if (!empty($_FILES['download']['name'])) {
        $targetDir = '/downloads/';
        $fileName = 'tpl_' . time() . '_' . basename($_FILES['download']['name']);
        $downloadPath = $targetDir . $fileName;
        move_uploaded_file($_FILES['download']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $downloadPath);
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE templates SET 
            slug = :slug,
            name = :name,
            short_description = :short,
            long_description = :long,
            price = :price,
            thumbnail_image = :thumb,
            hero_image = :hero,
            download_file = :download,
            category = :cat,
            is_active = :active
            WHERE id = :id");
        $stmt->execute([
            ':slug' => $slug,
            ':name' => $name,
            ':short' => $short,
            ':long' => $long,
            ':price' => $price,
            ':thumb' => $thumbPath,
            ':hero' => $heroPath,
            ':download' => $downloadPath,
            ':cat' => $category,
            ':active' => $isActive,
            ':id' => $id
        ]);
        header('Location: /admin/templates.php');
        exit;
    }
}

$pageTitle = 'Template bearbeiten';
include __DIR__ . '/../includes/header.php';
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Template bearbeiten</h1>
        <p class="section-subtitle">
            Passe Name, Beschreibung, Preis oder Dateien dieses Templates an.
        </p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $err): ?>
                    <?php echo htmlspecialchars($err); ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form class="form" method="post" enctype="multipart/form-data"
              action="/admin/edit-template.php?id=<?php echo (int)$template['id']; ?>">
            <div class="form-group">
                <label for="name">Name*</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($template['name']); ?>">
            </div>
            <div class="form-group">
                <label for="slug">Slug*</label>
                <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($template['slug']); ?>">
            </div>
            <div class="form-group">
                <label for="category">Kategorie</label>
                <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($template['category']); ?>">
            </div>
            <div class="form-group">
                <label for="price">Preis (€)</label>
                <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($template['price']); ?>">
            </div>
            <div class="form-group">
                <label for="short_description">Kurzbeschreibung</label>
                <textarea id="short_description" name="short_description" rows="2"><?php echo htmlspecialchars($template['short_description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="long_description">Langbeschreibung</label>
                <textarea id="long_description" name="long_description" rows="5"><?php echo htmlspecialchars($template['long_description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="thumbnail">Thumbnail-Bild (optional neu hochladen)</label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                <?php if (!empty($template['thumbnail_image'])): ?>
                    <div style="font-size:0.8rem;color:#A0A0A0;margin-top:0.25rem;">
                        Aktuell: <?php echo htmlspecialchars($template['thumbnail_image']); ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="hero">Hero-Bild (optional neu hochladen)</label>
                <input type="file" id="hero" name="hero" accept="image/*">
                <?php if (!empty($template['hero_image'])): ?>
                    <div style="font-size:0.8rem;color:#A0A0A0;margin-top:0.25rem;">
                        Aktuell: <?php echo htmlspecialchars($template['hero_image']); ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="download">ZIP-Download (optional neu hochladen)</label>
                <input type="file" id="download" name="download" accept=".zip">
                <?php if (!empty($template['download_file'])): ?>
                    <div style="font-size:0.8rem;color:#A0A0A0;margin-top:0.25rem;">
                        Aktuell: <?php echo htmlspecialchars($template['download_file']); ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" <?php echo $template['is_active'] ? 'checked' : ''; ?>> Aktiv
                </label>
            </div>
            <button class="btn btn-primary" type="submit">Änderungen speichern</button>
        </form>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
