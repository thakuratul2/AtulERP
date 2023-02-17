<?php 
if ($f == 'login') {
    if (!empty($_SESSION['user_id'])) {
        $_SESSION['user_id'] = '';
        unset($_SESSION['user_id']);
    }
    if (!empty($_COOKIE['user_id'])) {
        $_COOKIE['user_id'] = '';
        unset($_COOKIE['user_id']);
        setcookie('user_id', null, -1);
        setcookie('user_id', null, -1,'/');
    }
    $data_ = array();
    $phone = 0;
    if (isset($_POST['email']) && isset($_POST['password'])) {
        if ($br['config']['prevent_system'] == 1) {
            if (!BrCanLogin()) {
                $errors[] = $error_icon . 'login_attempts';
                header("Content-type: application/json");
                echo json_encode(array(
                    'errors' => $errors
                ));
                exit();
            }
        }
        $username = Br_Secure($_POST['email']);
        $password = $_POST['password'];
        $result   = Br_Login($username, $password);
        if ($result === false) {
            $errors[] = $error_icon . 'Incorrect Email or Password';
            if ($br['config']['prevent_system'] == 1) {
                BrAddBadLoginLog();
            }
        } else if (Br_UserInactive($_POST['email']) === true) {
            $errors[] = $error_icon . 'account_disbaled_contact_admin';
        } else if (Br_VerfiyIP($_POST['email']) === false) {
            $_SESSION['code_id'] = Br_UserIdForLogin($username);
            $data_               = array(
                'status' => 600,
                'location' => Br_SeoLink('index.php?link1=unusual-login')
            );
            $phone               = 1;
        } else if (Br_TwoFactor($_POST['email']) === false) {
            $_SESSION['code_id'] = Br_UserIdForLogin($username);
            //'location' => $br['config']['site_url'] . '/unusual-login?type=two-factor'
            $data_               = array(
                'status' => 600,
                'location' => Br_SeoLink('index.php?link1=unusual-login&type=two-factor')
            );
            $phone               = 1;
        } else if (Br_UserActive($_POST['email']) === false) {
            $_SESSION['code_id'] = Br_UserIdForLogin($username);
            $data_               = array(
                'status' => 600,
                'location' => Br_SeoLink('index.php?link1=user-activation')
            );
            $phone               = 1;
        }
        if (empty($errors) && $phone == 0) {
            $userid              = Br_UserIdFromEmail($username);
            $ip                  = Br_Secure(get_ip_address());
            $update              = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `ip_address` = '{$ip}' WHERE `uid` = '{$userid}'");
            $session             = Br_CreateLoginSession(Br_UserIdFromEmail($username));
            $_SESSION['user_id'] = $session;
            setcookie("user_id", $session, time() + (10 * 365 * 24 * 60 * 60));
            setcookie('ad-con', htmlentities(json_encode(array(
                'date' => date('Y-m-d'),
                'ads' => array()
            ))), time() + (10 * 365 * 24 * 60 * 60));
            $data = array(
                'status' => 200
            );
            if (!empty($_POST['last_url'])) {
                $data['location'] = $_POST['last_url'];
            } else {
                $data['location'] = $br['config']['site_url']."/index.php?link1=welcome";
            }
            $user_data = Br_UserData($userid);
            // if ($br['config']['membership_system'] == 1) {
            //     $data['location'] = Br_SeoLink('index.php?link1=go-pro');
            // }
        }
    }
    header("Content-type: application/json");
    if (!empty($errors)) {
        echo json_encode(array(
            'errors' => $errors
        ));
    } else if (!empty($data_)) {
        echo json_encode($data_);
    } else {
        echo json_encode($data);
    }
    exit();
}
