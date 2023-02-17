<?php
if ($br['loggedin'] == true) {
  header("Location: " . $br['config']['site_url']);
  exit();
}
if (empty($_SESSION['code_id']) || !isset($_SESSION['code_id'])) {
	header("Location: " . $br['config']['site_url']);
    exit();
}
$br['user'] = Br_UserData($_SESSION['code_id']);
if (empty($br['user'])) {
	header("Location: " . $br['config']['site_url']);
    exit();
}
$br['description'] = $br['config']['siteDesc'];
$br['keywords']    = $br['config']['siteKeywords'];
$br['page']        = 'user_activation';
$br['title']       = 'account_activation';
$br['content']     = br_LoadPage('user_activation/content');