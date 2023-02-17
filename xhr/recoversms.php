<?php 
if ($f == 'recoversms') {
    if (empty($_POST['recoverphone'])) {
        $errors = $error_icon . 'please_check_details';
    } else {
        if (!filter_var($_POST['recoverphone'], FILTER_SANITIZE_NUMBER_INT)) {
            $errors = $error_icon . 'phone_invalid_characters';
        }
        if (!in_array(true, Br_IsPhoneExist($_POST['recoverphone']))) {
            $errors = $error_icon . 'phonenumber_not_found';
        }
    }
    if (empty($errors)) {
        $random_activation = Br_Secure(rand(11111, 99999));
        $message           = 'confirmation code is' . ": {$random_activation}";
        $user_id           = Br_UserIdFromPhoneNumber($_POST['recoverphone']);
        $code              = md5(rand(111, 999) . time());
        $query             = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `sms_code` = '{$random_activation}', `email_code` = '$code' WHERE `uid` = {$user_id}");
        if ($query) {
            if (Br_SendSMSMessage($_POST['recoverphone'], $message) === true) {
                $data = array(
                    'status' => 200,
                    'message' => $success_icon . 'recover sms_sent',
                    'location' => Br_SeoLink('index.php?link1=confirm-sms-password?code=' . $code)
                );
            } else {
                $errors = $error_icon . 'failed_to_send_code_email';
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
