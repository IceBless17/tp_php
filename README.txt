PROJET FACTURATION PHP PROCEDURAL

1. Placer le dossier facturation dans le dossier htdocs de XAMPP ou dans le dossier www de MAMP.
2. Démarrer Apache.
3. Ouvrir dans le navigateur :
   http://localhost/facturation/

COMPTE INITIAL
Identifiant : super.admin
Mot de passe : admin123

IMPORTANT
- Les données sont stockées uniquement dans les fichiers JSON du dossier data/.
- Le design actuel est minimal.
- Les blocs visuels pourront être remplacés par Lovable sans toucher aux traitements PHP.

OU PLACER LE CODE LOVABLE
1. CSS global : assets/css/style.css
2. JS scanner/caméra/interface : assets/js/scanner.js
3. Mise en page commune : includes/header.php et includes/footer.php
4. Pages à embellir :
   - auth/login.php
   - index.php
   - modules/produits/enregistrer.php
   - modules/produits/liste.php
   - modules/facturation/nouvelle-facture.php
   - modules/facturation/afficher-facture.php
   - modules/admin/gestion-comptes.php
   - modules/admin/ajouter-compte.php
   - rapports/rapport-journalier.php
   - rapports/rapport-mensuel.php

NE PAS CASSER CES ELEMENTS
- Les attributs name des champs de formulaire
- Les method="post" ou method="get"
- Les chemins PHP des formulaires et liens
- Les variables PHP et les boucles foreach
- Les redirections et includes PHP

PROMPT DE BASE POUR LOVABLE
Créer une interface moderne, propre, responsive et élégante pour un système de facturation de supermarché en PHP. Ne modifie pas la logique PHP. Garde tous les champs, leurs attributs name, les méthodes de formulaire et les liens existants. Tu peux uniquement améliorer la structure HTML visuelle, ajouter des classes CSS, proposer un style professionnel, une barre de navigation, des cartes statistiques, des tableaux modernes, des formulaires propres, une zone scanner caméra moderne et un thème cohérent. Les fichiers concernés sont header.php, footer.php, login.php, index.php, enregistrer.php, liste.php, nouvelle-facture.php, afficher-facture.php, gestion-comptes.php, ajouter-compte.php, rapport-journalier.php et rapport-mensuel.php. Le JavaScript visuel du scanner doit aller dans assets/js/scanner.js et le style global dans assets/css/style.css.
