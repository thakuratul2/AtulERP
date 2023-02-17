<?php
if ($br['loggedin'] == false) {
    if ($br['config']['profile_privacy'] == 0) {
        header("Location: " . Br_SeoLink('index.php?link1=welcome'));
        exit();
    }
}
if (isset($_GET['u'])) {
    $check_user = Br_IsNameExist($_GET['u'], 1);
    if (in_array(true, $check_user)) {
        $id = $user_id = Br_UserIdFromEmail($_GET['u']);
        $br['user_profile'] = Br_UserData($user_id);
        $type = 'user';
        $about = $br['user_profile']['about'];
        $name = $br['user_profile']['name'];
        //$br['user_profile']['fields'] = Br_UserFieldsData($user_id);

        if ($br['loggedin'] == true) {


        }

    } else {
        header("Location: " . Br_SeoLink('index.php?link1=404'));
        exit();
    }
} else {
    header("Location: " . $br['config']['site_url']);
    exit();
}



if ($type == 'timeline') {
    // $user_data = Br_UpdateUserDetails($br['user_profile'], true, true, true);
    // if (is_array($user_data)) {
    //     $br['user_profile'] = $user_data;
    //     $about  = $br['user_profile']['about'];
    //     $name   = $br['user_profile']['name'];
    //     $br['user_profile']['fields'] = Br_UserFieldsData($user_id);
    // }
}

$br['description'] = $about;
$br['keyBrrds'] = '';
$br['page'] = $type;
$br['keywords']    = $br['config']['siteKeywords'];
$br['title'] = str_replace('&#039;', "'", $name);
$br['content'] = Br_LoadPage("{$type}/content");