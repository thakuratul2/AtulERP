<?php
$response_data = array(
    'api_status' => 400
);

if (empty($_POST['fetch'])) {
    $error_code    = 3;
    $error_message = 'fetch (POST) is missing';
}

$user_id = $br['user']['user_id'];

// if (!empty($_POST['device_id'])) {
//     $device_id  = Br_Secure($_POST['device_id']);
//     $update  = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `device_id` = '{$device_id}' WHERE `user_id` = '{$user_id}'");
// }
if (!empty($_POST['android_m_device_id'])) {
    $device_id  = Br_Secure($_POST['android_m_device_id']);
    $update  = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `android_m_device_id` = '{$device_id}' WHERE `user_id` = '{$user_id}'");
}
if (!empty($_POST['ios_m_device_id'])) {
    $device_id  = Br_Secure($_POST['ios_m_device_id']);
    $update  = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `ios_m_device_id` = '{$device_id}' WHERE `user_id` = '{$user_id}'");
}
if (!empty($_POST['android_n_device_id'])) {
    $device_id  = Br_Secure($_POST['android_n_device_id']);
    $update  = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `android_n_device_id` = '{$device_id}' WHERE `user_id` = '{$user_id}'");
}
if (!empty($_POST['ios_n_device_id'])) {
    $device_id  = Br_Secure($_POST['ios_n_device_id']);
    $update  = mysqli_query($sqlConnect, "UPDATE " . T_USERS . " SET `ios_n_device_id` = '{$device_id}' WHERE `user_id` = '{$user_id}'");
}

