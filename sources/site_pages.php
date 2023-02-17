<?php
if (empty($_GET['page_name'])) {
	header("Location: " . $config['site_url']);
	exit();
}

$page_data = $br['page_data'] = Br_GetCustomPage($_GET['page_name']);
if (empty($page_data)) {
	header("Location: " . $config['site_url']);
	exit();
}

$br['description'] = $br['config']['siteDesc'];
$br['keywords']    = $br['config']['siteKeywords'];
$br['page']        = 'custom_page';
$br['title']       = $page_data['page_title'];
$br['content']     = Br_LoadPage('terms/custom-page');