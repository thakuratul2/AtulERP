<?php 
if ($f == 'reset_password') {
    if (isset($_POST['id'])) {
        $user_id  = explode("_", $_POST['id']);
        if (Br_isValidPasswordResetToken($_POST['id']) === false && Br_isValidPasswordResetToken2($_POST['id']) === false) {
            $errors = $error_icon . 'invalid_token';
        } elseif (empty($_POST['id'])) {
            $errors = $error_icon . 'processing_error';
        } elseif (empty($_POST['password'])) {
            $errors = $error_icon . 'please_check_details';
        } elseif (strlen($_POST['password']) < 5) {
            $errors = $error_icon . 'password_short';
        } else if (Br_TwoFactor($user_id[0], 'id') === false) {
            $_SESSION['code_id'] = $user_id[0];
            $password = $_POST['password'];
            if (Br_ResetPassword($user_id[0], $password) === true) {
                $data               = array(
                    'status' => 600,
                    'location' => $br['config']['site_url'] . '/unusual-login?type=two-factor'
                );
                $phone               = 1;
            }
        }
        if (empty($errors) && empty($phone)) {
            $password = $_POST['password'];
            if (Br_ResetPassword($user_id[0], $password) === true) {
                $_SESSION['user_id'] = Br_CreateLoginSession($user_id[0]);
            }
            $data = array(
                'status' => 200,
                'message' => $success_icon . 'password_changed',
                'location' => $br['config']['site_url']
            );
        }
    }
    header("Content-type: application/json");
    if (isset($errors)) {
        echo json_encode(array(
            'errors' => $errors
        ));
    } else {
        echo json_encode($data);
    }
    exit();
}