if (empty($error_code)) {
    
    $response_data = array(
        'api_status' => 200
    );
    
    $fetch = explode(',', $_POST['fetch']);
    $data  = array();
    foreach ($fetch as $key => $value) {
        $data[$value] = $value;
    }
    if (empty($br['user']['timezone'])) {
        $br['user']['timezone'] = 'UTC';
    }
    $timezone      = new DateTimeZone($br['user']['timezone']);

    if (!empty($data['notifications'])) {
    	$final_notifications= array();
        $offset = (!empty($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] > 0 ? Br_Secure($_POST['offset']) : 0);
        $notifications = Br_GetNotifications(array(
            'remove_notification' => array(
                'requested_to_join_group',
                'interested_event',
                'going_event',
                'invited_event',
                'forum_reply',
                'admin_notification',
            ),
            'offset' => $offset
        ));
        
        foreach ($notifications as $notification) {
            $br['notification'] = $notification;
            if ($br['notification']['seen'] == 0 && !empty($_GET['seen'])) {
                $notification_ids[] = $br['notification']['id'];
            }
            $unread_class = '';
            if ($br['notification']['seen'] == 0) {
                $unread_class = ' unread';
            }
            $br['notification']['type_text'] = '';
            $br['notification']['icon']      = '';
            $notificationText                = $br['notification']['text'];
            if (isset($notificationText) && !empty($notificationText)) {
                $notificationText = '"' . $br['notification']['text'] . '"';
            }
            $notificationType2 = $br['notification']['type2'];
            if (isset($notificationType2) && !empty($notificationType2)) {
                if ($notificationType2 == 'post_image') {
                    $type2 = $br['lang']['photo_n_label'];
                } elseif ($notificationType2 == 'post_youtube' || $notificationType2 == 'post_video') {
                    $type2 = $br['lang']['video_n_label'];
                } elseif ($notificationType2 == 'post_file') {
                    $type2 = $br['lang']['file_n_label'];
                } elseif ($notificationType2 == 'post_vine') {
                    $type2 = $br['lang']['vine_n_label'];
                } elseif ($notificationType2 == 'post_soundFile') {
                    $type2 = $br['lang']['sound_n_label'];
                } elseif ($notificationType2 == 'post_avatar') {
                    $type2 = $br['lang']['avatar_n_label'];
                } elseif ($notificationType2 == 'post_cover') {
                    $type2 = $br['lang']['cover_n_label'];
                } else {
                    $type2 = '';
                }
            } else {
                $type2 = $br['lang']['post_n_label'];
            }
            $orginal_txt  = array(
                "{postType}",
                "{post}"
            );
            $replaced_txt = array(
                $type2,
                $notificationText
            );
            if (!empty($br['notification']['type'])) {
                if ($br['notification']['type'] == 'viewed_story') {
                    $br['notification']['type_text'] = $br['lang']['viewed_your_story'];
                    $br['notification']['url']       = $br['notification']['url']; 
                    $br['notification']['icon']     .= 'story';
                }
                if ($br['notification']['type'] == "reaction") {
                    if( $br['notification']['text'] == "post" ){
                        $br['notification']['type_text'] .= $br['lang']['reacted_to_your_post'];
                    }else if( $br['notification']['text'] == "comment" ){
                        $br['notification']['type_text'] .= $br['lang']['reacted_to_your_comment'];
                    }else if( $br['notification']['text'] == "replay" ){
                        $br['notification']['type_text'] .= $br['lang']['reacted_to_your_replay'];
                    }
                    $br['notification']['icon'] = strtolower($notificationType2);
                }
                if ($br['notification']['type'] == "following") {
                    $br['notification']['type_text'] .= $br['lang']['followed_you'];
                    $br['notification']['icon'] .= 'user-plus';
                }
                if ($br['notification']['type'] == 'comment_mention') {
                    $br['notification']['type_text'] .= $br['lang']['comment_mention'];
                    $br['notification']['icon'] .= 'at';
                }
                if ($br['notification']['type'] == 'post_mention') {
                    $br['notification']['type_text'] .= $br['lang']['post_mention'];
                    $br['notification']['icon'] .= 'at';
                }
                if ($br['notification']['type'] == 'liked_post') {
                    $br['notification']['type_text'] = str_replace($orginal_txt, $replaced_txt, $br['lang']['liked_post']);
                    $br['notification']['icon'] .= 'thumbs-up';
                }
                if ($br['notification']['type'] == 'wondered_post') {
                    $lang_type                       = ($br['config']['second_post_button'] == 'wonder') ? $br['lang']['wondered_post'] : $br['lang']['disliked_post'];
                    $br['notification']['type_text'] = str_replace($orginal_txt, $replaced_txt, $lang_type);
                    $br['notification']['icon'] .= $br['second_post_button_icon'];
                }
                if ($br['notification']['type'] == 'share_post') {
                    $br['notification']['type_text'] = str_replace($orginal_txt, $replaced_txt, $br['lang']['share_post']);
                    $br['notification']['icon'] .= 'share';
                }
                if ($br['notification']['type'] == 'comment') {
                    $br['notification']['type_text'] = str_replace($orginal_txt, $replaced_txt, $br['lang']['commented_on_post']);
                    $br['notification']['icon'] .= 'comment';
                }
                if ($br['notification']['type'] == 'comment_reply') {
                    $br['notification']['type_text'] = str_replace('{comment}', $br['notification']['text'], $br['lang']['replied_to_comment']);
                    $br['notification']['icon'] .= 'comment';
                }
                if ($br['notification']['type'] == 'comment_reply_mention') {
                    $br['notification']['type_text'] = str_replace('{comment}', $br['notification']['text'], $br['lang']['comment_reply_mention']);
                    $br['notification']['icon'] .= 'comment';
                }
                if ($br['notification']['type'] == 'also_replied') {
                    $br['notification']['type_text'] = str_replace('{comment}', $br['notification']['text'], $br['lang']['also_replied']);
                    $br['notification']['icon'] .= 'comment';
                }
                if ($br['notification']['type'] == 'liked_comment') {
                    $br['notification']['type_text'] = str_replace('{comment}', $br['notification']['text'], $br['lang']['liked_comment']);
                    $br['notification']['icon'] .= 'thumbs-up';
                }
                if ($br['notification']['type'] == 'wondered_comment') {
                    $lang_type                       = ($br['config']['second_post_button'] == 'wonder') ? $br['lang']['wondered_comment'] : $br['lang']['disliked_comment'];
                    $br['notification']['type_text'] = str_replace('{comment}', $br['notification']['text'], $lang_type);
                    $br['notification']['icon'] .= $br['second_post_button_icon'];
                }
                if ($br['notification']['type'] == 'liked_reply_comment') {
                    $br['notification']['type_text'] = str_replace('{comment}', $br['notification']['text'], $br['lang']['liked_reply_comment']);
                    $br['notification']['icon'] .= 'thumbs-up';
                }
                if ($br['notification']['type'] == 'wondered_reply_comment') {
                    $lang_type                       = ($br['config']['second_post_button'] == 'wonder') ? $br['lang']['wondered_reply_comment'] : $br['lang']['disliked_reply_comment'];
                    $br['notification']['type_text'] = str_replace('{comment}', $br['notification']['text'], $lang_type);
                    $br['notification']['icon'] .= $br['second_post_button_icon'];
                }
                if ($br['notification']['type'] == 'profile_wall_post') {
                    $br['notification']['type_text'] = $br['lang']['posted_on_timeline'];
                    $br['notification']['icon'] .= 'user';
                }
                if ($br['notification']['type'] == 'visited_profile') {
                    $br['notification']['type_text'] = $br['lang']['profile_visted'];
                    $br['notification']['icon'] .= 'eye';
                }
                if ($br['notification']['type'] == 'liked_page') {
                    $page                            = Br_PageData($br['notification']['page_id']);
                    $br['notification']['type_text'] = str_replace('{page_name}', $page['name'], $br['lang']['liked_page']);
                    $br['notification']['icon'] .= 'thumbs-up';
                }
                if ($br['notification']['type'] == 'joined_group') {
                    $group                           = Br_GroupData($br['notification']['group_id']);
                    $br['notification']['type_text'] = str_replace('{group_name}', $group['name'], $br['lang']['joined_group']);
                    $br['notification']['icon'] .= 'users';
                }
                if ($br['notification']['type'] == 'accepted_invite') {
                    $page_id                         = @end(explode('/', $br['notification']['url']));
                    $page                            = Br_PageData(Br_PageIdFromPagename($page_id));
                    $br['notification']['type_text'] = str_replace('{page_name}', $page['name'], $br['lang']['accepted_invited_page']);
                    $br['notification']['icon'] .= 'user-plus';
                }
                
                if ($br['notification']['type'] == 'invited_page') {
                    $page_id                         = @end(explode('/', $br['notification']['url']));
                    $page                            = Br_PageData(Br_PageIdFromPagename($page_id));
                    $br['notification']['type_text'] = str_replace('{page_name}', $page['name'], $br['lang']['invited_page']);
                    $br['notification']['icon'] .= 'user-plus';
                }
                if ($br['notification']['type'] == 'accepted_join_request') {
                    $group_id                        = @end(explode('/', $br['notification']['url']));
                    $group                           = Br_GroupData(Br_GroupIdFromGroupname($group_id));
                    $br['notification']['type_text'] = str_replace('{group_name}', $group['name'], $br['lang']['accepted_join_request']);
                    $br['notification']['icon'] .= 'user-plus';
                }
                if ($br['notification']['type'] == 'added_you_to_group') {
                    $group_id                        = @end(explode('/', $br['notification']['url']));
                    $group                           = Br_GroupData(Br_GroupIdFromGroupname($group_id));
                    $br['notification']['type_text'] = str_replace('{group_name}', $group['name'], $br['lang']['added_you_to_group']);
                    $br['notification']['icon'] .= 'user-plus';
                }
                if ($br['notification']['type'] == 'requested_to_join_group') {
                    $br['notification']['type_text'] = $br['lang']['requested_to_join_group'];
                    $br['notification']['icon'] .= 'user-plus';
                }
                if ($br['notification']['type'] == 'accepted_request') {
                    if ($br['config']['connectivitySystem'] == 1) {
                        $br['notification']['type_text'] = $br['lang']['accepted_friend_request'];
                    } else {
                        $br['notification']['type_text'] = $br['lang']['accepted_follow_request'];
                    }
                    $br['notification']['icon'] .= 'user-plus';
                }
                if ($br['notification']['type'] == 'interested_event') {
                    $event_data                      = Br_EventData($br['notification']['event_id']);
                    $br['notification']['type_text'] = str_replace('{event_name}', $event_data['name'], $br['lang']['is_interested']);
                    $br['notification']['icon'] .= 'eye';
                }
                if ($br['notification']['type'] == 'going_event') {
                    $event_data                      = Br_EventData($br['notification']['event_id']);
                    $br['notification']['type_text'] = str_replace('{event_name}', $event_data['name'], $br['lang']['is_going']);
                    $br['notification']['icon'] .= 'calendar-o';
                }
                if ($br['notification']['type'] == 'invited_event') {
                    $event_data                      = Br_EventData($br['notification']['event_id']);
                    $br['notification']['type_text'] = str_replace('{event_name}', $event_data['name'], $br['lang']['invited_you_event']);
                    $br['notification']['icon'] .= 'calendar-o';
                }
                if ($br['notification']['type'] == 'poke') {
                    $page                            = Br_PageData($br['notification']['page_id']);
                    $br['notification']['type_text'] = $br['lang']['poked_you'];
                    $br['notification']['icon'] .= 'thumbs-up';
                }
                if ($br['notification']['type'] == 'shared_your_post') {
                    $br['notification']['type_text'] = $br['lang']['shared_your_post'];
                    $br['notification']['icon'] .= 'share';
                }
                if ($br['notification']['type'] == 'shared_a_post_in_timeline') {
                    $br['notification']['type_text'] = $br['lang']['shared_a_post_in_timeline'];
                    $br['notification']['icon'] .= 'share';
                }
                // ************************ 
                if ($br['notification']['type'] == "gift") {
                    $br['notification']['type_text'] .= $br['lang']['send_gift_to_you'];
                    $br['notification']['icon'] .= 'gift';
                }
                if ($br['notification']['type'] == 'declined_group_chat_request') {
                    $br['notification']['type_text'] = $br['lang']['declined_group_chat_request'];
                    $br['notification']['icon'] .= 'declined_group_chat_request';
                }
                if ($br['notification']['type'] == 'accept_group_chat_request') {
                    $br['notification']['type_text'] = $br['lang']['accept_group_chat_request'];
                    $br['notification']['icon'] .= 'accept_group_chat_request';
                }
                if ($br['notification']['type'] == 'apply_job') {
                    $br['notification']['type_text'] = $br['lang']['apply_your_job'];
                    $br['notification']['icon'] .= 'apply_job';
                }
                if ($br['notification']['type'] == 'fund_donate') {
                    $br['notification']['type_text'] = $br['lang']['donated_to'];
                    $br['notification']['icon'] .= 'fund_donate';
                }
                if ($br['notification']['type'] == 'page_admin') {
                    $br['notification']['type_text'] = $br['lang']['added_page_admin'];
                    $br['notification']['icon']     .= 'page_admin';
                }
                if ($br['notification']['type'] == 'group_admin') {
                    $br['notification']['type_text'] = $br['lang']['added_group_admin'];
                    $br['notification']['icon']     .= 'group_admin';
                }
                if ($br['notification']['type'] == 'added_u_as') {
                    $br['notification']['type_text'] = $br['notification']['text'];
                    $br['notification']['icon']     .= 'added_u_as';
                }
                if ($br['notification']['type'] == 'accept_u_as') {
                    $br['notification']['type_text'] = $br['notification']['text'];
                    $br['notification']['icon']     .= 'accept_u_as';
                }
                if ($br['notification']['type'] == 'rejected_u_as') {
                    $br['notification']['type_text'] = $br['notification']['text'];
                    $br['notification']['icon']     .= 'rejected_u_as';
                }
                if ($br['notification']['type'] == 'sent_u_money') {
                    $br['notification']['type_text'] = $br['notification']['text'];
                    $br['notification']['icon']     .= 'sent_u_money';
                }
                if ($br['notification']['type'] == 'blog_commented') {
                    $br['notification']['type_text'] = $br['lang']['commented_on_blog'];
                    $br['notification']['icon'] .= 'blog_commented';
                }
                if ($br['notification']['type'] == 'bank_pro') {
                    $br['notification']['type_text'] = $br['lang']['bank_pro'];
                    $br['notification']['icon'] .= 'bank_pro';
                }
                if ($br['notification']['type'] == 'bank_wallet') {
                    $br['notification']['type_text'] = $br['lang']['bank_pro'];
                    $br['notification']['icon'] .= 'bank_wallet';
                }
                if ($br['notification']['type'] == 'bank_decline') {
                    $br['notification']['type_text'] = $br['lang']['bank_decline'];
                    $br['notification']['icon'] .= 'bank_decline';
                }
                if ($br['notification']['type'] == 'live_video') {
                    $br['notification']['type_text'] = $br['lang']['started_live_video'];
                    $br['notification']['icon'] .= 'live_video';
                }
                if ($br['notification']['type'] == 'forum_reply') {
                    $br['notification']['type_text'] = $br['lang']['replied_to_topic'];
                    $br['notification']['icon'] .= 'forum_reply';
                }
                if ($br['notification']['type'] == 'memory') {
                    $br['notification']['type_text'] = $br['lang']['memory_this_day'];
                    $br['notification']['icon'] .= 'memory';
                }
                if ($br['notification']['type'] == 'thread_reply') {
                    $br['notification']['type_text'] = $br['lang']['thread_reply'];
                    $br['notification']['icon'] .= 'thread_reply';
                }
                if ($br['notification']['type'] == 'remaining') {
                    $br['notification']['type_text'] = $br['notification']['text'];
                    $br['notification']['icon'] .= 'remaining';
                }
                if ($br['notification']['type'] == 'new_post') {
                    $br['notification']['type_text'] = $br['lang']['created_new_post'];
                    $br['notification']['icon'] .= 'new_post';
                }
                if ($br['notification']['type2'] == 'anonymous') {
                    $br['notification']['notifier']['name']   = $br['lang']['anonymous']; 
                    $br['notification']['notifier']['avatar'] = Br_GetMedia('upload/photos/incognito.png');
                }
                // ************************ 
            }
            $br['notification']['time_text_string'] = Br_Time_Elapsed_String($br['notification']['time']);
            $br['notification']['time_text']        = Br_Time_Elapsed_String($br['notification']['time']);
            if (!empty($br['notification']['time'])) {
                $time_today = time() - 86400;
                if ($br['notification']['time'] < $time_today) {
                    $br['notification']['time_text'] = date('m.d.y', $br['notification']['time']);
                } else {
                    $time = new DateTime('now', $timezone);
                    $time->setTimestamp($br['notification']['time']);
                    $br['notification']['time_text'] = $time->format('H:i');
                }
            }
            if (!empty($notification_ids)) {
                $query_where = '\'' . implode('\', \'', $notification_ids) . '\'';
                $query       = "UPDATE " . T_NOTIFICATION . " SET `seen` = " . time() . " WHERE `id` IN ($query_where)";
                $sql_query   = mysqli_query($sqlConnect, $query);
            }
            foreach ($non_allowed as $key => $value) {
                unset($br['notification']['notifier'][$value]);
            }
            if (!empty($br['notification']['event_id'])) {
                $event = Br_EventData($br['notification']['event_id']);

                foreach ($non_allowed as $key => $value) {
                   unset($event['user_data'][$value]);
                }
                $event['is_going'] = Br_EventGoingExists($event['id']);
                $event['is_interested'] = Br_EventInterestedExists($event['id']);
                $event['going_count'] = Br_TotalGoingUsers($event['id']);
                $event['interested_count'] = Br_TotalInterestedUsers($event['id']);
                $event['start_date'] = date($br['config']['date_style'], strtotime($event['start_date']));
                $event['end_date'] = date($br['config']['date_style'], strtotime($event['end_date']));
                $br['notification']['event'] = $event;
            }
            array_push($final_notifications, $br['notification']);
        }
        $count_notifications = Br_CountNotifications(array(
            'unread' => true,
            'remove_notification' => array('requested_to_join_group', 'interested_event', 'going_event', 'invited_event', 'forum_reply', 'admin_notification')
        ));
        $response_data['notifications'] = $final_notifications;
        $response_data['new_notifications_count'] = $count_notifications;
    }
    
    if (!empty($data['friend_requests'])) {
    	$final_friend_requests = array();
    	$friend_requests = Br_GetFollowRequests();
    	if (!empty($friend_requests)) {
            foreach ($friend_requests as $key => $friend_request) {
                foreach ($non_allowed as $key => $value) {
                   unset($friend_request[$value]);
                }
                $final_friend_requests[] = $friend_request;
            }
        }
        $response_data['friend_requests'] = $final_friend_requests;
        $response_data['new_friend_requests_count'] = Br_CountFollowRequests();
    }

    if (!empty($data['group_chat_requests'])) {
        $final_group_chat_requests = array();
        $group_chat_requests = GetGroupChatRequests();
        if (!empty($group_chat_requests)) {
            foreach ($group_chat_requests as $key => $group_chat_request) {
                foreach ($non_allowed as $key => $value) {
                   unset($group_chat_request->{$value});
                }
                $group_chat_request->group_tab = Br_GroupTabData($group_chat_request->group_id,false);
                unset($group_chat_request->group_tab['messages']);
                $group_chat_request->group_tab['avatar'] = $group_chat_request->group_tab['avatar'];
                $group_chat_request->group_tab['time_text'] = Br_Time_Elapsed_String($group_chat_request->group_tab['time']);
                $final_group_chat_requests[] = $group_chat_request;
            }
        }
        $response_data['group_chat_requests'] = $final_group_chat_requests;
        $response_data['new_group_chat_requests_count'] = Br_CountGroupChatRequests();
    }

    if (!empty($data['pro_users'])) {
    	$final_pro_users = array();
    	$pro_users = Br_FeaturedUsers(9);
    	if (!empty($pro_users)) {
            foreach ($pro_users as $key => $pro_user) {
                foreach ($non_allowed as $key => $value) {
                   unset($pro_user[$value]);
                }
                $final_pro_users[] = $pro_user;
            }
        }
        $response_data['pro_users'] = $final_pro_users;
    }
    if (!empty($_POST['SetOnline']) && $_POST['SetOnline'] == 1) {
        Br_UpdateUserData($br['user']['user_id'], array('lastseen' => time()));
    }

    if (!empty($data['promoted_pages'])) {
        $response_data['promoted_pages'] = Br_GetPromotedPage();
    }

    if (!empty($data['trending_hashtag'])) {
        $response_data['trending_hashtag'] = Wa_GetTrendingHashs('popular');
    }

    if (!empty($data['count_new_messages'])) {
        $response_data['count_new_messages'] =  Br_CountMessages(array('new' => true), 'interval');
    }
    if (!empty($data['announcement'])) {
        $response_data['announcement'] =  Br_GetHomeAnnouncements();
        if (!empty($response_data['announcement'])) {
            $response_data['announcement']['text_decode'] = strip_tags($response_data['announcement']['text']);
            $response_data['announcement']['time_text'] = Br_Time_Elapsed_String($response_data['announcement']['time']);
        } 
    }
}