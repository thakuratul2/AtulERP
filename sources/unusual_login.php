<?php
if (empty($_SESSION['code_id'])) {
	header("Location: " . Br_SeoLink('index.php?link1=welcome'));
    exit();
}
$br['description'] = '';
$br['keywords']    = '';
$br['page']        = 'welcomes';
$br['title']       = 'Confirm your account';

$br['lang']['confirm_your_account'] = 'Unusual Login';
$br['lang']['sign_in_attempt'] = 'To log in, you need to verify your identity';
$br['lang']['we_have_sent_you_code'] = 'We have sent you the confirmation code to your phone and to your email address.';

if (!empty($_GET['type'])) {
	if ($_GET['type'] == 'two-factor') {
		$br['lang']['confirm_your_account'] = 'Two-factor authentication';
		$br['lang']['sign_in_attempt'] = 'To log in, you need to verify your identity.';
		if ($br['config']['two_factor_type'] == 'both') {
			$br['lang']['we_have_sent_you_code'] = 'We have sent you the confirmation code to your phone and to your email address.';
		} else if ($br['config']['two_factor_type'] == 'email') {
			$br['lang']['we_have_sent_you_code'] =  'We have sent you the confirmation code to your email address.';
		} else if ($br['config']['two_factor_type'] == 'phone') {
			$br['lang']['we_have_sent_you_code'] = 'We have sent you the confirmation code to your phone.';
		}
	} else {
		header("Location: " . Br_SeoLink('index.php?link1=welcome'));
        exit();
	}
}
$br['content']     = Br_LoadPage('welcome/unusual-login');