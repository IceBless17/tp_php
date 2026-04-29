<?php
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/fonctions-produits.php';

function get_all_users()
{
    return read_json_file(USERS_FILE);
}

function find_user_by_identifier($identifier)
{
    $users = get_all_users();
    foreach ($users as $user) {
        if (isset($user['identifiant']) && $user['identifiant'] === $identifier) {
            return $user;
        }
    }
    return null;
}

function user_exists($identifier)
{
    return find_user_by_identifier($identifier) !== null;
}

function validate_login($identifier, $password)
{
    $user = find_user_by_identifier($identifier);
    if (!$user) {
        return [false, 'Identifiants invalides.'];
    }
    if (empty($user['actif'])) {
        return [false, 'Ce compte est désactivé.'];
    }
    if (!password_verify($password, $user['mot_de_passe'])) {
        return [false, 'Identifiants invalides.'];
    }

    return [true, $user];
}

function login_user($user)
{
    $_SESSION['user'] = [
        'identifiant' => $user['identifiant'],
        'nom_complet' => $user['nom_complet'],
        'role' => $user['role'],
    ];
}

function logout_user()
{
    $_SESSION = [];
    session_destroy();
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in()
{
    return current_user() !== null;
}

function has_role($roles)
{
    $user = current_user();
    if (!$user) {
        return false;
    }
    if (!is_array($roles)) {
        $roles = [$roles];
    }
    return in_array($user['role'], $roles, true);
}

function require_roles($roles)
{
    if (!is_logged_in()) {
        $_SESSION['error_message'] = 'Veuillez vous connecter.';
        redirect('/facturation/auth/login.php');
    }

    if (!has_role($roles)) {
        $_SESSION['error_message'] = 'Accès non autorisé.';
        redirect('/facturation/index.php');
    }
}

function validate_new_user_data($data)
{
    $errors = [];
    $allowedRoles = ['caissier', 'manager'];

    if ($data['identifiant'] === '') {
        $errors[] = 'L’identifiant est obligatoire.';
    }
    if ($data['nom_complet'] === '') {
        $errors[] = 'Le nom complet est obligatoire.';
    }
    if ($data['mot_de_passe'] === '' || strlen($data['mot_de_passe']) < 6) {
        $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
    }
    if (!in_array($data['role'], $allowedRoles, true)) {
        $errors[] = 'Rôle invalide.';
    }
    if (user_exists($data['identifiant'])) {
        $errors[] = 'Cet identifiant existe déjà.';
    }

    return $errors;
}

function save_user($data)
{
    $users = get_all_users();
    $users[] = [
        'identifiant' => $data['identifiant'],
        'mot_de_passe' => password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
        'role' => $data['role'],
        'nom_complet' => $data['nom_complet'],
        'date_creation' => today_date(),
        'actif' => true,
    ];

    return write_json_file(USERS_FILE, $users);
}

function delete_user($identifier)
{
    $users = get_all_users();
    $filtered = [];

    foreach ($users as $user) {
        if ($user['identifiant'] !== $identifier) {
            $filtered[] = $user;
        }
    }

    return write_json_file(USERS_FILE, $filtered);
}
