-- NeonNest Designs – Datenbankstruktur & Dummy-Daten

USE db_453539_4;


-- Templates
DROP TABLE IF EXISTS templates;
CREATE TABLE templates (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    short_description TEXT,
    long_description TEXT,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    thumbnail_image VARCHAR(255),
    hero_image VARCHAR(255),
    download_file VARCHAR(255),
    category VARCHAR(100),
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders
DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    customer_email VARCHAR(255) NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order Items
DROP TABLE IF EXISTS order_items;
CREATE TABLE order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    template_id INT UNSIGNED NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_order_items_template FOREIGN KEY (template_id) REFERENCES templates(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Service Requests (inkl. Kontaktanfragen)
DROP TABLE IF EXISTS service_requests;
CREATE TABLE service_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    budget VARCHAR(100),
    project_type VARCHAR(100),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dummy-Templates

INSERT INTO templates (slug, name, short_description, long_description, price, thumbnail_image, hero_image, download_file, category, is_active) VALUES
('neon-landing-kit',
 'Neon Landing Kit',
 'Dunkles Landingpage-Template für moderne SaaS- und Produkt-Launches.',
 'Der Neon Landing Kit ist ein vollständig responsives Landingpage-Template im NeonNest-Stil. Ideal für Produkt-Launches, digitale Services oder SaaS-Tools. Enthält Hero-Sektion, Feature-Grid, Testimonial-Bereich und Pricing-Sektion. Der Code ist klar strukturiert und kommentiert, sodass du Inhalte und Farben schnell anpassen kannst.',
 49.00,
 '/assets/img/template-landing-thumb.jpg',
 '/assets/img/template-landing-hero.jpg',
 '/downloads/neon-landing-kit.zip',
 'Landing Page',
 1
),
('neon-portfolio-grid',
 'Neon Portfolio Grid',
 'Portfolio-Template für Designer, Fotografie & Studios mit starkem Grid-Fokus.',
 'Neon Portfolio Grid richtet sich an Designer:innen, Studios und Creator, die ihre Arbeiten in einem klaren Grid präsentieren möchten. Fokus liegt auf großen Thumbnails, Cases und einem About-Bereich mit Skills & Services. Alle Projekte sind leicht erweiterbar, sodass du dein Portfolio stetig updaten kannst.',
 59.00,
 '/assets/img/template-portfolio-thumb.jpg',
 '/assets/img/template-portfolio-hero.jpg',
 '/downloads/neon-portfolio-kit.zip',
 'Portfolio',
 1
),
('neon-agency-site',
 'Neon Agency Site',
 'Mehrseitige Agency-Website mit Service-Übersicht und Case-Bereich.',
 'Neon Agency Site ist ein mehrseitiges Template für kleine Agenturen und Studios. Enthält Startseite, Services, Cases, About- und Kontaktseite im einheitlichen NeonNest-Look. Die Navigation ist für mehr Inhalte vorbereitet, sodass du bei Bedarf weitere Seiten ergänzen kannst.',
 79.00,
 '/assets/img/template-agency-thumb.jpg',
 '/assets/img/template-agency-hero.jpg',
 '/downloads/neon-agency-kit.zip',
 'Agency',
 1
);
