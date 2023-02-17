<?php 
if ($f == 'resned_code') {
    if (isset($_POST['user_id']) && is_numeric($_POST['user_id']) && $_POST['user_id'] > 0) {
        $user = Br_UserData($_POST['user_id']);
        if (empty($user) || empty($_POST['user_id']) || empty($_POST['phone_number'])) {
            $errors = $br['lang']['failed_to_send_code'];
        }
        if (!preg_match('/^\+?\d+$/', $_POST['phone_number'])) {
            $errors = $br['lang']['worng_phone_number'];
        }
        if (Br_PhoneExists($_POST['phone_number']) === true) {
            if ($user['phone_number'] != $_POST['phone_number']) {
                $errors = $br['lang']['phone_already_used'];
            }
        }
        if (empty($errors)) {
            $random_activation = Br_Secure(rand(11111, 99999));
            $message           = "Your confirmation code is: {$random_activation}";
            $user_id           = $_POST['user_id'];
            $query             = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `sms_code` = '{$random_activation}' WHERE `user_id` = {$user_id}");
            if ($query) {
                if (Br_SendSMSMessage($_POST['phone_number'], $message) === true) {
                    $data = array(
                        'status' => 200,
                        'message' => $success_icon . $br['lang']['sms_has_been_sent']
                    );
                } else {
                    $errors = $br['lang']['error_while_sending_sms'];
                }
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
