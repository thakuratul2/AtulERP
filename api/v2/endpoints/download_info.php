<?php

$response_data = array(
    'api_status' => 400
);
$types = array('posts','pages','groups','followers','following','my_information','friends');
if (empty($_POST['data'])) {
    $error_code    = 3;
    $error_message = 'data (POST) is missing';
}
else{
	$fetch = explode(',', $_POST['data']);
	$data  = array();
    foreach ($fetch as $key => $value) {
        $data[$value] = $value;
    }
}

if (!empty($data)) {
    if (!empty($br['user']['info_file'])) {
        unlink($br['user']['info_file']);
    }
    $br['user_info'] = array();
    $html = '';
    if (!empty($data['my_information'])) {
        $br['user_info']['setting'] = Br_UserData($br['user']['user_id']);
        $br['user_info']['setting']['session'] = Br_GetAllSessionsFromUserID($br['user']['user_id']);
        $br['user_info']['setting']['block'] = Br_GetBlockedMembers($br['user']['user_id']);
        $br['user_info']['setting']['trans'] = Br_GetMytransactions();
        $br['user_info']['setting']['refs'] = Br_GetReferrers();
    }
    if (!empty($data['posts'])) {
        $br['user_info']['posts'] = Br_GetPosts(array('filter_by' => 'all','publisher_id' => $br['user']['user_id'],'limit' => 100000)); 
    }
    if (!empty($data['pages']) && $br['config']['pages'] == 1) {
        $br['user_info']['pages'] = Br_GetMyPages();
    }
    if (!empty($data['groups']) && $br['config']['groups'] == 1) {
        $br['user_info']['groups'] = Br_GetMyGroups();
    }
    if ($br['config']['connectivitySystem'] == 0) {
        if (!empty($data['followers'])) {
            $br['user_info']['followers'] = Br_GetFollowers($br['user']['user_id'],'profile',1000000);
        }
        if (!empty($data['following'])) {
            $br['user_info']['following'] = Br_GetFollowing($br['user']['user_id'], 'profile',1000000);
        }
    }
    else{
        if (!empty($data['friends'])) {
            $br['user_info']['friends'] = Br_GetMutualFriends($br['user']['user_id'],'profile', 1000000);
        }
    }
        
    $html = Br_LoadPage('user_info/content');

    if (!file_exists('upload/files/' . date('Y'))) {
        @mkdir('upload/files/' . date('Y'), 0777, true);
    }
    if (!file_exists('upload/files/' . date('Y') . '/' . date('m'))) {
        @mkdir('upload/files/' . date('Y') . '/' . date('m'), 0777, true);
    }
    $folder   = 'files';
    $fileType = 'file';
    $dir         = "upload/files/" . date('Y') . '/' . date('m');
    $hash    = $dir . '/' . Br_GenerateKey() . '_' . date('d') . '_' . md5(time()) . "_file.html";
    $file = fopen($hash, 'w');
    fwrite($file, $html);
    fclose($file);
    Br_UpdateUserData($br['user']['user_id'], array(
        'info_file' => $hash
    ));
    $response_data['message'] = $br['lang']['file_ready'];
    $response_data['link'] = Br_GetMedia($hash);
    $response_data['api_status'] = 200;
}
else{
	$error_code    = 6;
    $error_message = 'please check details';
}