<?php
if (empty($_GET['type']) || !isset($_GET['type'])) {
	header("Location: " . $br['config']['site_url']);
	exit();
}
$pages = array('terms','privacy-policy','about-us','developers');
// if ($br['config']['refund_system'] == 'on') {
// 	$pages[] = 'refund';
// }
if (!in_array($_GET['type'], $pages)) {
	header("Location: " . $br['config']['site_url']);
	exit();
}
$br['terms'] = Br_GetTerms();

$br['description'] = $br['config']['siteDesc'];
$br['keywords']    = $br['config']['siteKeywords'];
$br['page']        = 'terms';
$br['title']       = '';
$type = Br_Secure($_GET['type']);

if ($type == 'terms') {
	$br['title']  = 'terms_of_use';
} else if ($type == 'about-us') {
    $br['title']  = 'about';
} else if ($type == 'privacy-policy') {
    $br['title']  = 'privacy_policy';
} else if ($type == 'developers') {
	if ($br['config']['developers_page'] == 0) {
		header("Location: " . $br['config']['site_url']);
	    exit();
	}
    $br['title']  = 'developers';
} else if ($type == 'refund') {
	$br['title']  = 'refund';
}

$page = 'terms/' . $type;

$br['title'] = $br['config']['siteName'] . ' | ' . $br['title'];
$br['content']     = Br_LoadPage($page);