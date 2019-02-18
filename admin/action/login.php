<?php
require_once '../include/_autoload.php';
$response = User::verif_password($_POST['user_email'], $_POST['user_password']);

try {
    if (!$response) {
        throw  new Exception('Login et/ou mot de passe incorrect');
    }
    User::logout();
    session_start();

    $role = User::is_admin($_POST['user_email']);
    if ($role) {
        $_SESSION['admin'] = true;
        $array_result = [
            'connexion' => 'Connexion avec succès'
            , 'login' => true
        ];
        echo json_encode($array_result);
    } else {
        throw  new Exception ('Vous n\'avez pas les droits');
    }
} catch (Exception $ex) {
    $_SESSION['admin'] = false;
    $array_result = [
        'connexion' => $ex->getMessage()
        , 'login' => false
    ];
    echo json_encode($array_result);
}