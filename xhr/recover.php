<?php 
if ($f == 'recover') {
    if (empty($_POST['recoveremail'])) {
        $errors = $error_icon . 'please_check_details';
    } else {
        if (!filter_var($_POST['recoveremail'], FILTER_VALIDATE_EMAIL)) {
            $errors = $error_icon . 'email_invalid_characters';
        } else if (br_EmailExists($_POST['recoveremail']) === false) {
            $errors = $error_icon . 'email_not_found';
        }
    }
    if (empty($errors)) {
        $user_recover_data         = Br_UserData(Br_UserIdFromEmail($_POST['recoveremail']));
        $subject                   = $config['siteName'] . ' ' . 'password_rest_request';
        $code              = md5(rand(111, 999) . time());
        $user_recover_data['link'] = Br_Link('index.php?link1=reset-password&code=' . $user_recover_data['uid'] . '_' . $code);
        $query                     = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `email_code` = '$code' WHERE `uid` = {$user_recover_data['uid']}");
        $br['recover']             = $user_recover_data;
        $body                      = Br_LoadPage('emails/recover');
        $send_message_data         = array(
            'from_email' => $br['config']['siteEmail'],
            'from_name' => $br['config']['siteName'],
            'to_email' => $_POST['recoveremail'],
            'to_name' => '',
            'subject' => $subject,
            'charSet' => 'utf-8',
            'message_body' => $body,
            'is_html' => true
        );
        $send                      = Br_SendMessage($send_message_data);
        $data                      = array(
            'status' => 200,
            'message' => $success_icon . 'email_sent'
        );
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
