<?php
require_once dirname(dirname(__DIR__)) . '/auth/session.php';
require_once BASE_PATH . '/includes/fonctions-factures.php';
require_roles(['caissier', 'manager', 'super_admin']);
$invoice = $_SESSION['last_invoice'] ?? null;
require_once BASE_PATH . '/includes/header.php';
?>
<section>
    <h2>Dernière facture</h2>
    <?php if (!$invoice): ?>
        <p>Aucune facture récente à afficher.</p>
    <?php else: ?>
        <p>ID : <?php echo h($invoice['id_facture']); ?></p>
        <p>Date : <?php echo h($invoice['date']); ?> à <?php echo h($invoice['heure']); ?></p>
        <p>Caissier : <?php echo h($invoice['caissier']); ?></p>

        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th>Prix unitaire HT</th>
                    <th>Qté</th>
                    <th>Sous-total HT</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoice['articles'] as $item): ?>
                    <tr>
                        <td><?php echo h($item['nom']); ?></td>
                        <td><?php echo format_currency($item['prix_unitaire_ht']); ?></td>
                        <td><?php echo h($item['quantite']); ?></td>
                        <td><?php echo format_currency($item['sous_total_ht']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p>Total HT : <?php echo format_currency($invoice['total_ht']); ?></p>
        <p>TVA : <?php echo format_currency($invoice['tva']); ?></p>
        <p>Total TTC : <?php echo format_currency($invoice['total_ttc']); ?></p>
    <?php endif; ?>
</section>
<?php require_once BASE_PATH . '/includes/footer.php'; ?>
