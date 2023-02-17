<?php
if ($br['loggedin'] == false) {
  header("Location: " . $br['config']['site_url']);
  exit();
}

$is_admin = Br_IsAdmin();
$is_moderoter = Br_IsModerator();

if (isset($_GET['user']) && ($is_admin == true || $is_moderoter == true)) {
  $check_user = Br_IsNameExist($_GET['user'], 1);
  if (in_array(true, $check_user)) {
    $id = $user_id = Br_UserIdFromEmail($_GET['user']);
    $br['user_profile'] = Br_UserData($user_id);
    $type = 'user';
    $about = $br['user_profile']['about'];
    $name = $br['user_profile']['fname'];
    //$br['user_profile']['fields'] = Br_UserFieldsData($user_id);

    if ($br['loggedin'] == true) {


    }

  } else {
    header("Location: " . Br_SeoLink('index.php?link1=404'));
    exit();
  }
} else {
  $br['user_profile'] = $br['user'];
  // header("Location: " . $br['config']['site_url']);
  // exit();
}
// printArray($br);
$br['description'] = $br['config']['siteDesc'];
$br['keywords'] = $br['config']['siteKeywords'];
$br['page'] = 'edit-profile';
$br['title'] = "Edit Profile" . " | " . $br['config']['siteTitle'];
$br['content'] = Br_LoadPage('user/edit_profile');