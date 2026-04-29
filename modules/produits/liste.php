<?php
require_once dirname(dirname(__DIR__)) . '/auth/session.php';
require_once BASE_PATH . '/includes/fonctions-produits.php';
require_roles(['manager', 'super_admin']);
$products = get_all_products();
require_once BASE_PATH . '/includes/header.php';
?>
<section>
    <h2>Liste des produits</h2>
    <a href="/facturation/modules/produits/enregistrer.php">Ajouter un produit</a>
    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Code-barres</th>
                <th>Nom</th>
                <th>Prix HT</th>
                <th>Expiration</th>
                <th>Stock</th>
                <th>Date enregistrement</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo h($product['code_barre']); ?></td>
                    <td><?php echo h($product['nom']); ?></td>
                    <td><?php echo format_currency($product['prix_unitaire_ht']); ?></td>
                    <td><?php echo h($product['date_expiration']); ?></td>
                    <td><?php echo h($product['quantite_stock']); ?></td>
                    <td><?php echo h($product['date_enregistrement']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php require_once BASE_PATH . '/includes/footer.php'; ?>
