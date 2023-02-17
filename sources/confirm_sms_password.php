<?php
if ($br['loggedin'] == true) {
  header("Location: " . $br['config']['site_url']);
  exit();
}
if (empty($_GET['code'])) {
	header("Location: " . $br['config']['site_url']);
    exit(); 
}
$get_user = $br['confirm_user'] = Br_UserData(Br_UserIDFromEmailCode($_GET['code']));

if (empty($get_user)) {
	header("Location: " . $br['config']['site_url']);
    exit(); 
}


$br['description'] = $br['config']['siteDesc'];
$br['keywords']    = $br['config']['siteKeywords'];
$br['page']        = 'welcome';
$br['title']       = $br['config']['siteTitle'];
$br['content']     = Br_LoadPage('welcome/confirm-sms-password');

