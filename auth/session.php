<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once BASE_PATH . '/includes/fonctions-auth.php';

if (!is_logged_in()) {
    $_SESSION['error_message'] = 'Veuillez vous connecter.';
    redirect('/facturation/auth/login.php');
}
