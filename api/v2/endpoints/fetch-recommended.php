<?php

$response_data = array(
    'api_status' => 400,
);

$required_fields =  array(
                        'users',
                        'groups',
                        'pages'
                    );
$limit = (!empty($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] > 0 && $_POST['limit'] <= 50 ? Br_Secure($_POST['limit']) : 5);
if (!empty($_POST['type']) && in_array($_POST['type'], $required_fields)) {
    if ($_POST['type'] == 'users') {
    	$users = Br_UserSug($limit);
        foreach ($users as $key => $user) {
            foreach ($non_allowed as $key => $value) {
               unset($users[$key][$value]);
            }
        }
    	$response_data = array(
                                'api_status' => 200,
                                'data' => $users
                            );
    }
    elseif ($_POST['type'] == 'groups') {
        $groups = Br_GroupSug($limit);
        foreach ($groups as $key => $group) {
            $groups[$key]['members'] = Br_CountGroupMembers($group['id']);
            $groups[$key]['is_joined'] = Br_IsGroupJoined($group['id']);
            $groups[$key]['is_owner'] = Br_IsGroupOnwer($group['id']);
        }
        $response_data = array(
                                'api_status' => 200,
                                'data' => $groups
                            );
    }
    elseif ($_POST['type'] == 'pages') {
        $pages = Br_PageSug($limit);
        foreach ($pages as $key => $page) {
            $pages[$key]['likes'] = Br_CountPageLikes($page['page_id']);
            $pages[$key]['is_liked'] = Br_IsPageLiked($page['page_id'], $br['user']['id']);
        }
        $response_data = array(
                                'api_status' => 200,
                                'data' => $pages
                            );
    }
}
else{
    $error_code    = 4;
    $error_message = 'type can not be empty';
}
