<?php
require_once dirname(dirname(__DIR__)) . '/auth/session.php';
require_once BASE_PATH . '/includes/fonctions-auth.php';
require_roles('super_admin');
$users = get_all_users();
require_once BASE_PATH . '/includes/header.php';
?>
<section class="accounts-page">

    <div class="accounts-hero">
        <div class="accounts-hero-badge">
            <i class="fas fa-users-gear"></i>
            <span>Espace comptes</span>
        </div>

        <h2 class="accounts-title">Gestion des comptes</h2>

        <p class="accounts-subtitle">
            Consultez les utilisateurs du système, leurs rôles, leur statut et les actions disponibles.
        </p>
    </div>

    <div class="accounts-table-card">
        <div class="accounts-header-row">
            <h3 class="section-title">Liste des utilisateurs</h3>
            <a href="/facturation/modules/admin/ajouter-compte.php" class="btn-gold btn-inline">
                <i class="fas fa-user-plus"></i>
                Ajouter un compte
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Identifiant</th>
                    <th>Nom complet</th>
                    <th>Rôle</th>
                    <th>Actif</th>
                    <th>Date création</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $account): ?>
                    <tr>
                        <td><?php echo h($account['identifiant']); ?></td>
                        <td><?php echo h($account['nom_complet']); ?></td>
                        <td><?php echo h($account['role']); ?></td>
                        <td><?php echo !empty($account['actif']) ? 'Oui' : 'Non'; ?></td>
                        <td><?php echo h($account['date_creation']); ?></td>
                        <td>
                            <?php if ($account['role'] !== 'super_admin'): ?>
                                <a class="table-action-link danger-link" href="/facturation/modules/admin/supprimer-compte.php?identifiant=<?php echo urlencode($account['identifiant']); ?>">
                                    Supprimer
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</section>
<?php require_once BASE_PATH . '/includes/footer.php'; ?>
