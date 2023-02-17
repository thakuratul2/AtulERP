<?php
if ($f == 'register') {
    if (!empty($_SESSION['user_id'])) {
        $_SESSION['user_id'] = '';
        unset($_SESSION['user_id']);
    }
    if (!empty($_COOKIE['user_id'])) {
        $_COOKIE['user_id'] = '';
        unset($_COOKIE['user_id']);
        setcookie('user_id', null, -1);
        setcookie('user_id', null, -1, '/');
    }

    if (empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_pwd']) || empty($_POST['gender'])) {
        $errors = $error_icon . "Please fill all details !";
    } else {
        if (empty($_POST['email'])) {
            $errors = $error_icon . "Please enter your email !";
        }
        if (Br_EmailExists($_POST['email']) === true) {
            $errors = $error_icon . 'email_exists';
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors = $error_icon . 'email_invalid_characters';
        }
        if (strlen($_POST['password']) < 6) {
            $errors = $error_icon . 'password_short';
        }
        if ($_POST['password'] != $_POST['confirm_pwd']) {
            $errors = $error_icon . 'password_mismatch';
        }
    }
    $profile_pic = "";
    if (isset($_FILES['profileUpload'])) {
        $profile_pic = Br_upload_profile($_FILES['profileUpload']);
        if ($profile_pic == false) {
            $errors = $error_icon . 'Invalid file';
        }
    } else {
        $profile_pic = $br['config']['site_url'] . '/upload/photos/davatar.jpg';
        if ($_POST['gender'] == 'female') {
            $profile_pic = $br['config']['site_url'] . '/upload/photos/favatar.jpg';
        }
    }
    $field_data = array();
    if (empty($errors)) {
        $activate = ($br['config']['emailValidation'] == '1') ? '0' : '1';
        $code = md5(rand(1111, 9999) . time());
        $re_data = array(
            'email' => Br_Secure($_POST['email'], 0),
            'fname' => Br_Secure($_POST['firstName'], 0),
            'lname' => Br_Secure($_POST['lastName'], 0),
            'password' => $_POST['password'],
            'v_code' => Br_Secure($code, 0),
            'src' => 'site',
            'lastseen' => time(),
            'active' => Br_Secure($activate),
            'birthday' => '0000-00-00',
            'gender' => Br_Secure($_POST['gender'], 0),
            'profile_pic' => Br_Secure($profile_pic, 0),
        );


        // if (!empty($_POST['phone_num'])) {
        //     $re_data['phone_number'] = Br_Secure($_POST['phone_num']);
        // }
        // $in_code  = (isset($_POST['invited'])) ? Br_Secure($_POST['invited']) : false;
        // if (empty($_POST['phone_num'])) {
        //     $register = Br_RegisterUser($re_data, $in_code);
        // }
        // else{
        //     if($activate == 1){
        //        $register = Br_RegisterUser($re_data, $in_code);
        //     }
        //     else{
        //         $register = true;
        //     }
        // }

        $registers = Br_RegisterUser($re_data);

        //$registers = true;
        if ($registers === true) {
            if ($activate == 1) {
                $data = array(
                    'status' => 200,
                    'message' => $success_icon . 'Successfully Joined'
                );
                $login = Br_Login($_POST['email'], $_POST['password']);
                if ($login === true) {
                    $session = Br_CreateLoginSession(Br_UserIdFromEmail($_POST['email']));
                    $_SESSION['user_id'] = $session;
                    setcookie("user_id", $session, time() + (10 * 365 * 24 * 60 * 60));
                }
                $data['location'] = Br_SeoLink('index.php?link1=start-up');
                //$data['location'] = Br_SeoLink('index.php?link1=welcome');

            } else if ($br['config']['sms_or_email'] == 'mail') {
                $br['user'] = $_POST;
                $br['code'] = $code;
                $body = Br_LoadPage('emails/activate');
                $send_message_data = array(
                    'from_email' => $br['config']['siteEmail'],
                    'from_name' => $br['config']['siteName'],
                    'to_email' => $_POST['email'],
                    'to_name' => $_POST['fname'],
                    'subject' => 'Account Activation',
                    'charSet' => 'utf-8',
                    'message_body' => $body,
                    'is_html' => true
                );
                $send = Br_SendMessage($send_message_data);
                $errors = $success_icon . 'successfully_joined_verify_label';
                // if ($br['config']['membership_system'] == 1) {
                //     $session             = Br_CreateLoginSession(Br_UserIdFromUsername($_POST['username']));
                //     $_SESSION['user_id'] = $session;
                //     setcookie("user_id", $session, time() + (10 * 365 * 24 * 60 * 60));
                // }
            } else if ($br['config']['sms_or_email'] == 'sms' && !empty($_POST['phone_num'])) {
                $random_activation = Br_Secure(rand(11111, 99999));
                $message = "Your confirmation code is: {$random_activation}";

                if (Br_SendSMSMessage($_POST['phone_num'], $message) === true) {
                    $register = Br_RegisterUser($re_data);
                    $user_id = Br_UserIdFromUsername($_POST['username']);
                    $query = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `sms_code` = '{$random_activation}' WHERE `uid` = {$user_id}");
                    $data = array(
                        'status' => 300,
                        'location' => Br_SeoLink('index.php?link1=confirm-sms?code=' . $code)
                    );
                    // if ($br['config']['membership_system'] == 1) {
                    //     $session             = Br_CreateLoginSession(Br_UserIdFromUsername($_POST['username']));
                    //     $_SESSION['user_id'] = $session;
                    //     setcookie("user_id", $session, time() + (10 * 365 * 24 * 60 * 60));
                    // }
                } else {
                    $errors = $error_icon . 'failed_to_send_code_email';
                }
            }
        }
        if (!empty($field_data)) {
            $user_id = Br_UserIdFromEmail($_POST['email']);
        }
    }
    header("Content-type: application/json");
    if (isset($errors)) {
        echo json_encode(
            array(
                'errors' => $errors
            )
        );
    } else {
        echo json_encode($data);
    }
    exit();
}