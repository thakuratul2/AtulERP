<?php

$response_data = array(
    'api_status' => 400,
);
if (empty($_POST['email'])) {
    $error_code    = 3;
    $error_message = 'email (POST) is missing';
}
if (empty($error_code)) {
    if (Br_EmailExists($_POST['email']) === false) {
        $error_code    = 6;
        $error_message = 'Email not found';
    } else {
    	$user_recover_data         = Br_UserData(Br_UserIdFromEmail($_POST['email']));
        $subject                   = $config['siteName'] . ' ' . $br['lang']['password_rest_request'];
        $user_recover_data['link'] = Br_Link('index.php?link1=reset-password&code=' . $user_recover_data['user_id'] . '_' . $user_recover_data['password']);
        $br['recover']             = $user_recover_data;
        $body                      = Br_LoadPage('emails/recover');
        $send_message_data         = array(
            'from_email' => $br['config']['siteEmail'],
            'from_name' => $br['config']['siteName'],
            'to_email' => $_POST['email'],
            'to_name' => '',
            'subject' => $subject,
            'charSet' => 'utf-8',
            'message_body' => $body,
            'is_html' => true
        );
        $send                      = Br_SendMessage($send_message_data);
        if ($send) {
        	$response_data = array(
			    'api_status' => 200,
			);
        } else {
        	$error_code    = 7;
            $error_message = 'Failed to send the email, please check your server email settings.';
        }
    }
}