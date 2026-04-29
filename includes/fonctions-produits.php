<?php
require_once BASE_PATH . '/config/config.php';

function ensure_file_exists($file, $default = [])
{
    if (!file_exists($file)) {
        file_put_contents($file, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

function read_json_file($file)
{
    ensure_file_exists($file, []);
    $content = file_get_contents($file);
    if ($content === false || trim($content) === '') {
        return [];
    }

    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

function write_json_file($file, $data)
{
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        return false;
    }

    return file_put_contents($file, $json, LOCK_EX) !== false;
}

function get_all_products()
{
    return read_json_file(PRODUCTS_FILE);
}

function find_product_by_barcode($barcode)
{
    $products = get_all_products();
    foreach ($products as $product) {
        if (isset($product['code_barre']) && $product['code_barre'] === $barcode) {
            return $product;
        }
    }
    return null;
}

function find_product_index_by_barcode($barcode)
{
    $products = get_all_products();
    foreach ($products as $index => $product) {
        if (isset($product['code_barre']) && $product['code_barre'] === $barcode) {
            return $index;
        }
    }
    return -1;
}

function validate_product_data($data)
{
    $errors = [];

    if ($data['code_barre'] === '') {
        $errors[] = 'Le code-barres est obligatoire.';
    }
    if ($data['nom'] === '') {
        $errors[] = 'Le nom du produit est obligatoire.';
    }
    if ($data['prix_unitaire_ht'] === '' || !is_numeric($data['prix_unitaire_ht']) || (float) $data['prix_unitaire_ht'] <= 0) {
        $errors[] = 'Le prix unitaire HT doit être numérique et positif.';
    }
    if ($data['quantite_stock'] === '' || !is_numeric($data['quantite_stock']) || (int) $data['quantite_stock'] < 0) {
        $errors[] = 'La quantité en stock doit être numérique et positive ou nulle.';
    }
    if ($data['date_expiration'] === '') {
        $errors[] = 'La date d’expiration est obligatoire.';
    } else {
        $date = DateTime::createFromFormat('Y-m-d', $data['date_expiration']);
        if (!$date || $date->format('Y-m-d') !== $data['date_expiration']) {
            $errors[] = 'La date d’expiration doit être au format AAAA-MM-JJ.';
        }
    }
    if (find_product_by_barcode($data['code_barre']) !== null) {
        $errors[] = 'Ce code-barres existe déjà.';
    }

    return $errors;
}

function save_product($data)
{
    $products = get_all_products();
    $products[] = [
        'code_barre' => $data['code_barre'],
        'nom' => $data['nom'],
        'prix_unitaire_ht' => (float) $data['prix_unitaire_ht'],
        'date_expiration' => $data['date_expiration'],
        'quantite_stock' => (int) $data['quantite_stock'],
        'date_enregistrement' => today_date(),
    ];

    return write_json_file(PRODUCTS_FILE, $products);
}

function update_product_stock($barcode, $newStock)
{
    $products = get_all_products();
    $index = find_product_index_by_barcode($barcode);
    if ($index < 0) {
        return false;
    }

    $products[$index]['quantite_stock'] = (int) $newStock;
    return write_json_file(PRODUCTS_FILE, $products);
}
