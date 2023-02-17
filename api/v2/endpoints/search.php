<?php

$users = array();
$pages = array();
$groups = array();

$options['limit'] = (!empty($_POST['limit'])) ? (int) $_POST['limit'] : 35;
$options['query'] = (!empty($_POST['search_key'])) ? Br_Secure($_POST['search_key']) : '';
$options['gender'] = (!empty($_POST['gender'])) ? Br_Secure($_POST['gender']) : '';
$options['status'] = (!empty($_POST['status'])) ?  Br_Secure($_POST['status']) : '';
$options['image'] = (!empty($_POST['image'])) ?  Br_Secure($_POST['image']) : '';
$options['country'] = (!empty($_POST['country'])) ?  Br_Secure($_POST['country']) : '';
$options['verified'] = (!empty($_POST['verified'])) ?  Br_Secure($_POST['verified']) : '';
$options['filterbyage'] = (!empty($_POST['filterbyage'])) ?  Br_Secure($_POST['filterbyage']) : '';
$options['age_from'] = (!empty($_POST['age_from'])) ?  Br_Secure($_POST['age_from']) : '';
$options['age_to'] = (!empty($_POST['age_to'])) ?  Br_Secure($_POST['age_to']) : '';

$user_offset = (!empty($_POST['user_offset'])) ? (int) $_POST['user_offset'] : 0;
$page_offset = (!empty($_POST['page_offset'])) ? (int) $_POST['page_offset'] : 0;
$group_offset = (!empty($_POST['group_offset'])) ? (int) $_POST['group_offset'] : 0;

$get_users = Br_GetSearchFilter($options, $options['limit'], $user_offset);

foreach ($get_users as $key => $user) {
    foreach ($non_allowed as $key => $value) {
       unset($user[$value]);
    }
    $user['is_following'] = (Br_IsFollowing($user['user_id'], $br['user']['user_id'])) ? 1 : 0;
    $users[] = $user;
}

$search_query = Br_GetSearchAdv($options['query'], 'pages', $page_offset);
if (count($search_query) > 0) {
    foreach ($search_query as $br['result']) {
        $br['result']['is_liked'] = (Br_IsPageLiked($br['result']['id'], $br['user']['user_id'])) ? 'yes' : 'no';
        $pages[] = $br['result'];
    }
}
$search_query2 = Br_GetSearchAdv($options['query'], 'groups', $group_offset);
if (count($search_query2) > 0) {
    foreach ($search_query2 as $br['result']) {
        $br['result']['is_joined'] = (Br_IsGroupJoined($br['result']['id'], $br['user']['user_id'])) ? 'yes' : 'no';
        $groups[] = $br['result'];
    }
}

$response_data = array(
    'api_status' => 200,
    'users' => $users,
    'pages' => $pages,
    'groups' => $groups
);