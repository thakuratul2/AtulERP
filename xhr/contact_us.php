<?php 
if ($f == 'contact_us') {
    if ($br['config']['reCaptcha'] == 1) {
        if (empty($_POST['g-recaptcha-response'])) {
            $errors[] = $error_icon . $br['lang']['please_check_details'];
        }
        else{
            $recaptcha_data = array(
            'secret' => $br['config']['recaptcha_secret_key'],
            'response' => $_POST['g-recaptcha-response']
            );

            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($recaptcha_data));
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($verify);
            $response = json_decode($response);
            if (!$response->success) {
                $errors[] = $error_icon . $br['lang']['reCaptcha_error'];
            }
        }
    }
    if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['message'])) {
        $errors[] = $error_icon . $br['lang']['please_check_details'];
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = $error_icon . $br['lang']['email_invalid_characters'];
    }
    if (empty($errors)) {
        $first_name        = Br_Secure($_POST['first_name']);
        $last_name         = Br_Secure($_POST['last_name']);
        $email             = Br_Secure($_POST['email']);
        $message           = Br_Secure($_POST['message']);
        $name              = $first_name . ' ' . $last_name;
        $send_message_data = array(
            'from_email' => $br['config']['siteEmail'],
            'from_name' => $name,
            'reply-to' => $email,
            'to_email' => $br['config']['siteEmail'],
            'to_name' => $br['config']['siteName'],
            'subject' => 'Contact us new message',
            'charSet' => 'utf-8',
            'message_body' => $message,
            'is_html' => false
        );
        $send              = Br_SendMessage($send_message_data);
        if ($send) {
            $data = array(
                'status' => 200,
                'message' => $success_icon . $br['lang']['email_sent']
            );
        } else {
            $errors[] = $error_icon . $br['lang']['processing_error'];
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
