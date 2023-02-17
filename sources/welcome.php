<?php
// if ($br['loggedin'] == true) {
//   header("Location: " . $br['config']['site_url']);
//   exit();
// }

$br['description'] = $br['config']['siteDesc'];
$br['keywords']    = $br['config']['siteKeywords'];
$br['page']        = 'welcome';
$br['title']       = "Welcome".' | '.$br['config']['siteTitle'];
$br['content']     = Br_LoadPage('welcome/content');