<?php
$blocked_users = Br_GetBlockedMembers($br['user']['user_id']);

$users = array();

foreach ($blocked_users as $key => $blocked_user) {
	foreach ($non_allowed as $key => $value) {
	   unset($blocked_user[$value]);
	}
	$blocked_user['gender_text']        = ($blocked_user['gender'] == 'male') ? $br['lang']['male'] : $br['lang']['female'];
	$blocked_user['lastseen_time_text'] = Br_Time_Elapsed_String($blocked_user['lastseen']);
	$users[] = $blocked_user;
}

$response_data = array(
    'api_status' => 200,
    'blocked_users' => $users
);