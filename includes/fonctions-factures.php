<?php
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/fonctions-produits.php';

function get_all_invoices()
{
    return read_json_file(INVOICES_FILE);
}

function generate_invoice_id()
{
    $invoices = get_all_invoices();
    $today = date('Ymd');
    $count = 0;

    foreach ($invoices as $invoice) {
        if (isset($invoice['id_facture']) && strpos($invoice['id_facture'], 'FAC-' . $today) === 0) {
            $count++;
        }
    }

    return 'FAC-' . $today . '-' . str_pad((string) ($count + 1), 3, '0', STR_PAD_LEFT);
}

function get_cart()
{
    return $_SESSION['cart'] ?? [];
}

function save_cart($cart)
{
    $_SESSION['cart'] = array_values($cart);
}

function clear_cart()
{
    $_SESSION['cart'] = [];
}

function add_product_to_cart($barcode, $quantity)
{
    $product = find_product_by_barcode($barcode);
    if (!$product) {
        return [false, 'Produit inconnu. Veuillez demander au Manager de l’enregistrer.'];
    }

    $quantity = (int) $quantity;
    if ($quantity <= 0) {
        return [false, 'La quantité doit être supérieure à zéro.'];
    }

    $cart = get_cart();
    $existingQuantity = 0;
    $foundIndex = -1;

    foreach ($cart as $index => $item) {
        if ($item['code_barre'] === $barcode) {
            $existingQuantity = (int) $item['quantite'];
            $foundIndex = $index;
            break;
        }
    }

    $newQuantity = $existingQuantity + $quantity;
    if ($newQuantity > (int) $product['quantite_stock']) {
        return [false, 'Stock insuffisant pour ce produit.'];
    }

    $line = [
        'code_barre' => $product['code_barre'],
        'nom' => $product['nom'],
        'prix_unitaire_ht' => (float) $product['prix_unitaire_ht'],
        'quantite' => $newQuantity,
        'sous_total_ht' => (float) $product['prix_unitaire_ht'] * $newQuantity,
    ];

    if ($foundIndex >= 0) {
        $cart[$foundIndex] = $line;
    } else {
        $cart[] = $line;
    }

    save_cart($cart);
    return [true, 'Produit ajouté à la facture.'];
}

function remove_item_from_cart($barcode)
{
    $cart = get_cart();
    $filtered = [];
    foreach ($cart as $item) {
        if ($item['code_barre'] !== $barcode) {
            $filtered[] = $item;
        }
    }
    save_cart($filtered);
}

function calculate_cart_totals($cart)
{
    $totalHt = 0;
    foreach ($cart as $item) {
        $totalHt += (float) $item['sous_total_ht'];
    }
    $tva = $totalHt * TVA_TAUX;
    $totalTtc = $totalHt + $tva;

    return [
        'total_ht' => $totalHt,
        'tva' => $tva,
        'total_ttc' => $totalTtc,
    ];
}

function save_invoice($cashierName)
{
    $cart = get_cart();
    if (empty($cart)) {
        return [false, 'La facture est vide.'];
    }

    foreach ($cart as $item) {
        $product = find_product_by_barcode($item['code_barre']);
        if (!$product) {
            return [false, 'Un produit de la facture est introuvable.'];
        }
        if ((int) $item['quantite'] > (int) $product['quantite_stock']) {
            return [false, 'Le stock a changé. Veuillez vérifier la quantité du produit : ' . $item['nom']];
        }
    }

    $totals = calculate_cart_totals($cart);
    $invoice = [
        'id_facture' => generate_invoice_id(),
        'date' => today_date(),
        'heure' => now_time(),
        'caissier' => $cashierName,
        'articles' => $cart,
        'total_ht' => $totals['total_ht'],
        'tva' => $totals['tva'],
        'total_ttc' => $totals['total_ttc'],
    ];

    $invoices = get_all_invoices();
    $invoices[] = $invoice;

    if (!write_json_file(INVOICES_FILE, $invoices)) {
        return [false, 'Erreur lors de l’enregistrement de la facture.'];
    }

    foreach ($cart as $item) {
        $product = find_product_by_barcode($item['code_barre']);
        $newStock = (int) $product['quantite_stock'] - (int) $item['quantite'];
        update_product_stock($item['code_barre'], $newStock);
    }

    $_SESSION['last_invoice'] = $invoice;
    clear_cart();
    return [true, 'Facture enregistrée avec succès.'];
}

function filter_invoices_by_date($date)
{
    $invoices = get_all_invoices();
    return array_values(array_filter($invoices, function ($invoice) use ($date) {
        return isset($invoice['date']) && $invoice['date'] === $date;
    }));
}

function filter_invoices_by_month($month)
{
    $invoices = get_all_invoices();
    return array_values(array_filter($invoices, function ($invoice) use ($month) {
        return isset($invoice['date']) && strpos($invoice['date'], $month) === 0;
    }));
}
