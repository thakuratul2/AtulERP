<?php

$response_data = array(
    'api_status' => 400,
);
if (empty($_POST['user_id'])) {
    $error_code    = 3;
    $error_message = 'user_id (POST) is missing';
}
if (empty($_POST['fetch'])) {
    $error_code    = 3;
    $error_message = 'fetch (POST) is missing';
}
if (empty($error_code)) {
    $recipient_id   = Br_Secure($_POST['user_id']);
    $logged_user_id  = $br['user']['user_id'];
    $recipient_data = Br_UserData($recipient_id);
    if (empty($recipient_data)) {
        $error_code    = 6;
        $error_message = 'Recipient user not found';
    } else {
    	$response_data = array('api_status' => 200);
		$recipient_data_ = Br_UpdateUserDetails($recipient_data, true, true, true);
        if (is_array($recipient_data_)) {
            $recipient_data = $recipient_data_;
        }
        foreach ($non_allowed as $key => $value) {
           unset($recipient_data[$value]);
        }
	    $fetch = explode(',', $_POST['fetch']);
		$data = array();
		foreach ($fetch as $key => $value) {
			$data[$value] = $value;
		}
		if (!empty($data['user_data'])) {
			$recipient_data['is_following'] = 0;
	        $recipient_data['can_follow'] = 0;
	        if (Br_IsFollowing($recipient_id, $logged_user_id)) {
	            $recipient_data['is_following'] = 1;
	            $recipient_data['can_follow'] = 1;
	        } else {
	            if (Br_IsFollowRequested($recipient_id, $logged_user_id)) {
	                $recipient_data['is_following'] = 2;
	                $recipient_data['can_follow'] = 1;
	            } else {
	                if ($recipient_data['follow_privacy'] == 1) {
	                    if (Br_IsFollowing($logged_user_id, $recipient_id)) {
	                        $recipient_data['is_following'] = 0;
	                        $recipient_data['can_follow'] = 1;
	                    }
	                } else if ($recipient_data['follow_privacy'] == 0) {
	                    $recipient_data['can_follow'] = 1;
	                }
	            }
	        }
	        $recipient_data['is_following_me'] = (Br_IsFollowing( $br['user']['user_id'], $recipient_data['user_id'])) ? 1 : 0;
	        $recipient_data['gender_text']        = ($recipient_data['gender'] == 'male') ? $br['lang']['male'] : $br['lang']['female'];
        	$recipient_data['lastseen_time_text'] = Br_Time_Elapsed_String($recipient_data['lastseen']);
        	$recipient_data['is_blocked']         = Br_IsBlocked($recipient_data['user_id']);
        	$response_data['user_data'] = $recipient_data;
		}

		if (!empty($data['followers'])) {
			$followers_latest = array();
			$followers = Br_GetFollowers($recipient_data['user_id'], 'profile', 50);
			foreach ($followers as $key => $follower) {
				$follower['is_following'] = (Br_IsFollowing($follower['user_id'], $br['user']['user_id'])) ? 1 : 0;
				$followers_latest[] = $follower;
			}
			$response_data['followers'] = $followers_latest;
		}
		if (!empty($data['following'])) {
			$followings_latest = array();
			$followings = Br_GetFollowing($recipient_data['user_id'], 'profile', 50);
			foreach ($followings as $key => $following) {
				$following['is_following'] = (Br_IsFollowing($following['user_id'], $br['user']['user_id'])) ? 1 : 0;
				$followings_latest[] = $following;
			}
			$response_data['following'] = $followings_latest;
		}
		if (!empty($data['liked_pages'])) {
			$response_data['liked_pages'] = Br_GetLikes($recipient_data['user_id'], 'profile', 50);
			foreach ($response_data['liked_pages'] as $key => $value) {
                $response_data['liked_pages'][$key]['is_liked'] = Br_IsPageLiked($value['page_id'], $br['user']['id']);
            }
		}
		if (!empty($data['joined_groups'])) {
			$response_data['joined_groups'] = Br_GetUsersGroups($recipient_data['user_id'], 50);
			foreach ($response_data['joined_groups'] as $key => $value) {
                $response_data['joined_groups'][$key]['is_joined'] = Br_IsGroupJoined($value['group_id'], $br['user']['id']);
            }
		}
		if (!empty($data['family'])) {
			$family = Br_GetFamaly($recipient_data['user_id'],false,1);
			foreach ($family as $key => $value) {
				foreach ($non_allowed as $key1 => $value) {
			       unset($family[$key]['user_data'][$value]);
			    }
			}
			$response_data['family'] = $family;
		}
    }
}