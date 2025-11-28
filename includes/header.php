<?php
if (!isset($pageTitle)) {
    $pageTitle = 'NeonNest Designs';
}
$currentPath = $_SERVER['PHP_SELF'] ?? '';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?> – NeonNest Designs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="NeonNest Designs – Neon Webdesign, Website Templates & individuelle Projekte.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container">
        <nav class="navbar">
            <a href="/index.php" class="logo-wrap" aria-label="NeonNest Designs Startseite">
                <div class="logo-icon"></div>
                <div>
                    <div class="logo-text">NeonNest Designs</div>
                    <div class="logo-tagline">Neon Web Templates & Studio</div>
                </div>
            </a>

            <button class="nav-toggle" aria-label="Menü öffnen">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <ul class="nav-links">
                <li><a href="/index.php" class="<?php echo strpos($currentPath, 'index.php') !== false ? 'active' : ''; ?>">Home</a></li>
                <li><a href="/templates.php" class="<?php echo strpos($currentPath, 'templates.php') !== false || strpos($currentPath, 'template.php') !== false ? 'active' : ''; ?>">Templates</a></li>
                <li><a href="/services.php" class="<?php echo strpos($currentPath, 'services.php') !== false ? 'active' : ''; ?>">Services</a></li>
                <li><a href="/about.php" class="<?php echo strpos($currentPath, 'about.php') !== false ? 'active' : ''; ?>">About</a></li>
                <li><a href="/contact.php" class="<?php echo strpos($currentPath, 'contact.php') !== false ? 'active' : ''; ?>">Contact</a></li>
                <li><a href="/cart.php" class="<?php echo strpos($currentPath, 'cart.php') !== false || strpos($currentPath, 'checkout.php') !== false ? 'active' : ''; ?>">Cart</a></li>
            </ul>
        </nav>
    </div>
</header>
<main>
