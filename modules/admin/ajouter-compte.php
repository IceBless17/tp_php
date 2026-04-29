<?php
require_once dirname(dirname(__DIR__)) . '/auth/session.php';
require_once BASE_PATH . '/includes/fonctions-auth.php';
require_roles('super_admin');

$form = [
    'identifiant' => '',
    'nom_complet' => '',
    'mot_de_passe' => '',
    'role' => 'caissier',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = [
        'identifiant' => post('identifiant'),
        'nom_complet' => post('nom_complet'),
        'mot_de_passe' => post('mot_de_passe'),
        'role' => post('role'),
    ];
    $errors = validate_new_user_data($form);
    if (empty($errors)) {
        if (save_user($form)) {
            $_SESSION['success_message'] = 'Compte créé avec succès.';
            redirect('/facturation/modules/admin/gestion-comptes.php');
        }
        $errors[] = 'Erreur lors de la création du compte.';
    }
}

require_once BASE_PATH . '/includes/header.php';
?>
<section class="add-account-page">

    <div class="add-account-hero">
        <div class="add-account-hero-badge">
            <i class="fas fa-user-plus"></i>
            <span>Nouveau compte</span>
        </div>

        <h2 class="add-account-title">Ajouter un compte</h2>

        <p class="add-account-subtitle">
            Créez un nouvel utilisateur avec ses informations et son rôle dans le système.
        </p>
    </div>

    <div class="add-account-form-card">

        <?php if (!empty($errors)): ?>
            <div class="message error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo h($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <label>Identifiant</label>
            <input type="text" name="identifiant" value="<?php echo h($form['identifiant']); ?>" required>

            <label>Nom complet</label>
            <input type="text" name="nom_complet" value="<?php echo h($form['nom_complet']); ?>" required>

            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" required>

            <label>Rôle</label>
            <select name="role">
                <option value="caissier" <?php echo $form['role'] == 'caissier' ? 'selected' : ''; ?>>Caissier</option>
                <option value="manager" <?php echo $form['role'] == 'manager' ? 'selected' : ''; ?>>Manager</option>
            </select>

            <button type="submit" class="btn-gold">
                <i class="fas fa-user-check"></i>
                Créer le compte
            </button>

        </form>

    </div>

</section>
<?php require_once BASE_PATH . '/includes/footer.php'; ?>
