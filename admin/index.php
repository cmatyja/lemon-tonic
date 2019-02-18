<?php
require_once 'include/_autoload.php';
User::is_granted();
?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,shrink-to-fit=no">
    <title>Lemon Tonic - Portail administrateur</title>
    <style>
        @import "../css/admin_global.css";
        @import "../css/admin_form_edit.css";
        @import "../css/admin_table.css";
    </style>
</head>
<body>

<header>
    <h1>Portail administrateur</h1>
</header>

<nav>
    <a href="../index.php" target="_blank">Accueil du site</a>
    <a href="action/logout.php">Déconnexion</a>
</nav>
<main>
    <form id="_form_user" action="action/register.php" method="post">
        <h2>Ajout d'un utilisateur</h2>
        <input type="text" name="lastname" id="_lastname" placeholder="Nom" required/>
        <input type="text" name="firstname" id="_firstname" placeholder="Prénom" required/>
        <input type="date" name="birthday" id="_birthday" placeholder="Date d'anniversaire"
               required/>
        <input type="email" name="email" id="_email" placeholder="Adresse email" required/>
        <select name="work" id="_work" placeholder="Travail" required>
            <option value=""></option>
            <?php
            require_once 'include/_autoload.php';
            $jobs = Job::all();
            foreach ($jobs as $job): ?>
                <option value="<?= $job['job_id'] ?>">
                    <?= $job['name'] ?>
                </option>
            <?php endforeach ?>
        </select>
        <select name="country" id="_country" placeholder="Pays" required>
            <?php
            $countries = Country::all();
            foreach ($countries as $country): ?>
                <option value="<?= $country['country_id'] ?>">
                    <?= $country['name_FR'] ?>
                </option>
            <?php endforeach ?>
        </select>
        <select name="region" id="_region" placeholder="Région" required>
            <option value="0"></option>
            <?php
            $regions = Region::all();
            foreach ($regions as $region): ?>
                <option value="<?= $region['region_id'] ?>">
                    <?= $region['name'] ?>
                </option>
            <?php endforeach ?>
        </select>
        <fieldset>
            <label for="_sexe1">
                un homme
                <input type="radio" value="0" name="sex" id="_sexe1">
            </label>
            <label for="_sexe2">
                une femme
                <input type="radio" value="1" name="sex" id="_sexe2">
            </label>
        </fieldset>
        <input type="password" name="password" id="_password" placeholder="Mot de passe" required/>
        <input type="password" name="password_confirm" id="_password_confirm" placeholder="Répétez mot de passe"
               required/>
        <input type="hidden" name="user_id"/>
        <button type="submit" id="_submit_user">Ajouter utilisateur</button>
    </form>

    <h2>Liste des utilisateurs</h2>

    <section class="table">
        <article id="titles">
            <h4>ID</h4>
            <h4>Nom</h4>
            <h4>Prénom</h4>
            <h4>Date d'anniversaire</h4>
            <h4>Email</h4>
            <h4>Sexe</h4>
            <h4>CSP</h4>
            <h4>Pays</h4>
            <h4>Région</h4>
            <h4>Role</h4>
            <h4>Gestion</h4>
        </article>
        <?php
        require_once 'include/_autoload.php';
        $users = User::all();
        $regions = Region::get_name_by_id();
        $countries = Country::get_name_by_id();
        $jobs = Job::get_name_by_id();

        foreach ($users as $user): ?>
            <article data-id="<?= $user->getUserId(); ?>" data-key="<?= md5($user->getCreatedAt()); ?>">
                <h3 data-name="ID"><?= $user->getUserId(); ?></h3>
                <strong data-name="Nom"><?= $user->getLastname(); ?></strong>
                <strong data-name="Prénom"><?= $user->getFirstname(); ?></strong>
                <span data-name="Date anniversaire" data-birthday="<?= $user->getBirthday(); ?>">
                <?= date('d/m/Y', strtotime($user->getBirthday())); ?>
            </span>
                <em data-name="Email"><?= $user->getEmail(); ?></em>
                <span data-name="Sexe" data-sex="<?= $user->getSex(); ?>"><?php
                    if ($user->getSex() == 0) {
                        echo 'Homme';
                    } elseif ($user->getSex() == 1) {
                        echo 'Femme';
                    } else {
                        echo 'Erreur';
                    }
                    ?></span>
                <span data-name="CSP" data-job="<?= $user->getJobId() ?>"><?= $jobs[$user->getJobId()]; ?></span>
                <span data-name="Pays"
                      data-country="<?= $user->getCountryId() ?>"><?= $countries[$user->getCountryId()]; ?></span>
                <span data-name="Région" data-region="<?= $user->getRegionId() ?>">
                <?= $user->getRegionId() != 0 ? $regions[$user->getRegionId()] : '-'; ?>
            </span>
                <span data-name="Role" data-role="<?= $user->getRole(); ?>"><?php
                    if ($user->getRole() == 0) {
                        echo 'Utilisateur';
                    } elseif ($user->getRole() == 1) {
                        echo 'Administrateur';
                    } else {
                        echo 'Non répertorié';
                    }
                    ?></span>
                <p data-name="Gestion">
                    <button data-type="update">Modifier</button>
                    <button data-type="delete">Supprimer</button>
                </p>
            </article>
        <?php endforeach; ?>
    </section>
