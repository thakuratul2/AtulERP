<?php 
if ($f == 'resned_ac_email') {
    if (isset($_SESSION['code_id'])) {
        $email   = 0;
        $phone   = 0;
        $user_id = $_SESSION['code_id'];
        $user    = Br_UserData($_SESSION['code_id']);
        if (empty($user) || empty($_SESSION['code_id']) || (empty($_POST['phone_number']) && empty($_POST['email']))) {
            $errors[] = $error_icon . 'failed_to_send_code_fill';
        }
        if (!empty($_POST['email'])) {
            if (Br_EmailExists($_POST['email']) === true && $user['email'] != $_POST['email']) {
                $errors[] = $error_icon .'email_exists';
            }
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = $error_icon . 'email_invalid_characters';
            }
            if (empty($errors)) {
                $email = 1;
                $phone = 0;
            }
        } else if (!empty($_POST['phone_number'])) {
            if (!preg_match('/^\+?\d+$/', $_POST['phone_number'])) {
                $errors[] = $error_icon . 'worng_phone_number';
            }
            if (Br_PhoneExists($_POST['phone_number']) === true) {
                if ($user['phone_number'] != $_POST['phone_number']) {
                    $errors[] = $error_icon . 'phone_already_used';
                }
            }
            if (empty($errors)) {
                $email = 0;
                $phone = 1;
            }
        }
        if (empty($errors)) {
            if ($email == 1 && $phone == 0) {
                $br['user']             = $_POST;
                $br['user']['username'] = $user['fname'];
                $code                   = md5(rand(1111, 9999));
                $br['code']             = $code;
                $body                   = Br_LoadPage('emails/activate');
                $send_message_data      = array(
                    'from_email' => $br['config']['siteEmail'],
                    'from_name' => $br['config']['siteName'],
                    'to_email' => $_POST['email'],
                    'to_name' => $user['username'],
                    'subject' => 'account_activation',
                    'charSet' => 'utf-8',
                    'message_body' => $body,
                    'is_html' => true
                );
                $query                  = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `email` = '" . Br_Secure($_POST['email']) . "', `email_code` = '$code' WHERE `uid` = {$user_id}");
                $send                   = Br_SendMessage($send_message_data);
                if ($send) {
                    $data = array(
                        'status' => 200,
                        'message' => $success_icon . 'email_sent_successfully'
                    );
                }
            } else if ($email == 0 && $phone == 1) {
                $random_activation = Br_Secure(rand(11111, 99999));
                $message           = "Your confirmation code is: {$random_activation}";
                $user_id           = $_SESSION['code_id'];
                $phone_num         = Br_Secure($_POST['phone_number']);
                $query             = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `phone_number` = '{$phone_num}' WHERE `uid` = {$user_id}");
                $query             = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `sms_code` = '{$random_activation}' WHERE `uid` = {$user_id}");
                if ($query) {
                    if (Br_SendSMSMessage($_POST['phone_number'], $message) === true) {
                        $data = array(
                            'status' => 600,
                            'message' => $success_icon . 'sms_has_been_sent'
                        );
                    } else {
                        $errors[] = $error_icon . 'error_while_sending_sms';
                    }
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
