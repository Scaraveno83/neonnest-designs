<?php
$pageTitle = 'About';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/functions.php';
include __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container">
        <h1 class="section-title">Über NeonNest Designs</h1>
        <p class="section-subtitle">
            NeonNest verbindet visuelles Storytelling mit sauberem Code. Wir arbeiten remote, fokussiert und
            mit einem klaren Blick auf deine Marke.
        </p>

        <div class="grid grid-2">
            <div>
                <h2 style="font-size:1.4rem;margin-bottom:0.75rem;">Kurzgeschichte</h2>
                <p>
                    NeonNest ist aus der Idee entstanden, dunkle UI, klare Typografie und starke Kontraste
                    zu kombinieren – inspiriert von Synthwave, Cyberpunk und modernen Produktseiten.
                </p>
                <p style="margin-top:0.75rem;">
                    Wir sind ein kleines Studio mit Sitz in Berlin (Remote-first) und unterstützen
                    Solo-Selbstständige, Agenturen und junge SaaS-Teams mit digital-first Auftritten.
                </p>
            </div>
            <div>
                <h2 style="font-size:1.4rem;margin-bottom:0.75rem;">Skills</h2>
                <ul style="list-style:none;font-size:0.95rem;">
                    <li>• UI/UX Design für Web & Webapps</li>
                    <li>• Designsysteme & Komponenten-Bibliotheken</li>
                    <li>• Prototyping & Interactions</li>
                    <li>• Performance-orientierte Umsetzung (HTML/CSS/JS)</li>
                </ul>

                <h2 style="font-size:1.4rem;margin:1.5rem 0 0.75rem;">Tools</h2>
                <ul style="list-style:none;font-size:0.95rem;">
                    <li>• Figma & FigJam</li>
                    <li>• VS Code, Git</li>
                    <li>• Browser DevTools</li>
                    <li>• Notion, Linear, Slack</li>
                </ul>

                <h2 style="font-size:1.4rem;margin:1.5rem 0 0.75rem;">Mission</h2>
                <p>
                    Wir wollen Websites gestalten, die sowohl technisch sauber sind als auch emotional
                    hängen bleiben – ohne schwerfällige Overhead-Strukturen, sondern mit direktem Austausch.
                </p>
            </div>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
