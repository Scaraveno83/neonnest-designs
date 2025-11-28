<?php
$pageTitle = 'Home';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';

$featuredTemplates = get_featured_templates($pdo, 3);
include __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <div class="container hero-inner">
        <div>
            <div class="badge">
                <span class="badge-dot"></span>
                Neon-scharfe Webauftritte
            </div>
            <h1 class="hero-title">
                <span class="hero-gradient">NeonNest Designs</span><br>
                Templates & Studio für mutige Marken.
            </h1>
            <p class="hero-text">
                Wir entwickeln leuchtende, schnelle Websites für Creator, Agenturen und Startups.
                Wähle ein fertiges Template oder buche ein individuelles Projekt mit unserem Studio.
            </p>

            <div class="hero-actions">
                <a class="btn btn-primary" href="/templates.php">Templates entdecken</a>
                <a class="btn btn-secondary" href="/services.php">Projekt anfragen</a>
            </div>

            <p style="font-size:0.85rem;color:#A0A0A0;">
                Made for Webflow-Fans, Code-Lover und alle, die dunkle Neon-Themes lieben.
            </p>
        </div>

        <div class="hero-visual">
            <div class="hero-orbit">
                <div class="hero-card">
                    <div class="hero-card-header">
                        <div class="hero-card-title">Neon Landing Kit</div>
                        <div class="hero-card-pill">Ready to ship</div>
                    </div>
                    <div class="hero-card-main">
                        <div style="height:150px;border-radius:14px;background:radial-gradient(circle at 0 0,#00F5D4,transparent 55%),radial-gradient(circle at 100% 100%,#F72585,transparent 65%);position:relative;overflow:hidden;">
                            <div style="position:absolute;inset:0.65rem;border-radius:12px;border:1px solid rgba(255,255,255,0.15);background:linear-gradient(135deg,rgba(11,15,43,0.92),rgba(4,5,20,0.98));"></div>
                        </div>
                    </div>
                    <div class="hero-card-footer">
                        <span>3 Layouts • 12 Sektionen</span>
                        <span style="color:#00F5D4;font-weight:600;">49 €</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="templates">
    <div class="container">
        <h2 class="section-title">Featured Templates</h2>
        <p class="section-subtitle">
            Sofort einsatzbereite Web-Templates – optimiert für schnelle Umsetzung und klare Conversion.
        </p>

        <div class="grid grid-3">
            <?php foreach ($featuredTemplates as $tpl): ?>
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

<section class="section" id="services-teaser">
    <div class="container">
        <h2 class="section-title">NeonNest Studio – individuelle Projekte</h2>
        <p class="section-subtitle">
            Du hast eine Vision, aber kein fertiges Konzept? Wir entwickeln ein digitales Designsystem,
            das perfekt zu deiner Marke passt.
        </p>
        <div class="service-packages">
            <div class="service-card">
                <h3>Starter Site</h3>
                <p>Kompakte Onepager-Lösung für Launches, Events & simple Produktseiten.</p>
                <ul style="font-size:0.9rem;margin-top:0.5rem;">
                    <li>• Bis zu 3 Sektionen</li>
                    <li>• Mobile-optimiert</li>
                    <li>• Launch innerhalb von 10 Tagen (Richtwert)</li>
                </ul>
            </div>
            <div class="service-card">
                <h3>Brand Site</h3>
                <p>Mehrseitige Website mit Fokus auf Storytelling, Cases und klare Struktur.</p>
                <ul style="font-size:0.9rem;margin-top:0.5rem;">
                    <li>• Bis zu 6 Seiten</li>
                    <li>• Styleguide & Komponentensystem</li>
                    <li>• Content-Coaching inkl.</li>
                </ul>
            </div>
            <div class="service-card">
                <h3>Custom Experience</h3>
                <p>Komplett individuelles Webdesign für Produkte, Apps oder Communities.</p>
                <ul style="font-size:0.9rem;margin-top:0.5rem;">
                    <li>• UX-Konzept & Wireframes</li>
                    <li>• Animationskonzept im Neon-Stil</li>
                    <li>• Figma- oder Code-Delivery</li>
                </ul>
            </div>
        </div>
        <div style="margin-top:1.5rem;">
            <a href="/services.php" class="btn btn-primary">Mehr zu Services</a>
        </div>
    </div>
</section>

<section class="section" id="why">
    <div class="container">
        <h2 class="section-title">Warum NeonNest?</h2>
        <p class="section-subtitle">
            Wir kombinieren High-Contrast-Neon-Design mit klarer Struktur und Performance-Fokus.
        </p>

        <div class="why-grid">
            <article class="why-item">
                <div>⚡</div>
                <h3 class="why-title">Schnelle Umsetzung</h3>
                <p>Fertige Templates und modulare Komponenten sparen Zeit im Projektstart.</p>
            </article>
            <article class="why-item">
                <div>🎛️</div>
                <h3 class="why-title">Flexibel anpassbar</h3>
                <p>Alle Layouts sind sauber strukturiert, kommentiert und leicht erweiterbar.</p>
            </article>
            <article class="why-item">
                <div>🌒</div>
                <h3 class="why-title">Dark Mode first</h3>
                <p>Kontraststarke Dark-UI mit Neon-Akzenten – ideal für Tech, Gaming & Creator.</p>
            </article>
            <article class="why-item">
                <div>💬</div>
                <h3 class="why-title">Direkte Kommunikation</h3>
                <p>Du hast direkten Kontakt zum Designer – ohne Agentur-Zwischenstufen.</p>
            </article>
        </div>
    </div>
</section>

<section class="section" id="about-teaser">
    <div class="container">
        <h2 class="section-title">Über NeonNest</h2>
        <p class="section-subtitle">
            NeonNest ist ein kleines Webstudio mit Fokus auf digital-first Markenauftritte,
            die im Dunkeln leuchten. Wir arbeiten remote, kollaborativ & iterativ.
        </p>
        <a href="/about.php" class="btn btn-secondary">Mehr erfahren</a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
