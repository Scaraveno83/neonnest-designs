<?php
$pageTitle = 'Contact';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $msg   = trim($_POST['message'] ?? '');

    if ($name === '') {
        $errors['name'] = 'Bitte gib deinen Namen ein.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Bitte gib eine gültige E-Mail-Adresse an.';
    }
    if ($msg === '') {
        $errors['message'] = 'Bitte schreib eine kurze Nachricht.';
    }

    if (empty($errors)) {
        // In service_requests speichern mit Typ "Kontakt"
        $stmt = $pdo->prepare("INSERT INTO service_requests (name, email, budget, project_type, message, created_at)
                               VALUES (:name, :email, :budget, :type, :message, NOW())");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':budget' => 'n/a',
            ':type' => 'Kontakt',
            ':message' => $msg
        ]);

        // Admin-Mail (Demo)
        $adminMail = 'admin@neonnest.local';
        $subject = 'Neue Kontaktanfrage bei NeonNest';
        $body = "Name: {$name}\nE-Mail: {$email}\n\nNachricht:\n{$msg}";
        send_neonnest_mail($adminMail, $subject, $body);

        $successMessage = 'Danke für deine Nachricht! Wir melden uns bei dir.';
        $name = $email = $msg = '';
    }
}

include __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Kontakt</h1>
        <p class="section-subtitle">
            Du hast Fragen, möchtest ein Projekt anstoßen oder brauchst eine Einschätzung?
            Schreib uns kurz, worum es geht.
        </p>

        <?php if ($successMessage): ?>
            <div class="form-success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>

        <form class="form" method="post" action="/contact.php">
            <div class="form-group">
                <label for="name">Name*</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>">
                <?php if (!empty($errors['name'])): ?>
                    <div class="form-error"><?php echo htmlspecialchars($errors['name']); ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">E-Mail*</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                <?php if (!empty($errors['email'])): ?>
                    <div class="form-error"><?php echo htmlspecialchars($errors['email']); ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="message">Nachricht*</label>
                <textarea id="message" name="message" rows="6"><?php echo htmlspecialchars($msg ?? ''); ?></textarea>
                <?php if (!empty($errors['message'])): ?>
                    <div class="form-error"><?php echo htmlspecialchars($errors['message']); ?></div>
                <?php endif; ?>
            </div>
            <button class="btn btn-primary" type="submit">Nachricht senden</button>
        </form>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
