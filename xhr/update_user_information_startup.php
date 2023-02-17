<?php
if ($f == 'update_user_information_startup' && Br_CheckSession($hash_id) === true) {
    if (isset($_POST['user_id']) && is_numeric($_POST['user_id']) && $_POST['user_id'] > 0) {

        $Userdata = Br_UserData($_POST['user_id']);
        if (!empty($Userdata['uid'])) {
            if ($s == 'update_basic_setting') {
                if (empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['phone_number'])) {
                    $data = array(
                        'status' => 400,
                        'message' => 'Please check details'
                    );
                } else {
                    $Update_data = array(
                        'fname' => $_POST['fname'],
                        'lname' => $_POST['lname'],
                        'phone_number' => $_POST['phone_number'],
                        'about' => $_POST['about']
                    );
                    if (Br_UpdateUserData($_POST['user_id'], $Update_data)) {
                        $data = array(
                            'status' => 200,
                            'message' => 'Profile Details Updated'
                        );
                    }
                }

            } else if ($s == 'update_info_setting') {
                $age_data = '00-00-0000';
                if (!empty($_POST['birthday']) && preg_match('@^\s*(3[01]|[12][0-9]|0?[1-9])\-(1[012]|0?[1-9])\-((?:19|20)\d{2})\s*$@', $_POST['birthday'])) {
                    $newDate = date("Y-m-d", strtotime($_POST['birthday']));
                    $age_data = $newDate;
                } else {
                    $age_data = $_POST['birthday'];
                    $data = array(
                        'status' => 300,
                        'message' => 'Please choose correct date'
                    );
                }
                if (empty($_POST['gender'])) {
                    $data = array(
                        'status' => 300,
                        'message' => 'Please check details'
                    );
                } else {
                    $Update_data = array(
                        'gender' => $_POST['gender'],
                        'address' => $_POST['address'],
                        'city' => $_POST['city'],
                        'state' => $_POST['state'],
                        'pin_code' => $_POST['pin_code'],
                        'college_name' => $_POST['college_name'],
                        'department' => $_POST['department'],
                        'aadhar_no' => $_POST['aadhar_no'],
                        'birthday' => $age_data
                    );
                    if (Br_UpdateUserData($_POST['user_id'], $Update_data)) {
                        $data = array(
                            'status' => 200,
                            'message' => 'Profile Details Updated'
                        );
                    }
                }

            } else if ($s == 'update_more_setting') {
                $Update_data = array(
                    'portfolio' => $_POST['portfolio'],
                    'githubprofile' => $_POST['githubprofile'],
                    'instagramprofile' => $_POST['instagramprofile']
                );
                if (Br_UpdateUserData($_POST['user_id'], $Update_data)) {
                    $data = array(
                        'status' => 200,
                        'message' => 'Profile Details Updated'
                    );
                }

            } else {
                $data = array(
                    'status' => 400,
                    'message' => 'Invalid Request'
                );
            }
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}