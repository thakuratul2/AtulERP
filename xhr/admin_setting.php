<?php

use function PHPSTORM_META\type;

if ($f == 'admin_setting' and (Br_IsAdmin() || Br_IsModerator())) {

    // if ($s == 'search_in_pages') {
    //     $keyword = Br_Secure($_POST['keyword']);
    //     $html = '';

    //     $files = scandir('./admin-panel/pages');
    //     $not_allowed_files = array('edit-custom-page', 'edit-lang', 'edit-movie', 'edit-profile-field', 'edit-terms-pages');
    //     foreach ($files as $key => $file) {
    //         if (file_exists('./admin-panel/pages/' . $file . '/content.phtml') && !in_array($file, $not_allowed_files)) {

    //             $string = file_get_contents('./admin-panel/pages/' . $file . '/content.phtml');
    //             preg_match_all("@(?s)<h2([^<]*)>([^<]*)<\/h2>@", $string, $matches1);

    //             if (!empty($matches1) && !empty($matches1[2])) {
    //                 foreach ($matches1[2] as $key => $title) {
    //                     if (strpos(strtolower($title), strtolower($keyword)) !== false) {
    //                         $page_title = '';
    //                         preg_match_all("@(?s)<h2([^<]*)>([^<]*)<\/h2>@", $string, $matches3);
    //                         if (!empty($matches3) && !empty($matches3[2])) {
    //                             foreach ($matches3[2] as $key => $title2) {
    //                                 $page_title = $title2;
    //                                 break;
    //                             }
    //                         }
    //                         $html .= '<a href="' . Br_LoadAdminLinkSettings($file) . '?highlight=' . $keyword . '"><div  style="padding: 5px 2px;">' . $page_title . '</div><div><small style="color: #333;">' . $title . '</small></div></a>';
    //                         break;
    //                     }
    //                 }
    //             }

    //             preg_match_all("@(?s)<label([^<]*)>([^<]*)<\/label>@", $string, $matches2);
    //             if (!empty($matches2) && !empty($matches2[2])) {
    //                 foreach ($matches2[2] as $key => $lable) {
    //                     if (strpos(strtolower($lable), strtolower($keyword)) !== false) {
    //                         $page_title = '';
    //                         preg_match_all("@(?s)<h2([^<]*)>([^<]*)<\/h2>@", $string, $matches3);
    //                         if (!empty($matches3) && !empty($matches3[2])) {
    //                             foreach ($matches3[2] as $key => $title2) {
    //                                 $page_title = $title2;
    //                                 break;
    //                             }
    //                         }

    //                         $html .= '<a href="' . Br_LoadAdminLinkSettings($file) . '?highlight=' . $keyword . '"><div  style="padding: 5px 2px;">' . $page_title . '</div><div><small style="color: #333;">' . $lable . '</small></div></a>';
    //                         break;
    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     $data = array(
    //         'status' => 200,
    //         'html'   => $html
    //     );
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }

    // if ($s == 'remove_provider') {
    //     if (!empty($_POST['provider'])) {
    //         if (in_array($_POST['provider'], $br['config']['providers_array'])) {
    //             foreach ($br['config']['providers_array'] as $key => $provider) {
    //                 if ($provider == $_POST['provider']) {
    //                     unset($br['config']['providers_array'][$key]);
    //                 }
    //             }
    //             $saveSetting = Br_SaveConfig('providers_array', json_encode($br['config']['providers_array']));
    //         }
    //     }
    //     $data = array(
    //         'status' => 200
    //     );
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }
    // if ($s == 'add_new_provider') {
    //     if (!empty($_POST['provider'])) {
    //         $br['config']['providers_array'][] = Br_Secure($_POST['provider']);
    //         $saveSetting = Br_SaveConfig('providers_array', json_encode($br['config']['providers_array']));
    //     }
    //     $data = array(
    //         'status' => 200
    //     );
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }

    // if ($s == 'approve_receipt') {
    //     if (!empty($_GET['receipt_id'])) {
    //         $id = Br_Secure($_GET['receipt_id']);
    //         $receipt = $db->where('id', $id)->getOne('bank_receipts', array('*'));

    //         if ($receipt) {
    //             $updated = $db->where('id', $id)->update('bank_receipts', array('approved' => 1, 'approved_at' => time()));
    //             $updated = true;
    //             if ($updated === true) {
    //                 if ($receipt->mode == 'wallet') {
    //                     $amount = $receipt->price;
    //                     $result = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `wallet` = `wallet` + " . $amount . " WHERE `user_id` = '" . $receipt->user_id . "'");
    //                     if ($result) {
    //                         $create_payment_log = mysqli_query($sqlConnect, "INSERT INTO " . T_PAYMENT_TRANSACTIONS . " (`userid`, `kind`, `amount`, `notes`) VALUES ('" . $receipt->user_id . "', 'WALLET', '" . $amount . "', 'bank receipts')");
    //                     }
    //                     $notification_data_array = array(
    //                         'recipient_id' => $receipt->user_id,
    //                         'type' => 'admin_notification',
    //                         'url' => 'index.php?link1=wallet',
    //                         'text' => $br['lang']['bank_pro'],
    //                         'type2' => 'no_name'
    //                     );
    //                     Br_RegisterNotification($notification_data_array);
    //                 } elseif ($receipt->mode == 'donate') {
    //                     $fund = $db->where('id', $receipt->fund_id)->getOne(T_FUNDING);
    //                     if (!empty($fund)) {
    //                         $amount = $receipt->price;
    //                         $fund_id = $receipt->fund_id;


    //                         $notes = "Doanted to " . mb_substr($fund->title, 0, 100, "UTF-8");

    //                         $create_payment_log = mysqli_query($sqlConnect, "INSERT INTO " . T_PAYMENT_TRANSACTIONS . " (`userid`, `kind`, `amount`, `notes`) VALUES ({$receipt->user_id}, 'DONATE', {$amount}, '{$notes}')");

    //                         $admin_com = 0;
    //                         if (!empty($br['config']['donate_percentage']) && is_numeric($br['config']['donate_percentage']) && $br['config']['donate_percentage'] > 0) {
    //                             $admin_com = ($br['config']['donate_percentage'] * $amount) / 100;
    //                             $amount = $amount - $admin_com;
    //                         }
    //                         $user_data = Br_UserData($fund->user_id);
    //                         $db->where('user_id', $fund->user_id)->update(T_USERS, array('balance' => $user_data['balance'] + $amount));
    //                         $fund_raise_id = $db->insert(T_FUNDING_RAISE, array(
    //                             'user_id' => $receipt->user_id,
    //                             'funding_id' => $fund_id,
    //                             'amount' => $amount,
    //                             'time' => time()
    //                         ));
    //                         $post_data = array(
    //                             'user_id' => $receipt->user_id,
    //                             'fund_raise_id' => $fund_raise_id,
    //                             'time' => time(),
    //                             'multi_image_post' => 0
    //                         );

    //                         $id = Br_RegisterPost($post_data);

    //                         $notification_data_array = array(
    //                             'notifier_id'  => $receipt->user_id,
    //                             'recipient_id' => $fund->user_id,
    //                             'type' => 'fund_donate',
    //                             'url' => 'index.php?link1=show_fund&id=' . $fund->hashed_id
    //                         );
    //                         Br_RegisterNotification($notification_data_array);

    //                         $notification_data_array = array(
    //                             'recipient_id' => $receipt->user_id,
    //                             'type' => 'admin_notification',
    //                             'url' => 'index.php?link1=show_fund&id=' . $fund->hashed_id,
    //                             'text' => $br['lang']['bank_pro'],
    //                             'type2' => 'no_name'
    //                         );
    //                         Br_RegisterNotification($notification_data_array);
    //                     }
    //                 } else {
    //                     $pro_type = $receipt->mode;
    //                     $update_array = array(
    //                         'is_pro' => 1,
    //                         'pro_time' => time(),
    //                         'pro_' => 1,
    //                         'pro_type' => $pro_type
    //                     );
    //                     if (in_array($pro_type, array_keys($br['pro_packages_types'])) && $br['pro_packages'][$br['pro_packages_types'][$pro_type]]['verified_badge'] == 1) {
    //                         $update_array['verified'] = 1;
    //                     }
    //                     $mysqli       = Br_UpdateUserData($receipt->user_id, $update_array);

    //                     $user_data = Br_UserData($receipt->user_id);

    //                     if (!empty($user_data['ref_user_id']) && $br['config']['affiliate_type'] == 1 && $user_data['referrer'] == 0) {
    //                         $amount1 = $receipt->price;
    //                         $ref_user_id = $user_data['ref_user_id'];


    //                         if ($br['config']['amount_percent_ref'] > 0) {
    //                             if (!empty($ref_user_id) && is_numeric($ref_user_id)) {
    //                                 $update_user    = Br_UpdateUserData($user_data['user_id'], array(
    //                                     'referrer' => $ref_user_id,
    //                                     'src' => 'Referrer'
    //                                 ));
    //                                 $ref_amount     = ($br['config']['amount_percent_ref'] * $amount1) / 100;
    //                                 $update_balance = Br_UpdateBalance($ref_user_id, $ref_amount);
    //                                 unset($_SESSION['ref']);
    //                             }
    //                         } else if ($br['config']['amount_ref'] > 0) {
    //                             if (!empty($ref_user_id) && is_numeric($ref_user_id)) {
    //                                 $update_user    = Br_UpdateUserData($user_data['user_id'], array(
    //                                     'referrer' => $ref_user_id,
    //                                     'src' => 'Referrer'
    //                                 ));
    //                                 $update_balance = Br_UpdateBalance($ref_user_id, $br['config']['amount_ref']);
    //                                 unset($_SESSION['ref']);
    //                             }
    //                         }
    //                     }

    //                     $amount1 = $receipt->price;
    //                     $notes              = $br['lang']['upgrade_to_pro'] . " " . $receipt->description . " : Bank";
    //                     $create_payment_log = mysqli_query($sqlConnect, "INSERT INTO " . T_PAYMENT_TRANSACTIONS . " (`userid`, `kind`, `amount`, `notes`) VALUES ({$br['user']['user_id']}, 'PRO', {$amount1}, '{$notes}')");

    //                     $notification_data_array = array(
    //                         'recipient_id' => $receipt->user_id,
    //                         'type' => 'admin_notification',
    //                         'url' => 'index.php?link1=upgraded',
    //                         'text' => $br['lang']['bank_pro'],
    //                         'type2' => 'no_name'
    //                     );
    //                     Br_RegisterNotification($notification_data_array);
    //                 }
    //                 $data = array(
    //                     'status' => 200
    //                 );
    //             }
    //         }
    //         $data = array(
    //             'status' => 200,
    //             'data' => $receipt
    //         );
    //     }
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }
    // if ($s == 'delete_receipt') {
    //     if (!empty($_GET['receipt_id'])) {
    //         $user_id = Br_Secure($_GET['user_id']);
    //         $id = Br_Secure($_GET['receipt_id']);
    //         $photo_file = Br_Secure($_GET['receipt_file']);
    //         $receipt = $db->where('id', $id)->getOne('bank_receipts', array('*'));

    //         $notification_data_array = array(
    //             'recipient_id' => $receipt->user_id,
    //             'type' => 'admin_notification',
    //             'url' => 'index.php',
    //             'text' => $br['lang']['bank_decline'],
    //             'type2' => 'no_name'
    //         );
    //         Br_RegisterNotification($notification_data_array);

    //         $db->where('id', $id)->delete('bank_receipts');
    //         if (file_exists($photo_file)) {
    //             @unlink(trim($photo_file));
    //         } else if ($br['config']['amazone_s3'] == 1 || $br['config']['ftp_upload'] == 1) {
    //             @Br_DeleteFromToS3($photo_file);
    //         }
    //         $data = array(
    //             'status' => 200
    //         );
    //     }
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }

    if ($s == 'add_project') {
        $data           = array();
        $data['status'] = 200;
        $data['error']  = false;
        if (empty($_POST['name']) || empty($_POST['valu']) || empty($_POST['description']) || empty($_POST['priority'])) {
            $data['status'] = 500;
            $data['error']  = 'Please check details';
        }
        if (!is_numeric($_POST['valu']) || !is_numeric($_POST['priority'])) {
            $data['status'] = 500;
            $data['error']  = 'Numbers only are allowed';
        }
        if ($_POST['valu'] < 0 || $_POST['priority'] < 0) {
            $data['status'] = 500;
            $data['error']  = 'Integer numbers only are allowed';
        }
        if (empty($data['error']) && $data['status'] != 500) {
            $registration_date = date('n') . '/' . date("Y");
            if(Br_addProject($_POST['name'], $_POST['valu'], $_POST['priority'], $_POST['description'], $registration_date)){
                $data['status'] = 200;
            }else{
                $data['status'] = 500;
                $data['error']  = 'Error Creating Project';
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'edit_project') {
        $data           = array();
        $data['status'] = 200;
        $data['error']  = false;
        if (empty($_POST['role_id']) || empty($_POST['name']) || empty($_POST['valu']) || empty($_POST['description']) || empty($_POST['priority'])) {
            $data['status'] = 500;
            $data['error']  = 'please_check_details';
        }
        if (!is_numeric($_POST['role_id']) || !is_numeric($_POST['valu'])) {
            $data['status'] = 500;
            $data['error']  = 'Numbers only are allowed';
        }
        if ($_POST['role_id'] < 0 || $_POST['valu'] < 0) {
            $data['status'] = 500;
            $data['error']  = 'Integer numbers only are allowed';
        }
        if (empty($data['error']) && $data['status'] != 500) {
            if(Br_editProject($_POST['role_id'], $_POST['name'], $_POST['valu'], $_POST['priority'], $_POST['description'], $_POST['status'])){
                $data['status'] = 200;
            }else{
                $data['status'] = 500;
                $data['error']  = 'Error Updating Project';
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'add_roles') {
        $data           = array();
        $data['status'] = 200;
        $data['error']  = false;
        if (empty($_POST['name']) || empty($_POST['description']) || empty($_POST['priority'])) {
            $data['status'] = 500;
            $data['error']  = 'please_check_details';
        }
        if (!is_numeric($_POST['priority'])) {
            $data['status'] = 500;
            $data['error']  = 'Numbers only are allowed';
        }
        if ($_POST['priority'] < 0) {
            $data['status'] = 500;
            $data['error']  = 'Integer numbers only are allowed';
        }
        if (empty($data['error']) && $data['status'] != 500) {
            if(Br_addTeamRole($_POST['name'], $_POST['priority'], $_POST['description'])){
                $data['status'] = 200;
            }else{
                $data['status'] = 500;
                $data['error']  = 'Error Adding Admin';
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'edit_roles') {
        $data           = array();
        $data['status'] = 200;
        $data['error']  = false;
        if (empty($_POST['role_id']) || empty($_POST['name']) || empty($_POST['description']) || empty($_POST['priority'])) {
            $data['status'] = 500;
            $data['error']  = 'please_check_details';
        }
        if (!is_numeric($_POST['role_id'])) {
            $data['status'] = 500;
            $data['error']  = 'Numbers only are allowed';
        }
        if ($_POST['role_id'] < 0) {
            $data['status'] = 500;
            $data['error']  = 'Integer numbers only are allowed';
        }
        if (empty($data['error']) && $data['status'] != 500) {
            if(Br_editTeamRole($_POST['role_id'], $_POST['name'], $_POST['priority'], $_POST['description'])){
                $data['status'] = 200;
            }else{
                $data['status'] = 500;
                $data['error']  = 'Error Adding Admin';
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'assign_post') {
        $data           = array();
        $data['status'] = 200;
        $data['error']  = false;
        if (empty($_POST['post_id']) || empty(($_POST['user_id']))) {
            $data['status'] = 500;
            $data['error']  = 'please_check_details';
        }
        if (!is_numeric($_POST['post_id']) || !is_numeric($_POST['user_id'])) {
            $data['status'] = 500;
            $data['error']  = 'Numbers only are allowed';
        }
        if ($_POST['post_id'] < 0) {
            $data['status'] = 500;
            $data['error']  = 'Integer numbers only are allowed';
        }
        if (empty($data['error']) && $data['status'] != 500) {
            if(Br_assignPost($_POST['post_id'], $_POST['user_id'])){
                $data['status'] = 200;
            }else{
                $data['status'] = 500;
                $data['error']  = 'Error Assigning Post !';
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'delete_user_post') {
        $data           = array();
        $data['status'] = 200;
        $data['error']  = false;
        if (empty($_POST['user_id'])) {
            $data['status'] = 500;
            $data['error']  = 'please_check_details';
        }
        if (!is_numeric($_POST['user_id'])) {
            $data['status'] = 500;
            $data['error']  = 'Numbers only are allowed';
        }
        if ($_POST['user_id'] == 0 || $_POST['user_id'] == 1) {
            $data['status'] = 500;
            $data['error']  = 'Operation on Admin is Not allowed !';
        }
        if (empty($data['error']) && $data['status'] != 500) {
            if(Br_deletePost($_POST['user_id'])){
                $data['status'] = 200;
            }else{
                $data['status'] = 500;
                $data['error']  = 'Error Assigning Post !';
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'add_access_level') {
        $data           = array();
        $data['status'] = 200;
        $data['error']  = false;
        if (empty($_POST['alevel']) || empty($_POST['user_id'])) {
            $data['status'] = 500;
            $data['error']  = 'please_check_details';
        }
        if (!is_numeric($_POST['alevel']) || !is_numeric($_POST['user_id'])) {
            $data['status'] = 500;
            $data['error']  = 'Numbers only are allowed';
        }
        if ($_POST['alevel'] < 0 || $_POST['user_id'] < 0) {
            $data['status'] = 500;
            $data['error']  = 'Integer numbers only are allowed';
        }
        if (empty($data['error']) && $data['status'] != 500) {
            if(Br_addAdmin($_POST['user_id'], $_POST['alevel'])){
                $data['status'] = 200;
            }else{
                $data['status'] = 500;
                $data['error']  = 'Error Adding Admin';
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'update_custom_code') {
        $data    = array(
            'status' => 400
        );
        $theme   = $br['config']['theme'];
        $request = (isset($_POST['cheader']) && isset($_POST['cfooter']) && isset($_POST['css']));
        if ($request === true) {
            if (is_writable("themes/$theme/custom")) {
                $up_data        = array(
                    $_POST['cheader'],
                    $_POST['cfooter'],
                    $_POST['css']
                );
                $save           = Br_CustomCode('p', $up_data);
                $data['status'] = 200;
            } else {
                $data['status'] = 500;
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    // if ($s == 'update_ref_system') {
    //     $saveSetting = false;
    //     if (!empty($_POST['affiliate_type'])) {
    //         $_POST['affiliate_type'] = 1;
    //     } else {
    //         $_POST['affiliate_type'] = 0;
    //     }
    //     foreach ($_POST as $key => $value) {
    //         if ($key != 'hash_id') {
    //             $saveSetting = Br_SaveConfig($key, $value);
    //         }
    //     }
    //     if ($saveSetting === true) {
    //         $data['status'] = 200;
    //     }
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }
    // if ($s == 'mark_as_paid') {
    //     if (!empty($_GET['id']) && Br_CheckSession($hash_id)) {
    //         $get_payment_info = Br_GetPaymentHistory($_GET['id']);
    //         if (!empty($get_payment_info)) {
    //             $id     = $get_payment_info['id'];
    //             $update = mysqli_query($sqlConnect, "UPDATE " . T_A_REQUESTS . " SET status = '1' WHERE id = {$id}");
    //             if ($update) {
    //                 $body              = Br_LoadPage('emails/payment-sent');
    //                 $body              = str_replace('{name}', $get_payment_info['user']['name'], $body);
    //                 $body              = str_replace('{amount}', $get_payment_info['amount'], $body);
    //                 $body              = str_replace('{site_name}', $config['siteName'], $body);
    //                 $send_message_data = array(
    //                     'from_email' => $br['config']['siteEmail'],
    //                     'from_name' => $br['config']['siteName'],
    //                     'to_email' => $get_payment_info['user']['email'],
    //                     'to_name' => $get_payment_info['user']['name'],
    //                     'subject' => 'New Payment | ' . $br['config']['siteName'],
    //                     'charSet' => 'utf-8',
    //                     'message_body' => $body,
    //                     'is_html' => true
    //                 );
    //                 $send_message      = Br_SendMessage($send_message_data);

    //                 $notification_data_array = array(
    //                     'recipient_id' => $get_payment_info['user_id'],
    //                     'type' => 'admin_notification',
    //                     'url' => 'index.php?link1=setting&page=payments',
    //                     'text' => $br['lang']['withdraw_approve'],
    //                     'type2' => 'withdraw_approve'
    //                 );
    //                 Br_RegisterNotification($notification_data_array);
    //                 if ($send_message) {
    //                     $data['status'] = 200;
    //                 }
    //             }
    //         }
    //     }
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }
    // if ($s == 'decline_payment') {
    //     if (!empty($_GET['id']) && Br_CheckSession($hash_id)) {
    //         $get_payment_info = Br_GetPaymentHistory($_GET['id']);
    //         if (!empty($get_payment_info)) {
    //             $id     = $get_payment_info['id'];
    //             $update = mysqli_query($sqlConnect, "UPDATE " . T_A_REQUESTS . " SET status = '2' WHERE id = {$id}");
    //             if ($update) {
    //                 $body              = Br_LoadPage('emails/payment-declined');
    //                 $body              = str_replace('{name}', $get_payment_info['user']['name'], $body);
    //                 $body              = str_replace('{amount}', $get_payment_info['amount'], $body);
    //                 $body              = str_replace('{site_name}', $config['siteName'], $body);
    //                 $send_message_data = array(
    //                     'from_email' => $br['config']['siteEmail'],
    //                     'from_name' => $br['config']['siteName'],
    //                     'to_email' => $get_payment_info['user']['email'],
    //                     'to_name' => $get_payment_info['user']['name'],
    //                     'subject' => 'Payment Declined | ' . $br['config']['siteName'],
    //                     'charSet' => 'utf-8',
    //                     'message_body' => $body,
    //                     'is_html' => true
    //                 );
    //                 $send_message      = Br_SendMessage($send_message_data);

    //                 $notification_data_array = array(
    //                     'recipient_id' => $get_payment_info['user_id'],
    //                     'type' => 'admin_notification',
    //                     'url' => 'index.php?link1=setting&page=payments',
    //                     'text' => $br['lang']['withdraw_declined'],
    //                     'type2' => 'withdraw_declined'
    //                 );
    //                 Br_RegisterNotification($notification_data_array);
    //                 if ($send_message) {
    //                     $data['status'] = 200;
    //                 }
    //             }
    //         }
    //     }
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }
    if ($s == 'add_new_page') {
        if (Br_CheckSession($hash_id) === true && !empty($_POST['page_name']) && !empty($_POST['page_content']) && !empty($_POST['page_title'])) {
            $page_name    = Br_Secure($_POST['page_name']);
            $page_content = Br_Secure(str_replace(array("\r", "\n"), "", $_POST['page_content']));
            $page_title   = Br_Secure($_POST['page_title']);
            $page_type    = 0;
            if (!empty($_POST['page_type'])) {
                $page_type = 1;
            }
            if (!preg_match('/^[\w]+$/', $page_name)) {
                $data = array(
                    'status' => 400,
                    'message' => 'Invalid page name characters'
                );
                header("Content-type: application/json");
                echo json_encode($data);
                exit();
            }
            $data_ = array(
                'page_name' => $page_name,
                'page_content' => $page_content,
                'page_title' => $page_title,
                'page_type' => $page_type
            );
            $add   = Br_RegisterNewPage($data_);
            if ($add) {
                $data['status'] = 200;
            }
        } else {
            $data = array(
                'status' => 400,
                'message' => 'Please fill all the required fields'
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'edit_page') {
        if (Br_CheckSession($hash_id) === true && !empty($_POST['page_id']) && !empty($_POST['page_name']) && !empty($_POST['page_content']) && !empty($_POST['page_title'])) {
            $page_name    = $_POST['page_name'];
            $page_content = $_POST['page_content'];
            $page_title   = $_POST['page_title'];
            $page_type    = 0;
            if (!empty($_POST['page_type'])) {
                $page_type = 1;
            }
            if (!preg_match('/^[\w]+$/', $page_name)) {
                $data = array(
                    'status' => 400,
                    'message' => 'Invalid page name characters'
                );
                header("Content-type: application/json");
                echo json_encode($data);
                exit();
            }
            $data_ = array(
                'page_name' => $page_name,
                'page_content' => $page_content,
                'page_title' => $page_title,
                'page_type' => $page_type
            );
            $add   = Br_UpdateCustomPageData($_POST['page_id'], $data_);
            if ($add) {
                $data['status'] = 200;
            }
        } else {
            $data = array(
                'status' => 400,
                'message' => 'Please fill all the required fields'
            );
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_page') {
        if (Br_CheckMainSession($hash_id) === true && !empty($_GET['id'])) {
            $delete = Br_DeleteCustomPage($_GET['id']);
            if ($delete) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'new_backup') {
        $b = Br_Backup($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);
        if ($b) {
            $data['status'] = 200;
            $data['date']   = date('d-m-Y');
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'update_general_setting' && Br_CheckSession($hash_id) === true) {
        $saveSetting         = false;

        foreach ($_POST as $key => $value) {
            if (isset($br['config'][$key]) || $key == 'googleAnalytics_en') {
                if ($key == 'googleAnalytics_en') {
                    $key   = 'googleAnalytics';
                    $value = base64_decode($value);
                }
                
                
                $saveSetting = Br_SaveConfig($key, $value);
            }
        }
        if ($saveSetting === true) {
            $data['status'] = 200;
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }


    if ($s == 'update_terms_setting') {
        $saveSetting = false;
        foreach ($_POST as $key => $value) {
            if ($key != 'hash_id') {
                $saveSetting = Br_SaveTerm($key, base64_decode($value));
            }
        }
        if ($saveSetting === true) {
            $data['status'] = 200;
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'test_message') {
        $send_message_data = array(
            'from_email' => $br['config']['siteEmail'],
            'from_name' => $br['config']['siteName'],
            'to_email' => $br['user']['email'],
            'to_name' => $br['user']['name'],
            'subject' => 'Test Message From ' . $br['config']['siteName'],
            'charSet' => 'utf-8',
            'message_body' => 'If you can see this message, then your SMTP configuration is working fine.',
            'is_html' => false
        );
        $send_message      = Br_SendMessage($send_message_data);
        if ($send_message === true) {
            $data['status'] = 200;
        } else {
            $data['status'] = 400;
            $data['error']  = "Error found while sending the email, the information you provided are not correct, please test the email settings on your local device and make sure they are correct. ";
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    // if ($s == 'update_sms_setting') {
    //     $saveSetting = false;
    //     foreach ($_POST as $key => $value) {
    //         if ($key != 'hash_id') {
    //             $saveSetting = Br_SaveConfig($key, $value);
    //         }
    //     }
    //     if ($saveSetting === true) {
    //         $data['status'] = 200;
    //     }
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }
    if ($s == 'test_sms_message') {
        $message      = 'This is a test message from ' . $br['config']['siteName'];
        $send_message = Br_SendSMSMessage($br['config']['sms_phone_number'], $message);
        if ($send_message === true) {
            $data['status'] = 200;
        } else {
            $data['status'] = 400;
            $data['error']  = $send_message;
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'send_sms_users') {
        $data           = array();
        $data['status'] = 200;
        $data['error']  = false;
        if (empty($_POST['phone']) || empty($_POST['message'])) {
            $data['status'] = 500;
            $data['error']  = 'Please check details';
        }
        // if (!is_numeric($_POST['phone'])) {
        //     $data['status'] = 500;
        //     $data['error']  = 'Numbers only are allowed';
        // }
        if (empty($data['error']) && $data['status'] != 500) {
            $send_message = Br_SendSMSMessage($_POST['phone'], $_POST['message']);
            if($send_message == true){
                $data['status'] = 200;
                $data['message'] = 'Message sent successfully';
            }else{
                $data['status'] = 500;
                $data['error']  = $send_message;
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'update_design_setting') {
        $saveSetting = false;
        if (isset($_FILES['logo']['name'])) {
            $fileInfo = array(
                'file' => $_FILES["logo"]["tmp_name"],
                'name' => $_FILES['logo']['name'],
                'size' => $_FILES["logo"]["size"]
            );
            $media    = Br_UploadLogo($fileInfo);
        }
        if (isset($_FILES['background']['name'])) {
            $fileInfo = array(
                'file' => $_FILES["background"]["tmp_name"],
                'name' => $_FILES['background']['name'],
                'size' => $_FILES["background"]["size"]
            );
            $media    = Br_UploadBackground($fileInfo);
        }
        if (isset($_FILES['favicon']['name'])) {
            $fileInfo = array(
                'file' => $_FILES["favicon"]["tmp_name"],
                'name' => $_FILES['favicon']['name'],
                'size' => $_FILES["favicon"]["size"]
            );
            $media    = Br_UploadFavicon($fileInfo);
        }
        foreach ($_POST as $key => $value) {
            if ($key != 'hash_id') {
                $saveSetting = Br_SaveConfig($key, $value);
            }
        }
        if ($saveSetting === true) {
            $data['status'] = 200;
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    
    if ($s == 'updateTheme' && isset($_POST['theme'])) {
        $saveSetting = false;
        foreach ($_POST as $key => $value) {
            if ($key != 'hash_id') {
                $saveSetting = Br_SaveConfig($key, $value);
            }
        }
        if ($saveSetting === true) {
            $data['status'] = 200;
        }
        $files = glob('cache/*'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file
        }
        if (!file_exists('cache/index.html')) {
            $f = @fopen("cache/index.html", "a+");
            @fclose($f);
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'delete_user' && isset($_GET['user_id']) && Br_CheckMainSession($hash_id) === true) {
        if (Br_DeleteUser($_GET['user_id']) === true) {
            $data['status'] = 200;
        }else{
            $data['status'] = 400;
            $data['error']  = "Error found while deleting the user, please try again later.";
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    // if ($s == 'filter_all_users') {
    //     $html  = '';
    //     $after = (isset($_GET['after_user_id']) && is_numeric($_GET['after_user_id']) && $_GET['after_user_id'] > 0) ? $_GET['after_user_id'] : 0;
    //     foreach (Br_GetAllUsers(20, 'ManageUsers', $_POST, $after) as $br['userlist']) {
    //         $html .= Br_LoadAdminPage('manage-users/list');
    //     }
    //     $data = array(
    //         'status' => 200,
    //         'html' => $html
    //     );
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }
    // if ($s == 'get_more_pages') {
    //     $html  = '';
    //     $after = (isset($_GET['after_page_id']) && is_numeric($_GET['after_page_id']) && $_GET['after_page_id'] > 0) ? $_GET['after_page_id'] : 0;
    //     foreach (Br_GetAllPages(20, $after) as $br['pagelist']) {
    //         $html .= Br_LoadAdminPage('manage-pages/list');;
    //     }
    //     $data = array(
    //         'status' => 200,
    //         'html' => $html
    //     );
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }

    if ($s == 'delete_role' && isset($_GET['user_id']) && Br_CheckMainSession($hash_id) === true) {
        if (Br_DeleteRole($_GET['user_id']) === true) {
            $data['status'] = 200;
        }else{
            $data['status'] = 500;
            $data['error']  = "Error found while deleting the role, please try again later.";
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'delete_project' && isset($_GET['user_id']) && Br_CheckMainSession($hash_id) === true) {
        if (Br_DeleteProject($_GET['user_id']) === true) {
            $data['status'] = 200;
        }else{
            $data['status'] = 500;
            $data['error']  = "Error found while deleting the project, please try again later.";
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'assign_project') {
        $data           = array();
        $data['status'] = 200;
        $data['error']  = false;
        if (empty($_POST['post_id']) || empty(($_POST['user_id']))) {
            $data['status'] = 500;
            $data['error']  = 'please_check_details';
        }
        if (!is_numeric($_POST['post_id']) || !is_numeric($_POST['user_id'])) {
            $data['status'] = 500;
            $data['error']  = 'Numbers only are allowed';
        }
        if ($_POST['post_id'] < 0) {
            $data['status'] = 500;
            $data['error']  = 'Integer numbers only are allowed';
        }
        if (empty($data['error']) && $data['status'] != 500) {
            if(Br_assignProject($_POST['post_id'], $_POST['user_id'])){
                $data['status'] = 200;
            }else{
                $data['status'] = 500;
                $data['error']  = 'Error Assigning Project !';
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'update_users_setting' && isset($_POST['user_lastseen'])) {
        $delete_follow_table = 0;
        $saveSetting         = false;
        foreach ($_POST as $key => $value) {
            $saveSetting = Br_SaveConfig($key, $value);
        }
        if ($saveSetting === true) {
            $data['status'] = 200;
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    // if ($s == 'verify_user' && Br_CheckMainSession($hash_id) === true) {
    //     if (!empty($_GET['id'])) {
    //         $type = '';
    //         if (!empty($_GET['type'])) {
    //             $type = $_GET['type'];
    //         }
    //         if (Br_VerifyUser($_GET['id'], $_GET['verification_id'], $type) === true) {
    //             $data = array(
    //                 'status' => 200
    //             );
    //         }
    //     }
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }

    if ($s == 'reset_windows_app_keys') {
        $app_key    = sha1(rand(111111111, 999999999)) . '-' . md5(microtime()) . '-' . rand(11111111, 99999999);
        $data_array = array(
            'widnows_app_api_key' => $app_key
        );
        foreach ($data_array as $key => $value) {
            $saveSetting = Br_SaveConfig($key, $value);
        }
        if ($saveSetting === true) {
            $data['status']  = 200;
            $data['app_key'] = $app_key;
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    if ($s == 'send_mail_to_all_users') {
        $isset_test = 'off';
        if (empty($_POST['message']) || empty($_POST['subject'])) {
            $send_errors = $error_icon . 'please_check_details';
        } else {
            if (!empty($_POST['test_message'])) {
                if ($_POST['test_message'] == 'on') {
                    $isset_test = 'on';
                }
            }
            if ($isset_test == 'on') {
                $send_message_data = array(
                    'from_email' => $br['config']['siteEmail'],
                    'from_name' => $br['config']['siteName'],
                    'to_email' => $br['user']['email'],
                    'to_name' => $br['user']['name'],
                    'subject' => $_POST['subject'],
                    'charSet' => 'utf-8',
                    'message_body' => $_POST['message'],
                    'is_html' => true
                );
                $send              = Br_SendMessage($send_message_data);
            } else {
                $users_type = 'all';
                $users      = array();
                if (isset($_POST['selected_emails']) && strlen($_POST['selected_emails']) > 0) {
                    $user_ids = explode(',', $_POST['selected_emails']);
                    if (is_array($user_ids) && count($user_ids) > 0) {
                        foreach ($user_ids as $user_id) {
                            $users[] = Br_UserData($user_id);
                        }
                    }
                } else if ($_POST['send_to'] == 'active') {
                    $users = Br_GetAllUsersByType('active');
                } else if ($_POST['send_to'] == 'inactive') {
                    $users = Br_GetAllUsersByType('inactive');
                }
                Br_RunInBackground(array('status' => 300));
                foreach ($users as $user) {
                    $send_message_data = array(
                        'from_email' => $br['config']['siteEmail'],
                        'from_name' => $br['config']['siteName'],
                        'to_email' => $user['email'],
                        'to_name' => $user['name'],
                        'subject' => $_POST['subject'],
                        'charSet' => 'utf-8',
                        'message_body' => $_POST['message'],
                        'is_html' => true
                    );
                    $send              = Br_SendMessage($send_message_data);
                    $mail->ClearAddresses();
                }
            }
        }
        header("Content-type: application/json");
        if (!empty($send_errors)) {
            $send_errors_data = array(
                'status' => 400,
                'message' => $send_errors
            );
            echo json_encode($send_errors_data);
        } else {
            $data = array(
                'status' => 200
            );
            echo json_encode($data);
        }
        exit();
    }

    if ($s == 'send_mail_to_mock_users') {
        $isset_test = 'off';
        $types = array('week', 'month', '3month', '6month', '9month', 'year');
        if (empty($_POST['message']) || empty($_POST['subject']) || empty($_POST['send_to']) || !in_array($_POST['send_to'], $types)) {
            $send_errors = $error_icon . 'please_check_details';
        } else {
            if (!empty($_POST['test_message'])) {
                if ($_POST['test_message'] == 'on') {
                    $isset_test = 'on';
                }
            }
            if ($isset_test == 'on') {
                $send_message_data = array(
                    'from_email' => $br['config']['siteEmail'],
                    'from_name' => $br['config']['siteName'],
                    'to_email' => $br['user']['email'],
                    'to_name' => $br['user']['name'],
                    'subject' => $_POST['subject'],
                    'charSet' => 'utf-8',
                    'message_body' => $_POST['message'],
                    'is_html' => true
                );
                $send              = Br_SendMessage($send_message_data);
            } else {
                $users      = array();
                if (isset($_POST['selected_emails']) && strlen($_POST['selected_emails']) > 0) {
                    $user_ids = explode(',', $_POST['selected_emails']);
                    if (is_array($user_ids) && count($user_ids) > 0) {
                        foreach ($user_ids as $user_id) {
                            $users[] = Br_UserData($user_id);
                        }
                    }
                } else {
                    $users = Br_GetUsersByTime($_POST['send_to']);
                }
                Br_RunInBackground(array('status' => 300));
                foreach ($users as $user) {
                    $send_message_data = array(
                        'from_email' => $br['config']['siteEmail'],
                        'from_name' => $br['config']['siteName'],
                        'to_email' => $user['email'],
                        'to_name' => $user['name'],
                        'subject' => $_POST['subject'],
                        'charSet' => 'utf-8',
                        'message_body' => $_POST['message'],
                        'is_html' => true
                    );
                    $send              = Br_SendMessage($send_message_data);
                    $mail->ClearAddresses();
                }
            }
        }
        header("Content-type: application/json");
        if (!empty($send_errors)) {
            $send_errors_data = array(
                'status' => 400,
                'message' => $send_errors
            );
            echo json_encode($send_errors_data);
        } else {
            $data = array(
                'status' => 200
            );
            echo json_encode($data);
        }
        exit();
    }
    if ($s == 'get_users_emails' && isset($_GET['name'])) {
        $name  = Br_Secure($_GET['name']);
        $html  = '';
        $users = Br_GetUsersByName($name, 20);
        $data  = array(
            'status' => 404
        );
        if (count($users) > 0) {
            foreach ($users as $user) {
                $html .= "<p data-user='" . $user['uid'] . "'>" . $user['fname'] . "</p>";
            }
            $data['status'] = 200;
            $data['html']   = $html;
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'add_new_announcement') {
        if (!empty($_POST['announcement_text'])) {
            $html = '';
            $id   = Br_AddNewAnnouncement(base64_decode($_POST['announcement_text']));
            if ($id > 0) {
                $br['activeAnnouncement'] = Br_GetAnnouncement($id);
                $html .= Br_LoadAdminPage('manage-announcements/active-list');
                $data = array(
                    'status' => 200,
                    'text' => $html
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'delete_announcement') {
        if (!empty($_GET['id'])) {
            $DeleteAnnouncement = Br_DeleteAnnouncement($_GET['id']);
            if ($DeleteAnnouncement === true) {
                $data = array(
                    'status' => 200
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'disable_announcement') {
        if (!empty($_GET['id'])) {
            $html                = '';
            $DisableAnnouncement = Br_DisableAnnouncement($_GET['id']);
            if ($DisableAnnouncement === true) {
                $br['inactiveAnnouncement'] = Br_GetAnnouncement($_GET['id']);
                $html .= Br_LoadAdminPage('manage-announcements/inactive-list');
                $data = array(
                    'status' => 200,
                    'html' => $html
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }
    if ($s == 'activate_announcement') {
        if (!empty($_GET['id'])) {
            $html                 = '';
            $ActivateAnnouncement = Br_ActivateAnnouncement($_GET['id']);
            if ($ActivateAnnouncement === true) {
                $br['activeAnnouncement'] = Br_GetAnnouncement($_GET['id']);
                $html .= Br_LoadAdminPage('manage-announcements/active-list');
                $data = array(
                    'status' => 200,
                    'html' => $html
                );
            }
        }
        header("Content-type: application/json");
        echo json_encode($data);
        exit();
    }

    // if ($s == 'delete_refund') {
    //     if (!empty($_GET['id'])) {
    //         $request = $db->where('id', Br_Secure($_GET['id']))->getOne(T_REFUND);
    //         $db->where('id', Br_Secure($_GET['id']))->delete(T_REFUND);
    //         $data = array(
    //             'status' => 200
    //         );
    //         $notification_data_array = array(
    //             'recipient_id' => $request->user_id,
    //             'type' => 'admin_notification',
    //             'url' => 'index.php?link1=home',
    //             'text' => $br['lang']['refund_decline'],
    //             'type2' => 'refund_decline'
    //         );
    //         Br_RegisterNotification($notification_data_array);
    //     }
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }
    // if ($s == 'approve_refund') {
    //     if (!empty($_GET['id'])) {
    //         $request = $db->where('id', Br_Secure($_GET['id']))->getOne(T_REFUND);
    //         if (!empty($request)) {
    //             $price = $br['pro_packages'][$request->pro_type]['price'];
    //             $db->where('user_id', $request->user_id)->update(T_USERS, array(
    //                 'balance' => $db->inc($price),
    //                 'is_pro' => 0
    //             ));
    //             $db->where('id', Br_Secure($_GET['id']))->delete(T_REFUND);
    //             $notification_data_array = array(
    //                 'recipient_id' => $request->user_id,
    //                 'type' => 'admin_notification',
    //                 'url' => 'index.php?link1=setting&page=payments',
    //                 'text' => $br['lang']['refund_approve'],
    //                 'type2' => 'refund_approve'
    //             );
    //             Br_RegisterNotification($notification_data_array);
    //         }

    //         $data = array(
    //             'status' => 200
    //         );
    //     }
    //     header("Content-type: application/json");
    //     echo json_encode($data);
    //     exit();
    // }
}
