<?php
require_once dirname(dirname(__DIR__)) . '/auth/session.php';
require_once BASE_PATH . '/includes/fonctions-factures.php';
require_roles(['caissier', 'manager', 'super_admin']);

header('Content-Type: application/json');
echo json_encode(calculate_cart_totals(get_cart()), JSON_UNESCAPED_UNICODE);
