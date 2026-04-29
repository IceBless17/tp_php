<?php
require_once dirname(__DIR__) . '/auth/session.php';
require_once BASE_PATH . '/includes/fonctions-factures.php';
require_roles(['manager', 'super_admin']);

$month = get('mois', date('Y-m'));
$invoices = filter_invoices_by_month($month);
$total = 0;
foreach ($invoices as $invoice) {
    $total += (float) $invoice['total_ttc'];
}

require_once BASE_PATH . '/includes/header.php';
?>
<section>
    <h2>Rapport mensuel</h2>
    <form method="get">
        <label>Mois</label>
        <input type="month" name="mois" value="<?php echo h($month); ?>">
        <button type="submit">Afficher</button>
    </form>
    <p>Nombre de factures : <?php echo count($invoices); ?></p>
    <p>Total encaissé TTC : <?php echo format_currency($total); ?></p>
</section>
<?php require_once BASE_PATH . '/includes/footer.php'; ?>
