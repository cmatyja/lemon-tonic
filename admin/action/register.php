<?php

if (!empty($_GET['type'])) {
    require_once '../include/_autoload.php';
    if (!empty($_POST['user_id']) && is_numeric($_POST['user_id'])) {
        $_GET['type'] = 'update';
    }
    $array_result = [];
    function verification_post($update = false)
    {
        $msg_error = '';
        $required_fields = [
            'lastname' => 'Nom'
            , 'firstname' => 'Prénom'
            , 'birthday' => 'Date de naissance'
            , 'email' => 'Email'
            , 'work' => 'Travail'
            , 'country' => 'Pays'
            , 'sex' => 'Sexe'
            , 'password' => 'Mot de passe'
        ];
        $error = [];
        /* on ckeck si aucun champs requis n'est vide ; /!\ car le empty(0) == false */
        foreach ($required_fields as $field => $fieldFR) {
            if (!($field === 'password' && $update)) {
                if (trim($_POST[$field]) == '') {
                    array_push($error, $fieldFR);
                }
            }
        }

        if (!empty($error)) {
            $msg_error .= "Les champs suivant n'ont pas été complétés :" . PHP_EOL;
            foreach ($error as $field) {
                $msg_error .= ' - ' . $field . PHP_EOL;
            }
        }

        if (!empty($_POST['password'])) { // mot de passe vide possible en cas de mise à jour de profil par l'admin
            if ($_POST['password'] !== $_POST['password_confirm']) {
                $msg_error .= "Les mots de passe ne correspondent pas" . PHP_EOL;
            }
        }

        if (!empty($_POST['birthday']) && !User::birthday_valid($_POST['birthday'])) {
            $msg_error .= "La date d'anniversaire n'est pas valide" . PHP_EOL;
        }

        if (!User::email_valid($_POST['email'])) {
            $msg_error .= "L'adresse mail saisie n'est pas valide" . PHP_EOL;
        }

        if (!$update) {
            if (!empty($_POST['email']) && User::checkByEmail($_POST['email'])) {
                $msg_error .= "L'adresse mail '{$_POST['email']}'' a déjà été utilisée" . PHP_EOL;
            }
        }

        /* on verifie que l'identifiant pour le sex est bien soit 0 ou 1 */
        if (intval($_POST['sex']) !== 0 && intval($_POST['sex']) !== 1) {
            echo intval($_POST['sex']);
            $msg_error .= "La valeur pour la civilité (homme ou femme) est incorrecte, merci de la resaissir" . PHP_EOL;
        }
        return $msg_error;
    }

    switch ($_GET['type']) {
        case 'create':
            $msg_error = verification_post();
            if ($msg_error !== '') {
                $array_result = ['create' => $msg_error];
            } else {
                $user = new User();
                $user->setLastname($_POST['lastname']);
                $user->setFirstname($_POST['firstname']);
                $user->setBirthday($_POST['birthday']);
                $user->setEmail($_POST['email']);
                $user->setSex($_POST['sex']);
                $user->setJobId($_POST['work']);
                $user->setCountryId($_POST['country']);
                $user->setRegionId((empty($_POST['region']) ? 0 : $_POST['region']));
                $user->setRole(0);
                $user->setPassword($_POST['password']);
                $array_result = ['create' => $user->create()];
                if ($array_result['create']) {
                    $array_result['user_id'] = $user->getUserId();
                    $list_user_detail = '
                        <ul>
                            <li>Nom et Prénom : ' . $_POST['lastname'] . ' ' . $_POST['firstname'] . '</li>
                            <li>Date de naissance : . ' . date('d/m/Y', strtotime($_POST['firstname'])) . '</li>
                            <li>Email : ' . $_POST['email'] . '</li>
                        </ul>';
                    Email::HTML_to_user($_POST['email'], 'Bienvenue sur Lemon-Tonic',
                        'Bonjour et bienvenue sur notre site. Voici un récapitulatif de votre inscription :<br/>
                           ' . $list_user_detail);
                    Email::HTML_to_webmaster(
                        'Bonjour. Voici un récapitulatif de l\'inscription du nouvel utilisateur :<br/>
                           ' . $list_user_detail);
                    $array_result['create'] = true;
                }
            }
            break;
        case 'update':
            User::is_granted();
            $ar_update = [
                'user_id' => $_POST['user_id']
                , 'lastname' => $_POST['lastname']
                , 'firstname' => $_POST['firstname']
                , 'birthday' => $_POST['birthday']
                , 'email' => $_POST['email']
                , 'sex' => $_POST['sex']
                , 'work' => $_POST['work']
                , 'country' => $_POST['country']
                , 'region' => (empty($_POST['region']) ? 0 : $_POST['region'])
            ];

            if (!empty($_POST['password'])) {
                $ar_update += ['password' => $_POST['password']];
            }

            $user = new User();
            $array_result = ['update' => $user->update($ar_update)];
            break;

        case 'delete':
            User::is_granted();
            if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
                $user = new User();
                $user->setUserId($_GET['id']);
                $bool_result = $user->delete($_GET['key']);
                $array_result = ['delete' => $bool_result];
            } else {
                $array_result = ['delete' => false];
            }
            break;
    }
    echo json_encode($array_result);
}