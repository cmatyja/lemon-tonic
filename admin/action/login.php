<?php
require_once '../include/_autoload.php';
$response = User::verif_password($_POST['user_login'], $_POST['user_password']);

try {
    if (!$response) {
        throw  new Exception('Login et/ou mot de passe incorrect');
    }

    if (session_status() == PHP_SESSION_ACTIVE) {
        $_SESSION = [];
        session_destroy();
    }
    session_start();

    $role = User::is_admin($_POST['user_login']);
    if ($role) {
        $_SESSION['admin'] = true;
        //throw  new Exception ('Vous n\'avez pas les droits');
    }
    else {
        $_SESSION['admin'] = false;    
    }
    
    $array_result = [
        'connexion' => 'Connexion avec succès'
        , 'login' => true
    ];
    echo json_encode($array_result);
} catch (Exception $ex) {
    $array_result = [
        'connexion' => $ex->getMessage()
        , 'login' => false
    ];
    echo json_encode($array_result);
}