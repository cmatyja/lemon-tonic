<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, shrink-to-fit=no"/>
    <title>Lemon Tonic - S'inscrire sur le site</title>
    <meta name="description" content="Lemon-Tonic, donnez du tonic à votre quotidien"/>
    <style>
        @import "css/global.css";
        @import "css/form.css";
    </style>
</head>
<body class="nav_closed">
<?php require_once 'header.html'; ?>
<?php require_once 'nav.html'; ?>
<main>
    <h3>Inscription sur le site</h3>
    <section class="form">
        <form action="admin/action/register.php" method="post" id="_form_register"
              enctype="multipart/form-data">
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
                    <?php
                    require_once 'admin/include/_autoload.php';
                    $jobs = Job::all();
                    foreach ($jobs as $job): ?>
                        <option value="<?= $job['job_id'] ?>">
                            <?= $job['name'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </label>
            <label for="_country">
                Pays :
                <select name="country" id="_country" required>
                    <?php
                    $countries = Country::all();
                    $location = serialize(json_decode(file_get_contents('http://www.geoplugin.net/json.gp')));
                    foreach ($countries as $country): ?>
                        <option
                            <?= ($country['name_FR'] === unserialize($location)->geoplugin_countryName ? 'selected' : '') ?>
                                value="<?= $country['country_id'] ?>">
                            <?= $country['name_FR'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </label>
            <label>
                Région :
                <select name="region" id="_region" required>
                    <?php
                    $regions = Region::all();
                    foreach ($regions as $region): ?>
                        <option
                            <?= ($region['name'] === unserialize($location)->geoplugin_region ? 'selected' : '') ?>
                                value="<?= $region['region_id'] ?>">
                            <?= $region['name'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </label>
            <fieldset>
                <span>Vous êtes :</span>
                <label for="_sexe1">
                    un homme
                    <input type="radio" value="0" name="sex" id="_sexe1">
                </label>
                <label for="_sexe2">
                    une femme
                    <input type="radio" value="1" name="sex" id="_sexe2">
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
<?php require_once 'footer.php'; ?>
<div id="_alert">
    <p></p>
</div>
<script src="js/register.js"></script>
<script>
    /* globals _submit_register, _form_register */
    _submit_register.addEventListener('click', e => {
        e.preventDefault();
        e.stopImmediatePropagation();
        traitement_add_user(_form_register);
    });
</script>
</body>
</html>