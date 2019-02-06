<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, shrink-to-fit=no"/>
    <title>Connexion</title>
    <meta name="description" content="Projet test pour Lemon-Interactive"/>
    <style>
        @import "css/global.css";
        @import "css/form.css";
    </style>
</head>
<body>
<?php require_once 'header.html'; ?>
<?php require_once 'nav.php'; ?>
<main>
    <h1>Inscription sur le site</h1>
    <section class="form">
        <form action="admin/action/register.php" method="post" id="_form_register">
            <label for="_lastname">
                Nom :
                <input type="text" name="lastname" id="_lastname" required/>
            </label>
            <label for="_firstname">
                Prénom :
                <input type="text" name="firstname" id="_firstname" required/>
            </label>
            <label for="_birthday">
                Date de naissance :
                <input type="text" onfocus="(this.type='date')" name="birthday" id="_birthday" required/>
            </label>
            <label for="_email">
                Adresse mail :
                <input type="email" name="email" id="_email" required/>
            </label>
            <label for="_work">
                Métier :
                <select name="work" id="_work" required>
                    <option value=""></option>
                    <option value=""></option>
                    <option value=""></option>
                    <option value=""></option>
                    <option value=""></option>
                </select>
            </label>
            <label>
                Pays :
                <select name="country" id="_country" required>
                    <?php
                    require_once 'admin/include/_autoload.php';
                    $countries = Country::AllCountries();
                    $location = serialize(json_decode(file_get_contents('http://www.geoplugin.net/json.gp')));
                    foreach ($countries as $row): ?>
                        <option
                            <?= ($row['name_FR'] === unserialize($location)->geoplugin_countryName ? 'selected' : '') ?>
                            value="<?= $row['id'] ?>">
                            <?= $row['name_FR'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </label>
            <fieldset>
                <span>Vous êtes :</span>
                <label for="_sexe1">
                    un homme
                    <input type="radio" value="H" name="sex" id="_sexe1">
                </label>
                <label for="_sexe2">
                    une femme
                    <input type="radio" value="F" name="sex" id="_sexe2">
                </label>
            </fieldset>
            <label for="_password">
                Mot de passe :
                <input type="password" name="password" id="_password" required/>
            </label>
            <label for="_password_confirm">
                Répéter mot de passe :
                <input type="password" name="password_confirm" id="_password_confirm" required/>
            </label>
            <button id="_submit_register">S'inscrire</button>
        </form>
    </section>
</main>
</body>
</html>