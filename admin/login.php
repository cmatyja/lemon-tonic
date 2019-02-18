<?php
require_once 'include/_autoload.php';
if (User::verif_admin()) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, shrink-to-fit=no"/>
    <title>Connexion au portail administrateur</title>
    <meta name="description" content=""/>
    <style>
        @import "../css/admin_global.css";
        @import "../css/admin_form_connexion.css";
    </style>
</head>
<body style="background : linear-gradient(rgba(74,74,74,.75), rgba(74,74,74, .75)), url('../admin/img/fond.png') fixed;">
<section class="form-connexion">
    <h1>Connexion au portail admin</h1>
    <form action="action/login.php" method="post" id="_form_login">
        <input type="text" name="user_email" placeholder="Email" required/>
        <input type="password" name="user_password" placeholder="Mot de passe" autocomplete="new-password"
               required/>
        <button id="_submit_login">LOGIN</button>
    </form>
</section>
<div id="_alert">
    <p></p>
</div>
<script>
    /* globals _submit_login, _form_login, _alert */

    _submit_login.addEventListener('click', e => {
        e.preventDefault();
        e.stopImmediatePropagation();
        let data = new FormData();
        data.append('user_email', document.getElementsByName('user_email')[0].value);
        data.append('user_password', document.getElementsByName('user_password')[0].value);
        let xhr = new XMLHttpRequest();
        xhr.addEventListener('loadend', () => {
            if (xhr.status === 200 && xhr.response) {
                let obj = JSON.parse(xhr.response);
                let paragraph = _alert.getElementsByTagName('p')[0];
                paragraph.innerText = obj.connexion;
                _alert.style.right = '20px';
                _alert.style.display = 'block';
                _alert.style.opacity = 1;

                let alert_color = obj.login ? 'success' : 'error';
                _alert.style.backgroundColor = 'var(--' + alert_color + ')';
                setTimeout(() => {
                    _alert.style.opacity = 0;
                    if (obj.login) {
                        window.location.href = 'index.php'
                    }
                }, 3000);
            }
        });
        xhr.open(_form_login.method, _form_login.action);
        xhr.send(data);
    });
</script>
</body>
</html>