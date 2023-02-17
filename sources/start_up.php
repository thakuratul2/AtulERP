<?php
if ($br['loggedin'] == false) {
    header("Location: " . Br_SeoLink('index.php?link1=welcome'));
    exit();
}
if (Br_IsUserComplete($br['user']['uid']) === false) {
	header("Location: " . Br_SeoLink('index.php?link1=welcome'));
    exit();
}

if(isset($_GET['link2'])) {
	if($_GET['link2'] == 'basic-startup') {
		$page = 'basic_startup';
	} else if($_GET['link2'] == 'info-startup') {
		$page = 'info_startup';
	} else if($_GET['link2'] == 'more-startup') {
		$page = 'more_startup';
	} else {
		header("Location: " . Br_SeoLink('index.php?link1=welcome'));
		exit();
	}
}
else{
	if (Br_IsUserNotCompleteBasicType($br['user']['uid']) == true) {
		$page = 'basic_startup';
	} else if (Br_IsUserNotCompleteInfoType($br['user']['uid']) == true) {
		$page = 'info_startup';
	} else if (Br_IsUserNotCompleteMoreType($br['user']['uid']) == true) {
		$page = 'more_startup';
	} else {
		header("Location: " . Br_SeoLink('index.php?link1=welcome'));
		exit();
	}
}


$br['description'] = $br['config']['siteDesc'];
$br['keywords']    = $br['config']['siteKeywords'];
$br['page']        = 'start_up';

$br['title']       = 'StartUp | '.$br['config']['siteTitle'];
$br['content']     = Br_LoadPage('start_up/' . $page);