</main>
<div id="_alert">
    <p></p>
</div>
<script src="../js/register.js"></script>
<script>
    /* globals _form_user, _submit_user */

    _submit_user.addEventListener('click', e => {
        e.preventDefault();
        e.stopImmediatePropagation();
        traitement_add_user(_form_user);
    });

    document.body.addEventListener('click', e => {
        if (e.target.matches('section.table>article button[data-type="update"]')) {
            let user = e.target.parentNode.parentNode;
            document.getElementsByName('lastname')[0].value = user.getElementsByTagName('strong')[0].innerText;
            document.getElementsByName('firstname')[0].value = user.getElementsByTagName('strong')[1].innerText;
            let field_birthday = new Date(user.getElementsByTagName('span')[0].dataset.birthday);
            field_birthday.setDate(field_birthday.getDate() + 1);
            document.getElementsByName('birthday')[0].valueAsDate = field_birthday;
            document.getElementsByName('email')[0].value = user.getElementsByTagName('em')[0].innerText;
            document.getElementsByName('work')[0].value = user.getElementsByTagName('span')[2].dataset.job;
            document.getElementsByName('country')[0].value = user.getElementsByTagName('span')[3].dataset.country;
            document.getElementsByName('region')[0].value =
                (user.getElementsByTagName('span')[4].dataset.region === '-'
                    ? 0
                    : user.getElementsByTagName('span')[4].dataset.region);
            document.getElementsByName('sex')[user.getElementsByTagName('span')[1].dataset.sex].checked = true;
            document.getElementsByName('password')[0].value = '';
            document.getElementsByName('password_confirm')[0].value = '';
            document.getElementsByName('user_id')[0].value = user.dataset.id;
            _submit_user.innerText = 'Modifier l`utilisateur';
        } else if (e.target.matches('section.table>article button[data-type="delete"]')) {
            if (confirm('Etes-vous sûr de supprimer l\'utilisateur ayant l\'ID ' + e.target.parentNode.parentNode.dataset.id + ' ?')) {
                let xhr = new XMLHttpRequest();
                xhr.addEventListener('loadend', function () {
                    if (xhr.status === 200
                        && this.response) {
                        let obj = JSON.parse(this.response);
                        if (obj.delete) {
                            let row = e.target.parentNode.parentNode;
                            row.parentNode.removeChild(row);
                        }
                    }
                });
                xhr.open('GET', _form_user.action + '?type=delete&id=' + e.target.parentNode.parentNode.dataset.id + '&key=' + e.target.parentNode.parentNode.dataset.key);
                xhr.send();
            }
        }
    });
</script>
</body>
</html>