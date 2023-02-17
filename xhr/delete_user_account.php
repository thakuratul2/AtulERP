<?php 
if ($f == 'delete_user_account' && $br['config']['deleteAccount'] == 1) {
    if (isset($_POST['password'])) {
        if (Br_HashPassword($_POST['password'], $br['user']['password']) == false) {
            $errors[] = $error_icon . 'current_password_mismatch';
        }
        if (empty($errors)) {
            if (Br_DeleteUser($br['user']['uid']) === true) {
                $data = array(
                    'status' => 200,
                    'message' => $success_icon . 'account_deleted',
                    'location' => Br_SeoLink('index.php?link1=logout')
                );
            }
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
