<?php
if ($br['loggedin'] == true) {
  header("Location: " . $br['config']['site_url']);
  exit();
}
if (empty($_GET['code'])) {
	header("Location: " . $br['config']['site_url']);
    exit();
}
$file = 'reset-password';
$validate = Br_isValidPasswordResetToken($_GET['code']);
if ($validate === false) {
	$validate = Br_isValidPasswordResetToken2($_GET['code']);
	if ($validate === false) {
		$file = 'invalid-markup';
	}
}
$br['description'] = $br['config']['siteDesc'];
$br['keywords']    = $br['config']['siteKeywords'];
$br['page']        = 'welcome';
$br['title']       = 'Rest Password | '.$br['config']['siteTitle'];
$br['content']     = Br_LoadPage('welcome/' . $file);

