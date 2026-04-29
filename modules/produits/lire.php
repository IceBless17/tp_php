<?php
require_once dirname(dirname(__DIR__)) . '/auth/session.php';
require_once BASE_PATH . '/includes/fonctions-produits.php';
require_roles(['manager', 'super_admin', 'caissier']);

$barcode = get('code_barre');
$product = null;
if ($barcode !== '') {
    $product = find_product_by_barcode($barcode);
}

header('Content-Type: application/json');
echo json_encode([
    'success' => $product !== null,
    'product' => $product,
    'message' => $product ? 'Produit trouvé.' : 'Produit introuvable.',
], JSON_UNESCAPED_UNICODE);
