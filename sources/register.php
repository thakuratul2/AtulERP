<?php
if ($br['loggedin'] == true) {
  header("Location: " . $br['config']['site_url']);
  exit();
}
// if ($br['config']['user_registration'] == 0 && (!isset($_GET['invite']) || (!Br_IsAdminInvitationExists($_GET['invite']) && !Br_IsUserInvitationExists($_GET['invite'])) )) {
// 	header("Location: " . $br['config']['site_url']);
//     exit();
// }
else{
	$br['description'] = $br['config']['siteDesc'];
	$br['keywords']    = $br['config']['siteKeywords'];
	$br['page']        = 'register';
	$br['title']       = $br['config']['siteTitle'] . ' | Register';
	$br['content']     = Br_LoadPage('welcome/register');
}
