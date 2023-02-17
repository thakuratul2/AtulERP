<?php
// if ($br['loggedin'] == false || $br['config']['pro'] == 0) {
// 	header("Location: " . Br_SeoLink('index.php?link1=welcome'));
//     exit();
// }
$br['description'] = '';
$br['keywords']    = '';
$br['page']        = 'About';
$br['title']       = 'About | ' . $br['config']['siteTitle'];
$br['content']     = Br_LoadPage('company/about');