<?php
$pageTitle = 'Services';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = trim($_POST['name'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $budget = trim($_POST['budget'] ?? '');
    $type   = trim($_POST['project_type'] ?? '');
    $msg    = trim($_POST['message'] ?? '');

    if ($name === '') {
        $errors['name'] = 'Bitte gib deinen Namen ein.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Bitte gib eine gültige E-Mail-Adresse an.';
    }
    if ($msg === '') {
        $errors['message'] = 'Bitte beschreibe dein Projekt.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO service_requests (name, email, budget, project_type, message, created_at)
                               VALUES (:name, :email, :budget, :type, :message, NOW())");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':budget' => $budget,
            ':type' => $type,
            ':message' => $msg
        ]);

        // E-Mail an Admin (Demo)
        $adminMail = 'admin@neonnest.local';
        $subject = 'Neue Service-Anfrage bei NeonNest';
        $body = "Name: {$name}\nE-Mail: {$email}\nBudget: {$budget}\nTyp: {$type}\n\nNachricht:\n{$msg}";
        send_neonnest_mail($adminMail, $subject, $body);

        $successMessage = 'Danke für deine Anfrage! Wir melden uns schnellstmöglich bei dir.';
        $name = $email = $budget = $type = $msg = '';
    }
}

include __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Services & Webdesign-Pakete</h1>
        <p class="section-subtitle">
            NeonNest begleitet dich von der ersten Idee bis zum finalen Launch – mit klarer Struktur, iterativen Feedback-Loops
            und einem Design, das im Dunkeln leuchtet.
        </p>

        <div class="service-packages" style="margin-bottom:3rem;">
            <div class="service-card">
                <h3>Starter Site</h3>
                <p>Ideal für Pre-Launch-Seiten, Produktdrops oder Events mit klarem Fokus.</p>
                <ul style="font-size:0.9rem;margin-top:0.5rem;">
                    <li>• Onepager mit bis zu 3 Sektionen</li>
                    <li>• Anpassung an deine Brand-Farben</li>
                    <li>• Übergabe als HTML/CSS oder in deinem System</li>
                </ul>
            </div>
            <div class="service-card">
                <h3>Brand Site</h3>
                <p>Mehrseitige Website mit Storytelling, Case-Bereich und Conversion-Fokus.</p>
                <ul style="font-size:0.9rem;margin-top:0.5rem;">
                    <li>• Bis zu 6 individuelle Seiten</li>
                    <li>• Designsystem & Komponentenbibliothek</li>
                    <li>• Optional: Basic Copy-Support</li>
                </ul>
            </div>
            <div class="service-card">
                <h3>Custom Experience</h3>
                <p>Komplexe Interfaces, Dashboards, Member-Bereiche oder App-UIs im NeonNest-Stil.</p>
                <ul style="font-size:0.9rem;margin-top:0.5rem;">
                    <li>• UX-Konzeption & Wireframes</li>
                    <li>• Motion- & Microinteraction-Konzept</li>
                    <li>• Enge Zusammenarbeit mit deinem Dev-Team</li>
                </ul>
            </div>
        </div>

        <h2 class="section-title" style="font-size:1.6rem;">Projekt anfragen</h2>
        <p class="section-subtitle">
            Erzähl kurz, was du vorhast – wir melden uns mit einem Vorschlag zum Set-up.
        </p>

        <?php if ($successMessage): ?>
            <div class="form-success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>

        <form class="form" method="post" action="/services.php">
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
                <label for="budget">Budget-Rahmen</label>
                <input type="text" id="budget" name="budget" placeholder="z.B. 1.500–3.000 €"
                       value="<?php echo htmlspecialchars($budget ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="project_type">Projekt-Typ</label>
                <select id="project_type" name="project_type">
                    <option value="">Bitte auswählen</option>
                    <option value="Starter Site" <?php echo (($type ?? '') === 'Starter Site') ? 'selected' : ''; ?>>Starter Site</option>
                    <option value="Brand Site" <?php echo (($type ?? '') === 'Brand Site') ? 'selected' : ''; ?>>Brand Site</option>
                    <option value="Custom Experience" <?php echo (($type ?? '') === 'Custom Experience') ? 'selected' : ''; ?>>Custom Experience</option>
                    <option value="Sonstiges" <?php echo (($type ?? '') === 'Sonstiges') ? 'selected' : ''; ?>>Sonstiges</option>
                </select>
            </div>
            <div class="form-group">
                <label for="message">Projektbeschreibung*</label>
                <textarea id="message" name="message" rows="6"><?php echo htmlspecialchars($msg ?? ''); ?></textarea>
                <?php if (!empty($errors['message'])): ?>
                    <div class="form-error"><?php echo htmlspecialchars($errors['message']); ?></div>
                <?php endif; ?>
            </div>
            <button class="btn btn-primary" type="submit">Anfrage senden</button>
        </form>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
