<?php
$response_data   = array(
    'api_status' => 400
);

$user_data = array();
if (!empty($_POST)) {
	$user_data = $_POST;
}

$escape = array('server_key');
$genders = array('male', 'female');
$keys = array();
$remove_from_list = array('user_id', 'background_image', 'background_image_status', 'last_data_update', 'sidebar_data', 'details', 'id'. 'following_data', 'name', 'url', 'followers_data', 'likes_data', 'groups_data', 'album_data', 'css_file', 'joined', 'admin', 'email_code', 'ip_address', 'active', 'type', 'sms_code', 'is_pro', 'balance', 'referrer', 'wallet', 'points','relationship','relationship_user');
foreach ($br['user'] as $key => $value) {
	if (!in_array($key, $remove_from_list )) {
		$keys[] = $key;
	}
}

$keys = implode(', ', $keys);

if (!empty($user_data['username'])) {
	$is_exist = Br_IsNameExist($user_data['username'], 0);
    if (in_array(true, $is_exist) && $user_data['username'] != $br['user']['username']) {
        $error_code    = 2;
        $error_message = 'Username is already exists';
    }
    if (in_array($user_data['username'], $br['site_pages']) || !preg_match('/^[\w]+$/', $user_data['username'])) {
        $error_code    = 3;
        $error_message = 'Invalid username characters';
    }
    if (strlen($user_data['username']) < 5 || strlen($user_data['username']) > 32) {
        $error_code    = 4;
        $error_message = 'Username must be between 5/32';
    }
}

if (!empty($user_data['email'])) {
	$is_exist = Br_EmailExists($user_data['email']);
    if ($is_exist && $user_data['email'] != $br['user']['email']) {
        $error_code    = 5;
        $error_message = 'E-mail is already exists';
    }
    if (!filter_var($user_data['email'], FILTER_VALIDATE_EMAIL)) {
        $error_code    = 6;
        $error_message = 'Invalid email characters';
    }
}

if (!empty($user_data['phone_number'])) {
	$is_exist = Br_PhoneExists($user_data['phone_number']);
    if ($is_exist && $user_data['phone_number'] != $br['user']['phone_number']) {
        $error_code    = 7;
        $error_message = 'Phone number already used';
    }
}

if (!empty($user_data['new_password']) && !empty($user_data['current_password'])) {
    if (Br_HashPassword($user_data['current_password'], $br['user']['password']) == false) {
        $error_code    = 8;
        $error_message = 'Current password not match';
    }
    if (strlen($user_data['new_password']) < 6) {
        $error_code    = 9;
        $error_message = 'Password is too short';
    }
    if (empty($error_code)) {
    	$user_data['password'] = password_hash($user_data['new_password'], PASSWORD_DEFAULT);
    	unset($user_data['new_password']);
    	unset($user_data['current_password']);
    }
}

if (!empty($user_data['gender'])) {
	$user_data['gender'] = (in_array($user_data['gender'], $genders)) ? $user_data['gender'] : $br['user']['gender'];
}

if (!empty($user_data['follow_privacy'])) {
	$user_data['follow_privacy'] = (in_array($user_data['follow_privacy'], array(0, 1))) ? $user_data['follow_privacy'] : $br['user']['follow_privacy'];
}

if (!empty($user_data['message_privacy'])) {
	$user_data['message_privacy'] = (in_array($user_data['message_privacy'], array(0, 1))) ? $user_data['message_privacy'] : $br['user']['message_privacy'];
}

if (!empty($user_data['birth_privacy'])) {
	$user_data['birth_privacy'] = (in_array($user_data['birth_privacy'], array(0, 1, 2))) ? $user_data['birth_privacy'] : $br['user']['birth_privacy'];
}

if (!empty($user_data['friend_privacy'])) {
	$user_data['friend_privacy'] = (in_array($user_data['friend_privacy'], array(0, 1, 2, 3))) ? $user_data['friend_privacy'] : $br['user']['friend_privacy'];
}

if (!empty($user_data['post_privacy'])) {
	$user_data['post_privacy'] = (in_array($user_data['post_privacy'], array('everyone', 'ifollow', 'nobody'))) ? $user_data['post_privacy'] : $br['user']['post_privacy'];
}

if (!empty($user_data['confirm_followers'])) {
	$user_data['confirm_followers'] = (in_array($user_data['confirm_followers'], array(0, 1))) ? $user_data['confirm_followers'] : $br['user']['confirm_followers'];
}

if (!empty($user_data['visit_privacy'])) {
	$user_data['visit_privacy'] = (in_array($user_data['visit_privacy'], array(0, 1))) ? $user_data['visit_privacy'] : $br['user']['visit_privacy'];
}

