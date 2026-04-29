<?php
require_once dirname(dirname(__DIR__)) . '/auth/session.php';
require_once BASE_PATH . '/includes/fonctions-produits.php';
require_roles(['manager', 'super_admin']);

$form = [
    'code_barre' => get('code_barre'),
    'nom' => '',
    'prix_unitaire_ht' => '',
    'date_expiration' => '',
    'quantite_stock' => '',
];
$errors = [];
$product = null;

if ($form['code_barre'] !== '') {
    $product = find_product_by_barcode($form['code_barre']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = [
        'code_barre' => post('code_barre'),
        'nom' => post('nom'),
        'prix_unitaire_ht' => post('prix_unitaire_ht'),
        'date_expiration' => post('date_expiration'),
        'quantite_stock' => post('quantite_stock'),
    ];

    $errors = validate_product_data($form);
    if (empty($errors)) {
        if (save_product($form)) {
            $_SESSION['success_message'] = 'Produit enregistré avec succès.';
            redirect('/facturation/modules/produits/liste.php');
        }
        $errors[] = 'Erreur lors de l’enregistrement du produit.';
    }
}


require_once BASE_PATH . '/includes/header.php';
?>
<section class="product-page">

    <div class="product-hero">
        <div class="product-hero-badge">
            <i class="fas fa-box-open"></i>
            <span>Espace produits</span>
        </div>

        <h2 class="product-title">Enregistrement d’un produit</h2>

        <p class="product-subtitle">
            Scannez un code-barres pour vérifier si le produit existe déjà, puis complétez
            les informations nécessaires pour l’enregistrer.
        </p>
    </div>

    <div class="product-check-card">
        <h3 class="section-title">Vérification du code-barres</h3>

       <form method="get" action="">
    <label for="product-code-barre">Code-barres scanné</label>
    <input
        id="product-code-barre"
        type="text"
        name="code_barre"
        value="<?php echo h($form['code_barre'] ?? ''); ?>"
        required
    >

    <div class="scanner-actions">
        <button
            type="button"
            class="btn-gold"
            data-start-scanner
            data-target-input="product-code-barre"
            data-video-id="product-video"
            data-result-id="product-scan-result">
            <i class="fas fa-camera"></i>
            Activer la caméra
        </button>

        <button
            type="button"
            class="btn-gold"
            data-stop-scanner
            data-result-id="product-scan-result">
            <i class="fas fa-circle-stop"></i>
            Arrêter la caméra
        </button>
    </div>

    <div class="scanner-box" style="max-width:560px;margin:0 auto 18px;">
    <div
        id="product-video"
        class="scanner-video"
        style="
            width:100%;
            max-width:520px;
            height:260px;
            min-height:260px;
            max-height:260px;
            margin:0 auto;
            background:#000;
            border-radius:14px;
            overflow:hidden;
            position:relative;
            border:1px solid rgba(212,175,55,0.22);
        "
    ></div>
    <p id="product-scan-result" class="scanner-result">Scanner arrêté.</p>
</div>
    <button type="submit" class="btn-gold">
        <i class="fas fa-magnifying-glass"></i>
        Vérifier manuellement
    </button>
</form>
    </div>

    <?php if ($product): ?>
        <div class="product-info-card">
            <h3 class="section-title">Produit déjà référencé</h3>

            <ul class="product-info-list">
                <li><strong>Nom :</strong> <?php echo h($product['nom']); ?></li>
                <li><strong>Prix HT :</strong> <?php echo format_currency($product['prix_unitaire_ht']); ?></li>
                <li><strong>Stock :</strong> <?php echo h($product['quantite_stock']); ?></li>
                <li><strong>Expiration :</strong> <?php echo h($product['date_expiration']); ?></li>
            </ul>
        </div>

    <?php elseif ($form['code_barre'] !== ''): ?>

        <?php if (!empty($errors)): ?>
            <div class="message error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo h($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="product-form-card">
            <h3 class="section-title">Nouveau produit</h3>

            <form method="post">
                <input type="hidden" name="code_barre" value="<?php echo h($form['code_barre']); ?>">

                <label>Nom du produit</label>
                <input type="text" name="nom" value="<?php echo h($form['nom']); ?>" required>

                <label>Prix unitaire HT (CDF)</label>
                <input type="number" step="0.01" name="prix_unitaire_ht" value="<?php echo h($form['prix_unitaire_ht']); ?>" required>

                <label>Date d’expiration</label>
                <input type="date" name="date_expiration" value="<?php echo h($form['date_expiration']); ?>" required>

                <label>Quantité initiale en stock</label>
                <input type="number" name="quantite_stock" value="<?php echo h($form['quantite_stock']); ?>" required>

                <button type="submit" class="btn-gold">
                    <i class="fas fa-floppy-disk"></i>
                    Enregistrer le produit
                </button>
            </form>
        </div>
    <?php endif; ?>

</section>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
