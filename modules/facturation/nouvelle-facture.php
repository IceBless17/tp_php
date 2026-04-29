<?php
require_once dirname(dirname(__DIR__)) . '/auth/session.php';
require_once BASE_PATH . '/includes/fonctions-factures.php';
require_once BASE_PATH . '/includes/fonctions-auth.php';
require_roles(['caissier', 'manager', 'super_admin']);

$cart = get_cart();
$form = [
    'code_barre' => '',
    'quantite' => '1',
];

if (isset($_GET['remove'])) {
    remove_item_from_cart(get('remove'));
    $_SESSION['success_message'] = 'Article supprimé de la facture.';
    redirect('/facturation/modules/facturation/nouvelle-facture.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = post('action');
    if ($action === 'add') {
        $form['code_barre'] = post('code_barre');
        $form['quantite'] = post('quantite', '1');
        [$ok, $message] = add_product_to_cart($form['code_barre'], $form['quantite']);
        if ($ok) {
            $_SESSION['success_message'] = $message;
            redirect('/facturation/modules/facturation/nouvelle-facture.php');
        }
        $_SESSION['error_message'] = $message;
    }

    if ($action === 'save') {
        $user = current_user();
        [$ok, $message] = save_invoice($user['identifiant']);
        if ($ok) {
            $_SESSION['success_message'] = $message;
            redirect('/facturation/modules/facturation/afficher-facture.php');
        }
        $_SESSION['error_message'] = $message;
    }
}

$cart = get_cart();
$totals = calculate_cart_totals($cart);
require_once BASE_PATH . '/includes/header.php';
?>
<section class="billing-page">

    <div class="billing-hero">
        <div class="billing-hero-badge">
            <i class="fas fa-barcode"></i>
            <span>Espace de facturation</span>
        </div>
        <h2 class="billing-title">Nouvelle facture</h2>
        <p class="billing-subtitle">
            Zone scanner/caméra à styliser ensuite dans Lovable. Le backend attend simplement un code-barres.
        </p>
    </div>

    <div class="billing-form-card">
        <h3 class="section-title">Ajout d’un article</h3>

        <form method="post">
    <input type="hidden" name="action" value="add">

    <label>Code-barres</label>
    <input id="invoice-code-barre" type="text" name="code_barre" value="<?php echo h($form['code_barre']); ?>" required>

    <div class="scanner-actions">
        <button type="button" class="btn-gold" data-start-scanner data-target-input="invoice-code-barre" data-video-id="invoice-video" data-result-id="invoice-scan-result">
            <i class="fas fa-camera"></i>
            Activer la caméra
        </button>

        <button type="button" class="btn-gold" data-stop-scanner data-result-id="invoice-scan-result">
            <i class="fas fa-circle-stop"></i>
            Arrêter la caméra
        </button>
    </div>

    <div class="scanner-box" style="max-width:560px;margin:0 auto 18px;">
    <div
        id="invoice-video"
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
    <p id="invoice-scan-result" class="scanner-result">Scanner arrêté.</p>
</div>

    <button type="submit" class="btn-gold">
        <i class="fas fa-magnifying-glass"></i>
        Vérifier manuellement
    </button>

    <label>Quantité</label>
    <input type="number" name="quantite" min="1" value="<?php echo h($form['quantite']); ?>" required>

    <button type="submit" class="btn-gold">
        <i class="fas fa-cart-plus"></i>
        Ajouter à la facture
    </button>
</form>
    </div>

    <div class="billing-table-card">
        <h3 class="section-title">Articles de la facture</h3>

        <table>
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th>Prix unitaire HT</th>
                    <th>Qté</th>
                    <th>Sous-total HT</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): ?>
                    <tr>
                        <td><?php echo h($item['nom']); ?></td>
                        <td><?php echo format_currency($item['prix_unitaire_ht']); ?></td>
                        <td><?php echo h($item['quantite']); ?></td>
                        <td><?php echo format_currency($item['sous_total_ht']); ?></td>
                        <td>
                            <a class="table-action-link danger-link" href="?remove=<?php echo urlencode($item['code_barre']); ?>">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals-box">
            <p><strong>Total HT :</strong> <?php echo format_currency($totals['total_ht']); ?></p>
            <p><strong>TVA (18%) :</strong> <?php echo format_currency($totals['tva']); ?></p>
            <p><strong>Net à payer :</strong> <?php echo format_currency($totals['total_ttc']); ?></p>
        </div>

        <div class="validate-box">
            <form method="post">
                <input type="hidden" name="action" value="save">
                <button type="submit">
                    <i class="fas fa-check-circle"></i>
                    Valider et enregistrer la facture
                </button>
            </form>
        </div>
    </div>

</section>
<?php require_once BASE_PATH . '/includes/footer.php'; ?>
