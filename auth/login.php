<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once BASE_PATH . '/includes/fonctions-auth.php';

if (is_logged_in()) {
    redirect('/facturation/index.php');
}

$identifier = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = post('identifiant');
    $password = post('mot_de_passe');
    [$ok, $result] = validate_login($identifier, $password);
    if ($ok) {
        login_user($result);
        $_SESSION['success_message'] = 'Connexion réussie.';
        redirect('/facturation/index.php');
    }
    $_SESSION['error_message'] = $result;
}

require_once BASE_PATH . '/includes/header.php';
?>
<section class="login-page">
    <div class="login-box">
        <h2 class="login-title">Connexion</h2>
        <p class="login-subtitle">Accédez à votre espace de gestion</p>

        <form method="post">
            <label>Identifiant</label>
            <input type="text" name="identifiant" value="<?php echo h($identifier); ?>" required>

            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" required>

            <button type="submit">Se connecter</button>
        </form>

        <p class="login-help">
            Super admin initial à créer manuellement dans <code>data/utilisateurs.json</code>.
        </p>
    </div>
</section>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
