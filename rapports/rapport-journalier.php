<?php
require_once dirname(__DIR__) . '/auth/session.php';
require_once BASE_PATH . '/includes/fonctions-factures.php';
require_roles(['manager', 'super_admin']);

$date = get('date', today_date());
$invoices = filter_invoices_by_date($date);
$total = 0;
foreach ($invoices as $invoice) {
    $total += (float) $invoice['total_ttc'];
}

require_once BASE_PATH . '/includes/header.php';
?>
<section class="report-page">

    <div class="report-hero">
        <div class="report-hero-badge">
            <i class="fas fa-chart-line"></i>
            <span>Espace rapports</span>
        </div>

        <h2 class="report-title">Rapport journalier</h2>

        <p class="report-subtitle">
            Consultez les performances quotidiennes de facturation et le total encaissé sur une date donnée.
        </p>
    </div>

    <div class="report-form-card">
        <h3 class="section-title">Recherche du rapport</h3>

        <form method="get">
            <label>Date</label>
            <input type="date" name="date" value="<?php echo h($date); ?>" required>

            <button type="submit" class="btn-gold">
                <i class="fas fa-calendar-day"></i>
                Afficher
            </button>
        </form>
    </div>

    <div class="report-result-card">
        <h3 class="section-title">Résumé du jour</h3>

        <div class="report-stats">
            <div class="report-stat-box">
                <span class="report-stat-label">Nombre de factures</span>
                <strong class="report-stat-value"><?php echo count($invoices); ?></strong>
            </div>

            <div class="report-stat-box">
                <span class="report-stat-label">Total encaissé TTC</span>
                <strong class="report-stat-value"><?php echo format_currency($total); ?></strong>
            </div>
        </div>
    </div>

</section>
<?php require_once BASE_PATH . '/includes/footer.php'; ?>
