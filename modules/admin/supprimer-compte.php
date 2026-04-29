<?php
require_once dirname(dirname(__DIR__)) . '/auth/session.php';
require_once BASE_PATH . '/includes/fonctions-auth.php';
require_roles('super_admin');

$identifier = get('identifiant');
if ($identifier === '') {
    $_SESSION['error_message'] = 'Identifiant manquant.';
    redirect('/facturation/modules/admin/gestion-comptes.php');
}

$user = find_user_by_identifier($identifier);
if (!$user) {
    $_SESSION['error_message'] = 'Utilisateur introuvable.';
    redirect('/facturation/modules/admin/gestion-comptes.php');
}

if ($user['role'] === 'super_admin') {
    $_SESSION['error_message'] = 'Impossible de supprimer un super administrateur.';
    redirect('/facturation/modules/admin/gestion-comptes.php');
}

delete_user($identifier);
$_SESSION['success_message'] = 'Compte supprimé avec succès.';
redirect('/facturation/modules/admin/gestion-comptes.php');
