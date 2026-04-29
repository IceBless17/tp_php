<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once BASE_PATH . '/includes/fonctions-auth.php';
logout_user();
session_start();
$_SESSION['success_message'] = 'Déconnexion réussie.';
redirect('/facturation/auth/login.php');
