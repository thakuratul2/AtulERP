<?php
$response_data = array(
    'api_status' => 400,
);
if (!empty(Br_GetUserFromSessionID($_GET['access_token']))) {
	$cookie = Br_Secure($_GET['access_token']);
	$_SESSION['user_id'] = $cookie;
	setcookie("user_id", $cookie, time() + (10 * 365 * 24 * 60 * 60));
	header("Location: " . Br_SeoLink('index.php?link1=get_news_feed'));
	exit();
}