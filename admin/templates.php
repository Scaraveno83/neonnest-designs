<?php
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/functions.php';
require __DIR__ . '/../includes/admin-auth.php';

// Login über Session oder Basic Header
enforce_admin_auth();

$pageTitle = 'Admin Templates';

// Template löschen
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM templates WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header('Location: /admin/templates.php');
    exit;
}

// Neues Template speichern
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
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

    $thumbPath = '';
    $heroPath = '';
    $downloadPath = '';

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
        $stmt = $pdo->prepare("INSERT INTO templates 
            (slug, name, short_description, long_description, price, thumbnail_image, hero_image, download_file, category, is_active, created_at)
            VALUES (:slug, :name, :short, :long, :price, :thumb, :hero, :download, :cat, :active, NOW())");
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
            ':active' => $isActive
        ]);
        header('Location: /admin/templates.php');
        exit;
    }
}

$templates = get_all_templates($pdo);
$pageTitle = 'Admin Templates';
include __DIR__ . '/../includes/header.php';
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Templates verwalten</h1>
        <p class="section-subtitle">
            Lege neue Templates an oder bearbeite bestehende. Uploads werden im Ordner <code>/assets/img</code>
            und <code>/downloads</code> gespeichert.
        </p>
        <p class="section-subtitle" style="margin-top:0.5rem;">
            Angemeldet als Admin. <a href="/admin/logout.php">Logout</a>
        </p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $err): ?>
                    <?php echo htmlspecialchars($err); ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <h2 style="font-size:1.3rem;margin-top:1.5rem;">Neues Template hinzufügen</h2>

        <form class="form" method="post" enctype="multipart/form-data" action="/admin/templates.php">
            <input type="hidden" name="action" value="create">
            <div class="form-group">
                <label for="name">Name*</label>
                <input type="text" id="name" name="name">
            </div>
            <div class="form-group">
                <label for="slug">Slug* (für URLs)</label>
                <input type="text" id="slug" name="slug" placeholder="z.B. neon-landing-kit">
            </div>
            <div class="form-group">
                <label for="category">Kategorie</label>
                <input type="text" id="category" name="category" placeholder="Landing Page, Portfolio, Agency …">
            </div>
            <div class="form-group">
                <label for="price">Preis (€)</label>
                <input type="number" step="0.01" id="price" name="price">
            </div>
            <div class="form-group">
                <label for="short_description">Kurzbeschreibung</label>
                <textarea id="short_description" name="short_description" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label for="long_description">Langbeschreibung</label>
                <textarea id="long_description" name="long_description" rows="5"></textarea>
            </div>
            <div class="form-group">
                <label for="thumbnail">Thumbnail-Bild</label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
            </div>
            <div class="form-group">
                <label for="hero">Hero-Bild</label>
                <input type="file" id="hero" name="hero" accept="image/*">
            </div>
            <div class="form-group">
                <label for="download">ZIP-Download</label>
                <input type="file" id="download" name="download" accept=".zip">
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" checked> Aktiv
                </label>
            </div>
            <button class="btn btn-primary" type="submit">Template speichern</button>
        </form>

        <h2 style="font-size:1.3rem;margin-top:2.5rem;">Bestehende Templates</h2>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Kategorie</th>
                <th>Preis</th>
                <th>Aktiv</th>
                <th>Aktionen</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($templates as $tpl): ?>
                <tr>
                    <td><?php echo (int)$tpl['id']; ?></td>
                    <td><?php echo htmlspecialchars($tpl['name']); ?></td>
                    <td><?php echo htmlspecialchars($tpl['category']); ?></td>
                    <td><?php echo format_price($tpl['price']); ?></td>
                    <td><?php echo $tpl['is_active'] ? 'Ja' : 'Nein'; ?></td>
                    <td>
                        <a href="/admin/edit-template.php?id=<?php echo (int)$tpl['id']; ?>">Bearbeiten</a> ·
                        <a href="/admin/templates.php?delete=<?php echo (int)$tpl['id']; ?>"
                           onclick="return confirm('Template wirklich löschen?');">Löschen</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
