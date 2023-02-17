<?php
// if ($br['loggedin'] == false || $br['config']['pro'] == 0) {
// 	header("Location: " . Br_SeoLink('index.php?link1=welcome'));
//     exit();
// }
$page = '';
$allowed_pg = array('web', 'app','graphics','content','marketing');
if(isset($_GET['service']) && in_array($_GET['service'], $allowed_pg)) {
    $page = $_GET['service'];
} else {
    $page = 'web';
}

$br['description'] = '';
$br['keywords']    = '';
$br['page']        = 'services';
$br['title']       = $page.' '.'Services | ' . $br['config']['siteTitle'];
$br['content']     = Br_LoadPage("company/$page");