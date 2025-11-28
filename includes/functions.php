<?php
// NeonNest Designs - Helper-Funktionen

if (!function_exists('format_price')) {
    function format_price($amount)
    {
        return number_format((float)$amount, 2, ',', '.') . ' €';
    }
}

function get_featured_templates(PDO $pdo, $limit = 6)
{
    $stmt = $pdo->prepare("SELECT * FROM templates WHERE is_active = 1 ORDER BY created_at DESC LIMIT :lim");
    $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function get_all_templates(PDO $pdo)
{
    $stmt = $pdo->query("SELECT * FROM templates WHERE is_active = 1 ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

function get_template_by_id(PDO $pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM templates WHERE id = :id AND is_active = 1");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

function get_similar_templates(PDO $pdo, $templateId, $category, $limit = 3)
{
    $stmt = $pdo->prepare("SELECT * FROM templates WHERE is_active = 1 AND id != :id AND category = :cat ORDER BY created_at DESC LIMIT :lim");
    $stmt->bindValue(':id', (int)$templateId, PDO::PARAM_INT);
    $stmt->bindValue(':cat', $category);
    $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/* Cart */

function add_to_cart($templateId, $quantity = 1)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $templateId = (int)$templateId;
    if (isset($_SESSION['cart'][$templateId])) {
        $_SESSION['cart'][$templateId] += $quantity;
    } else {
        $_SESSION['cart'][$templateId] = $quantity;
    }
}

function remove_from_cart($templateId)
{
    $templateId = (int)$templateId;
    if (isset($_SESSION['cart'][$templateId])) {
        unset($_SESSION['cart'][$templateId]);
    }
}

function get_cart_items(PDO $pdo)
{
    $items = [];
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return $items;
    }

    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare("SELECT * FROM templates WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $templates = $stmt->fetchAll();

    foreach ($templates as $tpl) {
        $tpl['quantity'] = $_SESSION['cart'][$tpl['id']];
        $tpl['line_total'] = $tpl['price'] * $tpl['quantity'];
        $items[] = $tpl;
    }

    return $items;
}

function get_cart_total(PDO $pdo)
{
    $items = get_cart_items($pdo);
    $total = 0;
    foreach ($items as $item) {
        $total += $item['line_total'];
    }
    return $total;
}

function generate_order_number()
{
    return 'NN-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
}

/* Basic mail helper */

function send_neonnest_mail($to, $subject, $message, $fromEmail = 'no-reply@neonnest.local')
{
    $headers = "From: NeonNest Designs <{$fromEmail}>\r\n";
    $headers .= "Reply-To: {$fromEmail}\r\n";
    $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
    @mail($to, $subject, $message, $headers);
}
