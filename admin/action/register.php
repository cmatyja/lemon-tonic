<?php
if (!empty($_GET['type'])) {

    require_once '../class/User.php';

    switch ($_GET['type']) {
        case 'create':
            try {
                $required_fields = array('lastname', 'firstname', 'birthday', 'email', 'work', 'country', 'sex', 'password');
                $error = [];
                foreach($required_fields as $field) {
                    if (empty($_POST[$field])) {
                        array_push($error, $field);
                    }
                }

                if(!empty($error)) {
                    throw new Exception('Les mots de passes ne correspondent pas');
                }

                if ($_POST['password'] !== $_POST['password_confirm']) {
                    throw new Exception('Les mots de passes ne correspondent pas');
                }

            } catch (Exception $ex) {

            }
    }

    $user = new User();

    if (!empty($_POST['user_id']) && is_numeric($_POST['user_id'])) {
        $_GET['type'] = 'update';
    }

    switch ($_GET['type']) {
        case 'create':
            if ($_POST['password'] !== $_POST['password_confirm']) {
                $user->firstname = $_POST['firstname'];
                $user->lastname = $_POST['lastname'];
                $user->email = $_POST['email'];
                $user->password = $_POST['password'];
                // ci dessous mieux vaut utiliser un isset plutôt que $_POST['valid']==='on' pour éviter les erreurs, car si la case n'est pas cochée, rien n'est renvoyée
                $user->valid = (isset($_POST['valid']) ? 1 : 0); // condition en ternaire :-)
                $array_result = ['create' => $user->create()];
                // si la création est ok : on ajoute l'ID dans le tableau Result
                if ($array_result['create']) {
                    $array_result['user_id'] = $user->user_id;
                }
            } else {
                $array_result = ['create' => false];
            }
            break;

        case 'update':
            $ar_update = [
                'user_id' => $_POST['user_id'],
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'email' => $_POST['email']
            ];

            !empty($_POST['valid']) ? $ar_update['valid'] = 1 : $ar_update['valid'] = 0;

            if (!empty($_POST['password'])) {
                $ar_update['password'] = $_POST['password'];
            }

            $array_result = ['update' => $user->update($ar_update)];
            break;

        case 'delete':
            if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
                $user->user_id = $_GET['id'];
                $bool_result = $user->delete();
                $array_result = ['delete' => $bool_result];
            } else {
                $array_result = ['delete' => false];
            }
            break;
    }
    echo json_encode($array_result);
}