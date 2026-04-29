<?php
require_once __DIR__ . '/config/config.php';
require_once BASE_PATH . '/includes/fonctions-auth.php';
require_once BASE_PATH . '/includes/fonctions-produits.php';
require_once BASE_PATH . '/includes/fonctions-factures.php';

$productCount = count(get_all_products());
$invoiceCount = count(get_all_invoices());
$userCount = count(get_all_users());

require_once BASE_PATH . '/includes/header.php';
?>

<section class="dashboard">
    <?php if ($user = current_user()): ?>
        <div class="hero-card">
            <div>
                <h2>Bienvenue, <?php echo h($user['nom_complet']); ?></h2>
                <p class="hero-role">Rôle : <?php echo h($user['role']); ?></p>
                <p class="hero-text">
                    Vous êtes connecté au système de facturation. Utilisez le menu ci-dessus pour gérer
                    les produits, les factures, les rapports et les comptes selon vos autorisations.
                </p>
            </div>
        </div>

        <div class="stats-grid">
             <div class="stat-card">
                 <div class="stat-icon blue">
                     <i class="fas fa-box"></i>
                 </div>
                 <span class="stat-label">Produits enregistrés</span>
                 <strong class="stat-value"><?php echo $productCount; ?></strong>
             </div>

             <div class="stat-card">
                 <div class="stat-icon green">
                     <i class="fas fa-file-invoice"></i>
                 </div>
                 <span class="stat-label">Factures enregistrées</span>
                 <strong class="stat-value"><?php echo $invoiceCount; ?></strong>
             </div>

             <div class="stat-card">
                 <div class="stat-icon purple">
                      <i class="fas fa-users"></i>
                 </div>
                 <span class="stat-label">Utilisateurs</span>
                 <strong class="stat-value"><?php echo $userCount; ?></strong>
             </div>
        </div>

        <div class="dashboard-grid">
            <div class="panel-card">
                <h3 class="section-title">Accès rapide</h3>
                <div class="quick-links">
                    <a class="quick-link" href="/facturation/modules/facturation/nouvelle-facture.php">Nouvelle facture</a>

                    <?php if (has_role(['manager', 'super_admin'])): ?>
                        <a class="quick-link" href="/facturation/modules/produits/enregistrer.php">Enregistrer un produit</a>
                        <a class="quick-link" href="/facturation/rapports/rapport-journalier.php">Voir les rapports</a>
                    <?php endif; ?>

                    <?php if (has_role('super_admin')): ?>
                        <a class="quick-link" href="/facturation/modules/admin/gestion-comptes.php">Gérer les comptes</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="panel-card">
                <h3 class="section-title">Résumé du système</h3>
                <ul class="summary-list">
                    <li>Application développée en PHP procédural</li>
                    <li>Persistance des données via fichiers JSON</li>
                    <li>Gestion des rôles : caissier, manager, super administrateur</li>
                    <li>Suivi des produits, factures et utilisateurs</li>
                </ul>
            </div>
        </div>

    <?php else: ?>
         <div class="hero-card public-hero">
             <div class="public-hero-badge">
                 <i class="fas fa-shield-halved"></i>
                 <span>Espace sécurisé</span>
             </div>

             <div>
                 <h2 class="public-hero-title">Bienvenue sur le système de facturation</h2>

                 <p class="hero-text public-hero-text">
                    Connectez-vous pour accéder aux fonctionnalités de gestion des produits,
                    de facturation, de rapports et d’administration.
                 </p>

                 <div class="public-hero-actions">
                     <a class="primary-btn" href="/facturation/auth/login.php">
                         <i class="fas fa-right-to-bracket"></i>
                         Se connecter
                      </a>
                 </div>
             </div>
         </div>
     <?php endif; ?>
</section>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
