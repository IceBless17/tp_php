<?php
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/fonctions-auth.php';

$user = current_user();
$errorMessage = $_SESSION['error_message'] ?? '';
$successMessage = $_SESSION['success_message'] ?? '';
unset($_SESSION['error_message'], $_SESSION['success_message']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h(APP_NAME); ?></title>
    <link rel="stylesheet" href="/facturation/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@ericblade/quagga2/dist/quagga.js"></script>
    <script src="/facturation/assets/js/scanner.js?v=999"></script>
    
</head>
<body>
    
    <header class="site-header">
        <div class="container header-container">
            <div class="brand-block">
                <h1 class="site-title"><?php echo h(APP_NAME); ?></h1>
                <p class="site-subtitle">Gestion de facturation et de stock</p>
            </div>

            <nav class="main-nav">
                <a href="/facturation/index.php">Accueil</a>

                <?php if ($user): ?>
                    <a href="/facturation/modules/facturation/nouvelle-facture.php">Facturation</a>

                    <?php if (has_role(['manager', 'super_admin'])): ?>
                        <a href="/facturation/modules/produits/enregistrer.php">Produits</a>
                        <div class="nav-dropdown">
                            <a href="#">Rapports</a>
                            <div class="nav-dropdown-menu">
                                <a href="/facturation/rapports/rapport-journalier.php">Rapport journalier</a>
                                <a href="/facturation/rapports/rapport-mensuel.php">Rapport mensuel</a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (has_role('super_admin')): ?>
                        <a href="/facturation/modules/admin/gestion-comptes.php">Comptes</a>
                    <?php endif; ?>

                    <a href="/facturation/auth/logout.php" class="nav-danger">Déconnexion</a>
                <?php else: ?>
                    <a href="/facturation/auth/login.php">Connexion</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <?php if ($errorMessage !== ''): ?>
                <div class="message error"><?php echo h($errorMessage); ?></div>
            <?php endif; ?>

            <?php if ($successMessage !== ''): ?>
                <div class="message success"><?php echo h($successMessage); ?></div>
            <?php endif; ?>