if (!empty($user_data['showlastseen'])) {
	$user_data['showlastseen'] = (in_array($user_data['showlastseen'], array(0, 1))) ? $user_data['showlastseen'] : $br['user']['showlastseen'];
}

if (!empty($user_data['show_activities_privacy'])) {
	$user_data['show_activities_privacy'] = (in_array($user_data['show_activities_privacy'], array(0, 1))) ? $user_data['show_activities_privacy'] : $br['user']['show_activities_privacy'];
}

if (!empty($user_data['share_my_location'])) {
	$user_data['share_my_location'] = (in_array($user_data['share_my_location'], array(0, 1))) ? $user_data['share_my_location'] : $br['user']['share_my_location'];
}

if (!empty($user_data['status'])) {
	$user_data['status'] = (in_array($user_data['status'], array(0, 1))) ? $user_data['status'] : $br['user']['status'];
}

if (!empty($_FILES["avatar"]["tmp_name"])) {
	$upload_image = Br_UploadImage($_FILES["avatar"]["tmp_name"], $_FILES['avatar']['name'], 'avatar', $_FILES['avatar']['type'], $br['user']['user_id']);
    if ($upload_image) {
        $response_data['api_status'] = 200;
    }
}

if (!empty($_FILES["cover"]["tmp_name"])) {
	$upload_image = Br_UploadImage($_FILES["cover"]["tmp_name"], $_FILES['cover']['name'], 'cover', $_FILES['cover']['type'], $br['user']['user_id']);
    if ($upload_image) {
        $response_data['api_status'] = 200;
    }
}

if (isset($user_data['server_key'])) {
	unset($user_data['server_key']);
}

if (empty($error_code)) {
    foreach ($remove_from_list as $rkey => $rvalue) {
        unset($user_data[$rvalue]);
    }
	foreach ($user_data as $key => $value) {

		if (!isset($br['user'][$key]) && !in_array($key, $escape)) {
			$error_code = 1;
			$error_message = "Key #$key not found, check Br_Users table to get the correct information, or you can use the following keys: $keys";
			unset($user_data[$key]);
		}
	}
}
if (!empty($user_data['two_factor']) && $user_data['two_factor'] == 'off') {
    $user_data['two_factor'] = 0;
}
elseif (!empty($user_data['two_factor']) && $user_data['two_factor'] == 'on') {
    $user_data['two_factor'] = 1;
}

if (!is_numeric($_POST['relationship']) || empty($_POST['relationship'])) {
    $user_data['relationship_id'] = 0;
    Br_DeleteMyRelationShip();
}
if (!empty($_POST['relationship']) && is_numeric($_POST['relationship']) && $_POST['relationship'] > 0 && $_POST['relationship'] <= 4) {
    if ($_POST['relationship'] > 1 && isset($_POST['relationship_user']) && is_numeric($_POST['relationship_user']) && $_POST['relationship_user'] > 0) {
        $relationship_user = Br_Secure($_POST['relationship_user']);
        $user              = Br_Secure($br['user']['id']);
        if (!Br_IsRelationRequestExists($user, $relationship_user, $_POST['relationship'])) {
            $registration_data = array(
                'from_id' => $user,
                'to_id' => $relationship_user,
                'relationship' => Br_Secure($_POST['relationship']),
                'active' => 0
            );
            $registration_id   = Br_RegisterRelationship($registration_data);
            if ($registration_id) {
                $relationship_user_data  = Br_UserData($relationship_user);
                $notification_data_array = array(
                    'recipient_id' => $relationship_user,
                    'type' => 'added_u_as',
                    'user_id' => $br['user']['id'],
                    'text' => $br['lang']['relationship_request'],
                    'url' => 'index.php?link1=timeline&u=' . $relationship_user_data['username'] . '&type=requests'
                );
                Br_RegisterNotification($notification_data_array);
            }
        }
    }
    $user_data['relationship_id'] = Br_Secure($_POST['relationship']);
}

if (empty($error_code)) {

    if (isset($_POST['language']) AND !empty($_POST['language'])) {
        if (in_array($_POST['language'], array_keys($br['config'])) && $br['config'][$_POST['language']] == 1) {
            $lang_name = Br_Secure(strtolower($_POST['language']));
            $langs                    = Br_LangsNamesFromDB();
            if (in_array($lang_name, $langs)) {
                Br_CleanCache();
                if ($br['loggedin'] == true) {
                    $user_data['language'] = $lang_name;
                }
            }
        }
    }


	$update = Br_UpdateUserData($br['user']['user_id'], $user_data);
	$update_last_seen = Br_LastSeen($br['user']['user_id']);
	if ($update) {
		$response_data['api_status'] = 200;
		$response_data['message'] = 'Your profile was updated';
	}
}