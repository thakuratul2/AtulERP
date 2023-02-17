<?php 
if ($f == 'confirm_user') {
    if (!empty($_POST['confirm_code']) && !empty($_POST['user_id']) && is_numeric($_POST['user_id']) && $_POST['user_id'] > 0) {
        $confirm_code = $_POST['confirm_code'];
        $user_id      = $_POST['user_id'];
        if (empty($_POST['confirm_code'])) {
            $errors = $error_icon . 'please_check_details';
        } else if (empty($_POST['user_id'])) {
            $errors = $error_icon .'error_while_activating';
        }
        $confirm_code = Br_ConfirmUser($user_id, $confirm_code);
        if ($confirm_code === false) {
            $errors = $error_icon . 'wrong_confirmation_code';
        }
        if (empty($errors) && $confirm_code === true) {
            $session             = Br_CreateLoginSession($user_id);
            $data                = array(
                'status' => 200
            );
            $_SESSION['user_id'] = $session;
            setcookie("user_id", $session, time() + (10 * 365 * 24 * 60 * 60));
            if (!empty($_POST['last_url'])) {
                $data['location'] = $_POST['last_url'];
            } else {
                $data['location'] = $br['config']['site_url'];
            }
        }
    }
    header("Content-type: application/json");
    if (!empty($errors)) {
        echo json_encode(array(
            'errors' => $errors
        ));
    } else {
        echo json_encode($data);
    }
    exit();
}
