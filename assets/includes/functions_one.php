<?php
/* Script Main Functions (File 1) */

define("T_TERMS", "terms");
define("T_CONFIG", "config");
define("T_USERS", "users");
define("T_APP_SESSIONS", "sessions");
define("T_CUSTOM_PAGES", "cpages");
define("T_ROLES", "roles");
define("T_PROJECTS", "projects");
define("T_PROJECTS_A", "projects_assigned");
define("T_A_REQUESTS", "affiliate");
define("T_EMAILS", "emails");
define("T_ANNOUNCEMENT", "announcement");
define("T_ANNOUNCEMENT_VIEWS", "announcement_views");
define("T_BANNED_IPS", "banned_ips");
define("T_BAD_LOGIN", "bad_login");
define("T_POSTS", "user_posts");

require_once('app_start.php');

function Br_GetTerms()
{
    global $sqlConnect;
    $data = array();
    $query = mysqli_query($sqlConnect, "SELECT * FROM " . T_TERMS);
    while ($fetched_data = mysqli_fetch_assoc($query)) {
        $data[$fetched_data['type']] = $fetched_data['text'];
    }
    return $data;
}

function Br_SaveTerm($update_name, $value)
{
    global $br, $config, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    $update_name = Br_Secure($update_name);
    $value = mysqli_real_escape_string($sqlConnect, $value);
    $query_one = " UPDATE " . T_TERMS . " SET `text` = '{$value}' WHERE `type` = '{$update_name}'";
    $query = mysqli_query($sqlConnect, $query_one);
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_GetConfig()
{
    global $sqlConnect;
    $data = array();
    $query = mysqli_query($sqlConnect, "SELECT * FROM " . T_CONFIG);
    while ($fetched_data = mysqli_fetch_assoc($query)) {
        $data[$fetched_data['name']] = $fetched_data['value'];
    }
    return $data;
}


function Br_SaveConfig($update_name, $value)
{
    global $br, $config, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (!array_key_exists($update_name, $config)) {
        return false;
    }
    $update_name = Br_Secure($update_name);
    $value = mysqli_real_escape_string($sqlConnect, $value);
    $query_one = " UPDATE " . T_CONFIG . " SET `value` = '{$value}' WHERE `name` = '{$update_name}'";
    $query = mysqli_query($sqlConnect, $query_one);
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_GetUserFromSessionID($session_id)
{
    global $sqlConnect, $db;
    if (empty($session_id)) {
        return false;
    }
    $session_id = Br_Secure($session_id);
    $query = mysqli_query($sqlConnect, "SELECT * FROM " . T_APP_SESSIONS . " WHERE `session_id` = '{$session_id}' LIMIT 1");
    if ($query) {
        $fetched_data = mysqli_fetch_assoc($query);
        if (!empty($fetched_data)) {
            return $fetched_data['uid'];
        }
        return -1;
    }
}

function Br_Login($email, $password)
{
    global $sqlConnect;
    if (empty($email) || empty($password)) {
        return false;
    }
    $username = Br_Secure($email);
    $login_password = '';
    $hash = 'md5';

    if ($hash == 'password_hash') {
        return false;
    } else {
        $login_password = Br_Secure(base64_encode($password));
    }

    $query = mysqli_query($sqlConnect, "SELECT COUNT(`uid`) FROM " . T_USERS . " WHERE (`email` = '{$username}' OR `phone_number` = '{$username}') AND `password` = '{$login_password}'");
    if (Br_Sql_Result($query, 0) == 1) {
        return true;
    }
    return false;
}


function Br_UserData($user_id, $password = true)
{
    global $br, $sqlConnect, $cache;
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $data = array();
    $user_id = Br_Secure($user_id);
    $query_one = "SELECT * FROM " . T_USERS . " WHERE `uid` = {$user_id}";


    $sql = mysqli_query($sqlConnect, $query_one);
    $fetched_data = mysqli_fetch_assoc($sql);

    if (empty($fetched_data)) {
        return array();
    }
    if ($password == false) {
        unset($fetched_data['password']);
    }
    $fetched_data['id'] = $fetched_data['uid'];
    $fetched_data['user_platform'] = "web";
    $fetched_data['type'] = 'user';
    $fetched_data['url'] = Br_SeoLink('index.php?link1=user&u=' . $fetched_data['email']);
    $fetched_data['name'] = '';
    if (!empty($fetched_data['fname'])) {
        if (!empty($fetched_data['last_name'])) {
            $fetched_data['name'] = $fetched_data['fname'] . ' ' . $fetched_data['lname'];
        } else {
            $fetched_data['name'] = $fetched_data['fname'];
        }
    } else {
        $fetched_data['name'] = $fetched_data['email'];
    }
    if (!empty($fetched_data['details'])) {
        $fetched_data['details'] = (array) json_decode($fetched_data['details']);
    }
    $fetched_data['lastseen_status'] = ($fetched_data['lastseen'] > (time() - 60)) ? 'on' : 'off';

    return $fetched_data;
}

function Br_IsUserCookie($user_id, $password)
{
    global $sqlConnect;
    if (empty($user_id) || empty($password)) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    $password = Br_Secure($password);
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `user_id` = '{$user_id}' AND `password` = '{$password}'");
    return (Br_Sql_Result($query, 0) == 1) ? true : false;
}
function Br_SetLoginWithSession($user_email)
{
    if (empty($user_email)) {
        return false;
    }
    $user_email = Br_Secure($user_email);
    //$_SESSION['user_id'] = Br_CreateLoginSession(Br_UserIdFromEmail($user_email));
}


function Br_UserExists($username)
{
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Br_Secure($username);
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `username` = '{$username}'");
    return (Br_Sql_Result($query, 0) == 1) ? true : false;
}
function Br_IsUserComplete($user_id)
{
    global $sqlConnect;
    if (empty($user_id)) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    // $query = mysqli_query($sqlConnect, "SELECT COUNT(`uid`) FROM " . T_USERS . " WHERE `uid` = '{$user_id}' AND `start_up` = '0'");
    // return (Br_Sql_Result($query, 0) == 1) ? true : false;
    return true;
}

function Br_IsUserNotCompleteBasicType($user_id)
{
    global $sqlConnect;
    if (empty($user_id)) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    $usr = Br_UserData($user_id);
    if (empty($usr['about'])) {
        return true;
    } else {
        return false;
    }
}
function Br_IsUserNotCompleteInfoType($user_id)
{
    global $sqlConnect;
    if (empty($user_id)) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    $usr = Br_UserData($user_id);
    if (empty($usr['gender']) || empty($usr['address']) || empty($usr['city']) || empty($usr['State']) || empty($usr['pin_code']) || empty($usr['birthday'])) {
        return true;
    } else {
        return false;
    }
}
function Br_IsUserNotCompleteMoreType($user_id)
{
    global $sqlConnect;
    if (empty($user_id)) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    //$usr = Br_UserData($user_id);
    // if (empty($usr['about'])) {
    //     return true;
    // }else{
    //     return false;
    // }
    return false;
}

function Br_UserIdFromUsername($username)
{
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Br_Secure($username);
    $query = mysqli_query($sqlConnect, "SELECT `uid` FROM " . T_USERS . " WHERE `username` = '{$username}'");
    return Br_Sql_Result($query, 0, 'user_id');
}
function Br_UserIdFromPhoneNumber($phone_number)
{
    global $sqlConnect;
    if (empty($phone_number)) {
        return false;
    }
    $phone_number = Br_Secure($phone_number);
    $query = mysqli_query($sqlConnect, "SELECT `user_id` FROM " . T_USERS . " WHERE `phone_number` = '{$phone_number}'");
    return Br_Sql_Result($query, 0, 'user_id');
}
function Br_UserNameFromPhoneNumber($phone_number)
{
    global $sqlConnect;
    if (empty($phone_number)) {
        return false;
    }
    $phone_number = Br_Secure($phone_number);
    $query = mysqli_query($sqlConnect, "SELECT `username` FROM " . T_USERS . " WHERE `phone_number` = '{$phone_number}'");
    return Br_Sql_Result($query, 0, 'username');
}
function Br_UserIdForLogin($username)
{
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Br_Secure($username);
    $query = mysqli_query($sqlConnect, "SELECT `uid` FROM " . T_USERS . " WHERE `email` = '{$username}' OR `phone_number` = '{$username}'");
    return Br_Sql_Result($query, 0, 'uid');
}
function Br_UserIdFromEmail($email)
{
    global $sqlConnect;
    if (empty($email)) {
        return false;
    }
    $email = Br_Secure($email);
    $query = mysqli_query($sqlConnect, "SELECT `uid` FROM " . T_USERS . " WHERE `email` = '{$email}'");
    return Br_Sql_Result($query, 0, 'uid');
}

function Br_CreateLoginSession($user_id = 0)
{
    global $sqlConnect, $db;
    if (empty($user_id)) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    $hash = sha1(rand(111, 999)) . md5(microtime());
    $query_two = mysqli_query($sqlConnect, "DELETE FROM " . T_APP_SESSIONS . " WHERE `session_id` = '{$hash}'");
    if ($query_two) {
        $ua = json_encode(getBrowser());
        $query_three = mysqli_query($sqlConnect, "INSERT INTO " . T_APP_SESSIONS . " (`uid`, `session_id`, `platform`, `platform_details`, `time`) VALUES('{$user_id}', '{$hash}', 'web', '$ua'," . time() . ")");
        if ($query_three) {
            return $hash;
        }
    }
}

function Br_SendSMSMessage_old($phone, $message)
{
    global $br;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($phone) || empty($message)) {
        return false;
    }
    $phone = Br_Secure($phone);
    $message = Br_Secure($message);

    $fields = array(
        "sender_id" => "FTWSMS",
        "message" => $message,
        "route" => "q",
        "flash" => 0,
        "language" => "english",
        "numbers" => $phone,
    );

    $curl = curl_init();

    curl_setopt_array(
        $curl,
        array(
        CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($fields),
        CURLOPT_HTTPHEADER => array(
                "authorization: " . $br['config']['fast2sms_authKey'],
                "accept: */*",
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        )
    );

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $resp = json_decode($response, true);
        if (isset($resp['return']) && $resp['return']) {
            return true;
        } else {
            echo $response;
        }
    }
}

function Br_SendSMSMessage($to, $message)
{
    global $br, $sqlConnect;
    if (empty($to)) {
        return false;
    }
    if ($br["config"]["sms_provider"] == "twilio" && !empty($br["config"]["sms_twilio_username"]) && !empty($br["config"]["sms_twilio_password"]) && !empty($br["config"]["sms_t_phone_number"])) {
        $account_sid = $br["config"]["sms_twilio_username"];
        $auth_token = $br["config"]["sms_twilio_password"];
        $to = Br_Secure($to);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/" . $account_sid . "/Messages");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "Body=" . $message . "&From=" . $br["config"]["sms_t_phone_number"] . "&To=" . $to);
        curl_setopt($ch, CURLOPT_USERPWD, $account_sid . ':' . $auth_token);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        if (!empty($result)) {
            $result = simplexml_load_string($result);
            if (!empty($result->Message) && !empty($result->Message->Status)) {
                return true;
            }
        }
        return false;
    } elseif ($br["config"]["sms_provider"] == "infobip" && !empty($br["config"]["infobip_api_key"]) && !empty($br["config"]["infobip_base_url"])) {

        $to = Br_Secure($to);
        if (empty($to)) {
            return false;
        }
        $sms = '{
                  "messages": [
                    {
                      "destinations": [
                        {
                          "to": "' . $to . '"
                        }
                      ],
                      "from": "' . $br["config"]["siteName"] . '",
                      "text": "' . $message . '"
                    }
                  ]
                }';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $br["config"]["infobip_base_url"] . '/sms/2/text/advanced');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sms);

        $headers = array();
        $headers[] = 'Authorization: App ' . $br["config"]["infobip_api_key"];
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $result = json_decode($result, true);
        if (!empty($result['messages'])) {
            return true;
        }
        return false;
    } elseif ($br["config"]["sms_provider"] == "bulksms" && !empty($br["config"]["sms_username"]) && !empty($br["config"]["sms_password"])) {
        if (empty($to)) {
            return false;
        }
        $to_ = @explode("+", $to);
        if (empty($to_[1])) {
            return false;
        }
        $messages = array(
            array('to' => $to, 'body' => $message)
        );

        $ch = curl_init();
        $url = 'https://api.bulksms.com/v1/messages?auto-unicode=true&longMessageMaxParts=30';
        $username = $br["config"]["sms_username"];
        $password = $br["config"]["sms_password"];
        $headers = array(
            'Content-Type:application/json',
            'Authorization:Basic ' . base64_encode("$username:$password")
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messages));
        // Allow cUrl functions 20 seconds to execute
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        // Wait 10 seconds while trying to connect
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $output = array();
        $output['server_response'] = curl_exec($ch);
        $curl_info = curl_getinfo($ch);
        $output['http_status'] = $curl_info['http_code'];
        $output['error'] = curl_error($ch);
        curl_close($ch);

        $result = $output;
        if ($result['http_status'] != 201) {
            return false;
        } else {
            return true;
        }
        
    } elseif ($br["config"]["sms_provider"] == "msg91" && !empty($br["config"]["msg91_authKey"])) {
        //Your authentication key
        $authKey = $br["config"]["msg91_authKey"];
        //Multiple mobiles numbers separated by comma
        $mobileNumber = $to;
        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = uniqid();
        //Define route
        $route = "4";
        //Prepare you post parameters
        $postData = array(
            "authkey" => $authKey,
            "mobiles" => $mobileNumber,
            "message" => $message,
            "sender" => $senderId,
            "route" => $route
        );
        if (!empty($br["config"]["msg91_dlt_id"])) {
            $postData["DLT_TE_ID"] = $br["config"]["msg91_dlt_id"];
        }
        //API URL
        $url = "http://api.msg91.com/api/sendhttp.php";
        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData
        )
        );
        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        //get response
        $output = curl_exec($ch);
        //Print error if any
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);
        return true;
    }
    return false;
}

function Br_upload_profile($file)
{
    global $br;

    $path = './upload/photos/';
    $targetDir = $path;
    $default = "defaultuser.png";
    $base_url = $br['config']['site_url'];
    // get the filename
    $filename = basename($file['name']);
    $filename = cleanString($filename);
    $filename = preg_replace('/\s+/', '', $filename);
    $filename = preg_replace('/-/i', '', $filename);
    $filename = Br_Secure($filename);
    $targetFilePath = $targetDir . $filename;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    if (!empty($filename)) {
        // allow certain file format
        $allowType = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowType)) {
            // upload file to the server
            if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                return $base_url . '/upload/photos/' . $filename;
            }
        }
    }
    return false;
}


function Br_IsBlocked($user_id)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $logged_user_id = Br_Secure($br['user']['user_id']);
    $user_id = Br_Secure($user_id);
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`id`) FROM " . T_USERS . " WHERE (`blocker` = {$logged_user_id} AND `blocked` = {$user_id}) OR (`blocker` = {$user_id} AND `blocked` = {$logged_user_id})");
    return (Br_Sql_Result($query, 0) == 1) ? true : false;
}
function Br_RegisterBlock($user_id)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $logged_user_id = Br_Secure($br['user']['user_id']);
    $user_id = Br_Secure($user_id);
    $query = mysqli_query($sqlConnect, "INSERT INTO " . T_USERS . " (`blocker`, `blocked`) VALUES ('{$logged_user_id}', '{$user_id}')");
    return ($query) ? true : false;
}
function Br_RemoveBlock($user_id)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $logged_user_id = Br_Secure($br['user']['user_id']);
    $user_id = Br_Secure($user_id);
    $query = mysqli_query($sqlConnect, "DELETE FROM " . T_USERS . " WHERE `blocker` = '{$logged_user_id}' AND `blocked` = '{$user_id}'");
    return ($query) ? true : false;
}
function Br_GetBlockedMembers($user_id = 0)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $data = array();
    $logged_user_id = Br_Secure($br['user']['user_id']);
    $user_id = Br_Secure($user_id);
    $query = mysqli_query($sqlConnect, "SELECT `blocked` FROM " . T_USERS . " WHERE `blocker` = '{$user_id}'");
    while ($fetched_data = mysqli_fetch_assoc($query)) {
        $data[] = Br_UserData($fetched_data['blocked']);
    }
    return $data;
}
function Br_EmailExists($email)
{
    global $sqlConnect;
    if (empty($email)) {
        return false;
    }
    $email = Br_Secure($email);
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`uid`) FROM " . T_USERS . " WHERE `email` = '{$email}'");
    return (Br_Sql_Result($query, 0) == 1) ? true : false;
}
function Br_PhoneExists($phone)
{
    global $sqlConnect;
    if (empty($phone)) {
        return false;
    }
    $phone = Br_Secure($phone);
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`uid`) FROM " . T_USERS . " WHERE `phone_number` = '{$phone}'");
    return (Br_Sql_Result($query, 0) > 1) ? true : false;
}
function Br_IsOnwerUser($user_id)
{
    global $br;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    $logged_user_id = Br_Secure($br['user']['uid']);
    if ($user_id == $logged_user_id) {
        return true;
    } else {
        return false;
    }
}
function Br_IsOnwer($user_id)
{
    global $br;

    if ($br['loggedin'] == false) {
        return false;
    }

    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }

    $user_id = Br_Secure($user_id);
    $logged_user_id = Br_Secure($br['user']['uid']);

    if (Br_IsAdmin($logged_user_id) === false) {
        if ($user_id == $logged_user_id) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function Br_addAdmin($user_id, $level)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    $query = mysqli_query($sqlConnect, " UPDATE " . T_USERS . " SET `admin` = " . $level . " WHERE `uid` = {$user_id}");
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_DeleteRole($user_id)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    $q = "DELETE FROM " . T_ROLES . " WHERE `id` = {$user_id}";
    $query = mysqli_query($sqlConnect, $q);
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_DeleteProject($user_id)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    $q = "DELETE FROM " . T_PROJECTS . " WHERE `id` = {$user_id}";
    $query = mysqli_query($sqlConnect, $q);
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_DeleteUser($user_id)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    $q = "DELETE FROM " . T_USERS . " WHERE `uid` = {$user_id}";
    $query = mysqli_query($sqlConnect, $q);
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_addProject($name, $valu, $priority, $description, $registered)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($valu) || !is_numeric($valu) || $valu < 0) {
        return false;
    }
    $valu = Br_Secure($valu);
    $name = Br_Secure($name);
    $q = " INSERT INTO " . T_PROJECTS . " (name, price, description, priority, registered) VALUES ('{$name}', '{$valu}', '{$description}', '{$priority}', '{$registered}')";
    $query = mysqli_query($sqlConnect, $q);
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_editProject($id, $name, $valu, $priority, $description, $status)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($valu) || !is_numeric($valu) || $valu < 0) {
        return false;
    }
    $valu = Br_Secure($valu);
    $name = Br_Secure($name);
    $q = " UPDATE " . T_PROJECTS . " SET `name` = '{$name}', `price` = '{$valu}', `description` = '{$description}', `status` = '{$status}', `priority` = '{$priority}' WHERE `id` = {$id}";
    $query = mysqli_query($sqlConnect, $q);
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_addTeamRole($name, $priority, $description)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($priority) || !is_numeric($priority) || $priority < 0) {
        return false;
    }
    $priority = Br_Secure($priority);
    $name = Br_Secure($name);
    $q = " INSERT INTO " . T_ROLES . " (name, description, priority) VALUES ('{$name}', '{$description}', '{$priority}')";
    $query = mysqli_query($sqlConnect, $q);
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_editTeamRole($id, $name, $priority, $description)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($id) || !is_numeric($id) || $id < 0) {
        return false;
    }
    $priority = Br_Secure($priority);
    $name = Br_Secure($name);
    $q = " UPDATE " . T_ROLES . " SET `name` = '{$name}', `description` = '{$description}', `priority` = '{$priority}' WHERE `id` = {$id}";
    $query = mysqli_query($sqlConnect, $q);
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_assignPost($post_id, $uid)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($post_id) || !is_numeric($post_id) || $post_id < 0) {
        return false;
    }
    $post_id = Br_Secure($post_id);
    $uid = Br_Secure($uid);
    $q = "INSERT INTO " . T_POSTS . " (`pid`, `uid`) VALUES ('{$post_id}', '{$uid}')";
    $query = mysqli_query($sqlConnect, $q);
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_deletePost($uid)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($uid) || !is_numeric($uid) || $uid < 0) {
        return false;
    }
    $uid = Br_Secure($uid);
    $q = "DELETE from " . T_POSTS . "where `uid` = '{$uid}')";
    $query = mysqli_query($sqlConnect, $q);
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_assignProject($post_id, $uid)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($post_id) || !is_numeric($post_id) || $post_id < 0) {
        return false;
    }
    $post_id = Br_Secure($post_id);
    $uid = Br_Secure($uid);
    $q = "INSERT INTO " . T_PROJECTS_A . " (`pid`, `uid`) VALUES ('{$post_id}', '{$uid}')";
    $query = mysqli_query($sqlConnect, $q);
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_getAdminType($username)
{
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Br_Secure($username);
    $query = mysqli_query($sqlConnect, "SELECT * FROM " . T_USERS . "  WHERE (`email` = '{$username}' OR `phone_number` = '{$username}')");
    $fetched_data = mysqli_fetch_assoc($query);
    return $fetched_data['admin'];
}

function Br_LastSeen($user_id, $type = '')
{
    global $br, $sqlConnect, $cache;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    $query = mysqli_query($sqlConnect, " UPDATE " . T_USERS . " SET `lastseen` = " . time() . " WHERE `uid` = {$user_id}");
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_RegisterUser($registration_data)
{
    global $br, $sqlConnect;
    if (empty($registration_data)) {
        return false;
    }
    if ($br['config']['user_registration'] == 0) {
        return false;
    }
    $ip = '0.0.0.0';
    $get_ip = get_ip_address();
    if (!empty($get_ip)) {
        $ip = $get_ip;
    }
    if ($br['config']['login_auth'] == 1) {
        $getIpInfo = fetchDataFromURL("http://ip-api.com/json/$get_ip");
        $getIpInfo = json_decode($getIpInfo, true);
        if ($getIpInfo['status'] == 'success' && !empty($getIpInfo['regionName']) && !empty($getIpInfo['countryCode']) && !empty($getIpInfo['timezone']) && !empty($getIpInfo['city'])) {
            $registration_data['last_login_data'] = json_encode($getIpInfo);
        }
    }
    $registration_data['registered'] = date('n') . '/' . date("Y");
    $registration_data['joined'] = time();
    $registration_data['password'] = Br_Secure(base64_encode($registration_data['password']));
    $registration_data['ip_address'] = Br_Secure($ip);

    $fields = '`' . implode('`,`', array_keys($registration_data)) . '`';
    $data = '\'' . implode('\', \'', $registration_data) . '\'';
    $query = mysqli_query($sqlConnect, "INSERT INTO " . T_USERS . " ({$fields}) VALUES ({$data})");
    $user_id = mysqli_insert_id($sqlConnect);
    if ($query) {
        return true;
    } else {
        return false;
    }
}
function Br_ActivateUser($email, $code)
{
    global $sqlConnect;
    $email = Br_Secure($email);
    $code = Br_Secure($code);
    $query = mysqli_query($sqlConnect, " SELECT COUNT(`uid`)  FROM " . T_USERS . "  WHERE `email` = '{$email}' AND `email_code` = '{$code}' AND `active` = '0'");
    $result = Br_Sql_Result($query, 0);
    if ($result == 1) {
        $query_two = mysqli_query($sqlConnect, " UPDATE " . T_USERS . "  SET `active` = '1' WHERE `email` = '{$email}' ");
        if ($query_two) {
            return true;
        }
    } else {
        return false;
    }
}
function Br_ResetPassword($user_id, $password)
{
    global $sqlConnect;
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    if (empty($password)) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    $password = Br_Secure(base64_encode($password));
    $query = mysqli_query($sqlConnect, " UPDATE " . T_USERS . " SET `password` = '{$password}' WHERE `uid` = {$user_id} ");
    if ($query) {
        return true;
    } else {
        return false;
    }
}

function Br_isValidPasswordResetToken($string)
{
    global $sqlConnect;
    $string_exp = explode('_', $string);
    $user_id = Br_Secure($string_exp[0]);
    $password = Br_Secure($string_exp[1]);
    if (empty($user_id) or !is_numeric($user_id) or $user_id < 1) {
        return false;
    }
    if (empty($password)) {
        return false;
    }
    $query = mysqli_query($sqlConnect, " SELECT COUNT(`uid`) FROM " . T_USERS . " WHERE `uid` = {$user_id} AND `email_code` = '{$password}' AND `active` = '1' ");
    return (Br_Sql_Result($query, 0) == 1) ? true : false;
}

function Br_isValidPasswordResetToken2($string)
{
    global $sqlConnect;
    $string_exp = explode('_', $string);
    $user_id = Br_Secure($string_exp[0]);
    $password = Br_Secure($string_exp[1]);
    if (empty($user_id) or !is_numeric($user_id) or $user_id < 1) {
        return false;
    }
    if (empty($password)) {
        return false;
    }
    $query = mysqli_query($sqlConnect, " SELECT COUNT(`uid`) FROM " . T_USERS . " WHERE `uid` = {$user_id} AND `password` = '{$password}' AND `active` = '1' ");
    return (Br_Sql_Result($query, 0) == 1) ? true : false;
}

function Br_UserIDFromEmailCode($email_code)
{
    global $sqlConnect;
    if (empty($email_code)) {
        return false;
    }
    $email_code = Br_Secure($email_code);
    $query = mysqli_query($sqlConnect, "SELECT `uid` FROM " . T_USERS . " WHERE `email_code` = '{$email_code}'");
    return Br_Sql_Result($query, 0, 'uid');
}

function BrAddBadLoginLog()
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == true) {
        return false;
    }
    $ip = get_ip_address();
    if (empty($ip)) {
        return true;
    }
    $time = time();
    $query = mysqli_query($sqlConnect, "INSERT INTO " . T_BAD_LOGIN . " (`ip`, `time`) VALUES ('{$ip}', '{$time}')");
    if ($query) {
        return true;
    }
}
function BrCanLogin()
{
    global $br, $sqlConnect, $db;
    if ($br['loggedin'] == true) {
        return false;
    }
    $ip = get_ip_address();
    if (empty($ip)) {
        return true;
    }
    if ($br['config']['lock_time'] < 1) {
        return true;
    }
    if ($br['config']['bad_login_limit'] < 1) {
        return true;
    }

    $time = time() - (60 * $br['config']['lock_time']);
    $login = $db->where('ip', $ip)->get(T_BAD_LOGIN);
    if (count($login) >= $br['config']['bad_login_limit']) {
        $last = end($login);
        if ($last->time >= $time) {
            return false;
        }
    }
    $db->where('time', time() - (60 * $br['config']['lock_time'] * 2), '<')->delete(T_BAD_LOGIN);
    return true;
}

function addhttp($url)
{
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}

function Br_UserActive($username)
{
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Br_Secure($username);
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`uid`) FROM " . T_USERS . "  WHERE (`email` = '{$username}' OR `phone_number` = '{$username}') AND `active` = '1'");
    return (Br_Sql_Result($query, 0) == 1) ? true : false;
}
function Br_UserInactive($username)
{
    global $sqlConnect;
    if (empty($username)) {
        return false;
    }
    $username = Br_Secure($username);
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`uid`) FROM " . T_USERS . "  WHERE (`email` = '{$username}' OR `phone_number` = '{$username}') AND `active` = '2'");
    return (Br_Sql_Result($query, 0) == 1) ? true : false;
}

function Br_GetUserPostFromPid($postid)
{
    global $sqlConnect;
    if (empty($postid)) {
        return false;
    }
    $postid = Br_Secure($postid);
    $query = mysqli_query($sqlConnect, "SELECT * FROM " . T_ROLES . "  WHERE `id` = '{$postid}'");
    $data = mysqli_fetch_assoc($query);
    return $data['name'];
}

function Br_GetUserPost($uid)
{
    global $br, $sqlConnect;
    $role = array();
    $data = array();
    $query_one = "SELECT * FROM " . T_POSTS . " WHERE `uid` = '{$uid}'";

    $sql = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql)) {
        $role['pid'] = $fetched_data['pid'];
        $role['post'] = Br_GetUserPostFromPid($fetched_data['pid']);

        $data[$fetched_data['pid']] = $role;
    }
    return $data;
}

function Br_getPostColor()
{
    $colors = array("label bg-red", "label bg-pink", "label bg-purple", "label bg-deep-purple", "label bg-indigo", "label bg-blue", "label bg-light-blue", "label bg-cyan", "label bg-teal", "label bg-green", "label bg-light-green", "label bg-lime", "label bg-yellow", "label bg-amber", "label bg-orange", "label bg-deep-orange", "label bg-brown", "label bg-grey", "label bg-blue-grey", "label bg-black");
    $key = array_rand($colors, 1);
    return $colors[$key];
}

function Br_UserBirthday($birthday)
{
    global $br;
    if (empty($birthday)) {
        return false;
    }
    $birthday = Br_Secure($birthday);
    if ($br['config']['age'] == 0) {
        $age = date_diff(date_create($birthday), date_create('today'))->y;
    } else {
        $age_style = explode('-', $birthday);
        $age = $age_style[1] . '/' . $age_style[2] . '/' . $age_style[0];
    }
    return $age;
}

function Br_GetAllUserIds()
{
    global $br, $sqlConnect;
    $data = array();
    $query_one = " SELECT * FROM " . T_USERS;

    $sql = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql)) {
        $data[] = $fetched_data['uid'];
    }
    return $data;
}

function Br_GetAllRoleIds()
{
    global $br, $sqlConnect;
    $data = array();
    $query_one = " SELECT * FROM " . T_ROLES;

    $sql = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql)) {
        $data[] = $fetched_data['id'];
    }
    return $data;
}

function Br_GetAllRole($id)
{
    global $br, $sqlConnect;
    $data = array();
    $query_one = " SELECT * FROM " . T_ROLES . " WHERE `id` = '{$id}'";

    $sql = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql)) {
        $data['id'] = $fetched_data['id'];
        $data['name'] = $fetched_data['name'];
        $data['description'] = $fetched_data['description'];
        $data['priority'] = $fetched_data['priority'];
    }
    return $data;
}
function Br_GetAllRoles()
{
    global $br, $sqlConnect;
    $role = array();
    $data = array();
    $query_one = "SELECT * FROM " . T_ROLES;

    $sql = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql)) {
        $role['id'] = $fetched_data['id'];
        $role['name'] = $fetched_data['name'];
        $role['description'] = $fetched_data['description'];
        $role['priority'] = $fetched_data['priority'];

        $data[$fetched_data['id']] = $role;
    }
    return $data;
}

function Br_GetAssignedUsers($pid)
{
    global $br, $sqlConnect;
    $role = array();
    $data = array();
    $query_one = "SELECT * FROM " . T_PROJECTS_A . " WHERE `pid` = '{$pid}'";

    $sql = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql)) {
        $role['uid'] = $fetched_data['uid'];
        $name = Br_UserData($fetched_data['uid']);
        $role['name'] = $name['name'];

        $data[$fetched_data['uid']] = $role;
    }
    return $data;
}

function Br_GetProject($id)
{
    global $br, $sqlConnect;
    $data = array();
    $query_one = " SELECT * FROM " . T_PROJECTS . " WHERE `id` = '{$id}'";

    $sql = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql)) {
        $data['id'] = $fetched_data['id'];
        $data['name'] = $fetched_data['name'];
        $data['price'] = $fetched_data['price'];
        $data['description'] = $fetched_data['description'];
        $data['priority'] = $fetched_data['priority'];
        $data['status'] = $fetched_data['status'];
    }
    return $data;
}

function Br_GetAllProjects()
{
    global $br, $sqlConnect;
    $role = array();
    $data = array();
    $query_one = "SELECT * FROM " . T_PROJECTS;

    $sql = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql)) {
        $role['id'] = $fetched_data['id'];
        $role['name'] = $fetched_data['name'];
        $role['description'] = $fetched_data['description'];
        $role['priority'] = $fetched_data['priority'];

        $data[$fetched_data['id']] = $role;
    }
    return $data;
}

function Br_getStatusType($id)
{
    if ($id == 0) {
        return "Ongoing";
    } else if ($id == 1) {
        return "Completed";
    } else if ($id == 2) {
        return "Pending";
    } else {
        return "Suspended";
    }
}

function Br_GetAllAdminIds()
{
    global $br, $sqlConnect;
    $data = array();
    $query_one = " SELECT * FROM " . T_USERS . " WHERE `admin` <> '0'";

    $sql = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql)) {
        $data[] = $fetched_data['uid'];
    }
    return $data;
}


function Br_GetAllUsers($limit = '', $type = '', $filter = array(), $after = '')
{
    global $br, $sqlConnect;
    $data = array();
    $query_one = " SELECT `user_id` FROM " . T_USERS . " WHERE `type` = 'user'";
    if (isset($filter) and !empty($filter)) {
        if (!empty($filter['query'])) {
            $query_one .= " AND ((`email` LIKE '%" . Br_Secure($filter['query']) . "%') OR (`username` LIKE '%" . Br_Secure($filter['query']) . "%') OR CONCAT( `first_name`,  ' ', `last_name` ) LIKE  '%" . Br_Secure($filter['query']) . "%')";
        }
        if (isset($filter['source']) && $filter['source'] != 'all') {
            $query_one .= " AND `src` = '" . Br_Secure($filter['source']) . "'";
        }
        if (isset($filter['status']) && $filter['status'] != 'all') {
            $query_one .= " AND `active` = '" . Br_Secure($filter['status']) . "'";
        }
    }
    if (!empty($after) && is_numeric($after) && $after > 0) {
        $query_one .= " AND `user_id` < " . Br_Secure($after);
    }
    if ($type == 'sidebar') {
        $query_one .= " ORDER BY RAND()";
    } else {
        $query_one .= " ORDER BY `user_id` DESC";
    }
    if (isset($limit) and !empty($limit)) {
        $query_one .= " LIMIT {$limit}";
    }
    $sql = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql)) {
        $user_data = Br_UserData($fetched_data['user_id']);
        $user_data['src'] = ($user_data['src'] == 'site') ? $br['config']['siteName'] : $user_data['src'];
        ;
        $data[] = $user_data;
    }
    return $data;
}
function Br_GetAllUsersByType($type = 'all')
{
    global $sqlConnect;
    $data = array();
    $query_one = " SELECT `user_id` FROM " . T_USERS;
    if ($type == 'active') {
        $query_one .= " WHERE `active` = '1'";
    } else if ($type == 'inactive') {
        $query_one .= " WHERE `active` = '0' OR `active` = '2'";
    } else if ($type == 'all') {
        $query_one .= "";
    }
    $sql = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql)) {
        $data[] = Br_UserData($fetched_data['user_id']);
    }
    return $data;
}
function Br_GetUsersByTime($type = 'week')
{
    global $sqlConnect;
    $types = array('week', 'month', '3month', '6month', '9month', 'year');
    if (empty($type) || !in_array($type, $types)) {
        return array();
    }
    $data = array();
    $end = time() - (60 * 60 * 24 * 7);
    $start = time() - (60 * 60 * 24 * 14);
    if ($type == 'month') {
        $end = time() - (60 * 60 * 24 * 30);
        $start = time() - (60 * 60 * 24 * 60);
    }
    if ($type == '3month') {
        $end = time() - (60 * 60 * 24 * 61);
        $start = time() - (60 * 60 * 24 * 150);
    }
    if ($type == '6month') {
        $end = time() - (60 * 60 * 24 * 151);
        $start = time() - (60 * 60 * 24 * 210);
    }
    if ($type == '9month') {
        $end = time() - (60 * 60 * 24 * 211);
        $start = time() - (60 * 60 * 24 * 300);
    }
    if ($type == 'year') {
        $end = time() - (60 * 60 * 24 * 365);
    }
    $sub1 = " WHERE `lastseen` >= '{$start}' ";
    $sub2 = " AND `lastseen` <= '{$end}' ";
    if ($type == 'year') {
        $sub2 = "";
    }
    $query_one = " SELECT `user_id` FROM " . T_USERS . $sub1 . $sub2;
    $sql = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql)) {
        $data[] = Br_UserData($fetched_data['user_id']);
    }
    return $data;
}


function Br_WelcomeUsers($limit = '', $type = '')
{
    global $br, $sqlConnect;
    if (empty($limit)) {
        $limit = 12;
    }
    $data = array();
    $query_one = " SELECT `user_id` FROM " . T_USERS . " WHERE `active` = '1' AND `avatar` <> '" . Br_Secure($br['userDefaultAvatar']) . "' ORDER BY RAND() LIMIT {$limit}";
    $sql = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql)) {
        $data[] = Br_UserData($fetched_data['user_id']);
    }
    return $data;
}

function Br_GetMedia($media)
{
    global $br;
    if (empty($media)) {
        return '';
    }
    return $br['config']['site_url'] . '/' . $media;
}

function Br_IsUrl($uri)
{
    if (empty($uri)) {
        return false;
    }
    if (filter_var($uri, FILTER_VALIDATE_URL)) {
        return true;
    }
    return false;
}

function Br_IsAdmin($user_id = 0)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    if (!empty($user_id) && $user_id > 0) {
        $query = mysqli_query($sqlConnect, "SELECT COUNT(`uid`) as count FROM " . T_USERS . " WHERE admin = '1' AND uid = {$user_id}");
        $sql = mysqli_fetch_assoc($query);
        if ($sql['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    if ($br['user']['admin'] == 1) {
        return true;
    }
    return false;
}
function Br_IsModerator($user_id = '')
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    if (!empty($user_id) && $user_id > 0) {
        $query = mysqli_query($sqlConnect, "SELECT COUNT(`uid`) as count FROM " . T_USERS . " WHERE admin = '2' AND uid = {$user_id}");
        $sql = mysqli_fetch_assoc($query);
        if ($sql['count'] > 0) {
            return true;
        } else {
            return false;
        }
    }
    if ($br['user']['admin'] == 2) {
        return true;
    }
    return false;
}

function Br_VerfiyIP($username = '')
{
    global $br, $db;
    if (empty($username)) {
        return false;
    }
    if ($br['config']['login_auth'] == 0) {
        return true;
    }
    $getuser = Br_UserData(Br_UserIdForLogin($username));
    $get_ip = get_ip_address();
    $getIpInfo = fetchDataFromURL("http://ip-api.com/json/$get_ip");
    $getIpInfo = json_decode($getIpInfo, true);
    if ($getIpInfo['status'] == 'success' && !empty($getIpInfo['regionName']) && !empty($getIpInfo['countryCode']) && !empty($getIpInfo['timezone']) && !empty($getIpInfo['city'])) {
        $create_new = false;
        $_SESSION['last_login_data'] = $getIpInfo;
        if (empty($getuser['last_login_data'])) {
            $create_new = true;
        } else {
            $lastLoginData = (Array) json_decode($getuser['last_login_data']);
            if (($getIpInfo['regionName'] != $lastLoginData['regionName']) || ($getIpInfo['countryCode'] != $lastLoginData['countryCode']) || ($getIpInfo['timezone'] != $lastLoginData['timezone']) || ($getIpInfo['city'] != $lastLoginData['city'])) {
                // send email
                $code = rand(111111, 999999);
                $hash_code = md5($code);
                $br['email']['username'] = $getuser['fname'];
                $br['email']['countryCode'] = $getIpInfo['countryCode'];
                $br['email']['timezone'] = $getIpInfo['timezone'];
                $br['email']['email'] = $getuser['email'];
                $br['email']['ip_address'] = $get_ip;
                $br['email']['code'] = $code;
                $br['email']['city'] = $getIpInfo['city'];
                $br['email']['date'] = date("Y-m-d h:i:sa");
                $update_code = $db->where('uid', $getuser['uid'])->update(T_USERS, array('email_code' => $hash_code));
                $email_body = Br_LoadPage("emails/unusual-login");
                $send_message_data = array(
                    'from_email' => $br['config']['siteEmail'],
                    'from_name' => $br['config']['siteName'],
                    'to_email' => $getuser['email'],
                    'to_name' => $getuser['fname'],
                    'subject' => 'Please verify that it’s you',
                    'charSet' => 'utf-8',
                    'message_body' => $email_body,
                    'is_html' => true
                );
                $send = Br_SendMessage($send_message_data);
                if ($send && !empty($_SESSION['last_login_data'])) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }
        if ($create_new == true) {
            $lastLoginData = json_encode($getIpInfo);
            $update_user = $db->where('uid', $getuser['uid'])->update(T_USERS, array('last_login_data' => $lastLoginData));
            return true;
        }
        return false;
    } else {
        return true;
    }
}

function Br_TwoFactor($username = '', $id_or_u = 'user')
{
    global $br, $db;
    if (empty($username)) {
        return true;
    }
    if ($br['config']['two_factor'] == 0) {
        return true;
    }

    if ($id_or_u == 'id') {
        $getuser = Br_UserData($username);
    } else {
        $getuser = Br_UserData(Br_UserIdForLogin($username));
    }

    if ($getuser['two_factor'] == 0 || $getuser['two_factor_verified'] == 0) {
        return true;
    }

    $code = rand(111111, 999999);
    $hash_code = md5($code);
    $update_code = $db->where('uid', $getuser['uid'])->update(T_USERS, array('email_code' => $hash_code));

    $message = "Your confirmation code is: $code";

    if (!empty($getuser['phone_number']) && ($br['config']['two_factor_type'] == 'both' || $br['config']['two_factor_type'] == 'phone')) {
        $send_message = Br_SendSMSMessage($getuser['phone_number'], $message);
    }
    if ($br['config']['two_factor_type'] == 'both' || $br['config']['two_factor_type'] == 'email') {
        $send_message_data = array(
            'from_email' => $br['config']['siteEmail'],
            'from_name' => $br['config']['siteName'],
            'to_email' => $getuser['email'],
            'to_name' => $getuser['name'],
            'subject' => 'Please verify that it’s you',
            'charSet' => 'utf-8',
            'message_body' => $message,
            'is_html' => true
        );
        $send = Br_SendMessage($send_message_data);
    }
    return false;
}

function Br_CountAllData($type)
{
    global $br, $sqlConnect;
    $type_table = T_USERS;
    $type_id = 'uid';
    if ($type == 'user') {
        $type_table = T_USERS;
        $type_id = 'uid';
    } else if ($type == 'roles') {
        $type_table = T_ROLES;
        $type_id = 'id';
    } else if ($type == 'projects') {
        $type_table = T_PROJECTS;
        $type_id = 'id';
    } else if ($type == 'announcement_views') {
        $type_table = T_ANNOUNCEMENT_VIEWS;
        $type_id = 'id';
    } else if ($type == 'sessions') {
        $type_table = T_APP_SESSIONS;
        $type_id = 'id';
    } else if ($type == 'affilitate') {
        $type_table = T_A_REQUESTS;
        $type_id = 'id';
    } else if ($type == 'emails') {
        $type_table = T_EMAILS;
        $type_id = 'id';
    }
    $type_id = Br_Secure($type_id);
    $query_one = mysqli_query($sqlConnect, "SELECT COUNT($type_id) as count FROM {$type_table}");
    $fetched_data = mysqli_fetch_assoc($query_one);
    return $fetched_data['count'];
}
function Br_GetRegisteredDataStatics($month, $type = 'user')
{
    global $br, $sqlConnect;
    $year = date("Y");
    $type_table = T_USERS;
    $type_id = 'uid';
    if ($type == 'user') {
        $type_table = T_USERS;
        $type_id = 'uid';
    } else if ($type == 'projects') {
        $type_table = T_PROJECTS;
        $type_id = 'id';
    } else if ($type == 'group') {
        //$type_table = T_GROUPS;
        $type_id = 'id';
    } else if ($type == 'posts') {
        //$type_table = T_POSTS;
        $type_id = 'id';
    }
    $type_id = Br_Secure($type_id);
    $query_one = mysqli_query($sqlConnect, "SELECT COUNT($type_id) as count FROM {$type_table} WHERE `registered` = '{$month}/{$year}'");
    $fetched_data = mysqli_fetch_assoc($query_one);
    return $fetched_data['count'];
}

function Br_CountOnlineData($type = '')
{
    global $br, $sqlConnect;
    $data = array();
    $type_table = T_USERS;
    $type_id = Br_Secure('uid');
    $time = time() - 60;
    $query_one = mysqli_query($sqlConnect, "SELECT COUNT(`{$type_id}`) as count FROM {$type_table} WHERE `lastseen` > {$time}");
    $fetched_data = mysqli_fetch_assoc($query_one);
    return $fetched_data['count'];
}
function Br_GetAllOnlineData()
{
    global $br, $sqlConnect;
    $data = array();
    $type_table = T_USERS;
    $type_id = Br_Secure('uid');
    $time = time() - 60;
    $query_one = mysqli_query($sqlConnect, "SELECT `uid` FROM {$type_table} WHERE `lastseen` > {$time}");
    while ($fetched_data = mysqli_fetch_assoc($query_one)) {
        $data[] = Br_UserData($fetched_data['uid']);
    }
    return $data;
}

function Br_UpdateUserData($user_id, $update_data, $unverify = false)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($user_id) || !is_numeric($user_id) || $user_id < 0) {
        return false;
    }
    if (empty($update_data)) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    $is_mod = Br_IsModerator();
    $is_admin = Br_IsAdmin();

    if ($is_admin === false && $is_mod === false) {
        if ($br['user']['uid'] != $user_id) {
            return false;
        }
    }
    if (!empty($update_data['admin']) && $update_data['admin'] == 1) {
        if ($is_admin === false) {
            return false;
        }
    }
    // if (isset($update_data['verified'])) {
    //     if (empty($update_data['pro_'])) {
    //         if ($is_admin === false && $is_mod === false) {
    //             return false;
    //         }
    //     }
    // }
    if ($is_mod) {
        $user_data_ = Br_UserData($user_id);
        if ($user_data_['admin'] == 1) {
            return false;
        }
    }
    // if (isset($update_data['country_id'])) {
    //     if (!array_key_exists($update_data['country_id'], $br['countries_name'])) {
    //         $update_data['country_id'] = 1;
    //     }
    // }

    $update = array();
    foreach ($update_data as $field => $data) {
        if ($field != 'pro_') {
            $update[] = '`' . $field . '` = \'' . Br_Secure($data, 0) . '\'';
        }
    }
    $impload = implode(', ', $update);
    $query_one = " UPDATE " . T_USERS . " SET {$impload} WHERE `uid` = {$user_id} ";
    $query_two = " UPDATE " . T_USERS . " SET `verified` = '0' WHERE `uid` = {$user_id} ";
    $query1 = mysqli_query($sqlConnect, $query_one);
    if ($unverify == true) {
        @mysqli_query($sqlConnect, $query_two);
    }
    if ($query1) {
        return true;
    } else {
        return false;
    }
}


function Br_UploadLogo($data = array())
{
    global $br, $sqlConnect;
    if (isset($data['file']) && !empty($data['file'])) {
        $data['file'] = Br_Secure($data['file']);
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = Br_Secure($data['name']);
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = Br_Secure($data['name']);
    }
    if (empty($data)) {
        return false;
    }
    $allowed = 'jpg,png,jpeg,gif';
    $new_string = pathinfo($data['name'], PATHINFO_FILENAME) . '.' . strtolower(pathinfo($data['name'], PATHINFO_EXTENSION));
    $extension_allowed = explode(',', $allowed);
    $file_extension = pathinfo($new_string, PATHINFO_EXTENSION);
    if (!in_array($file_extension, $extension_allowed)) {
        return false;
    }
    $dir = "themes/" . $br['config']['theme'] . "/img/";
    $filename = $dir . "logo.{$file_extension}";
    if (move_uploaded_file($data['file'], $filename)) {
        if (Br_SaveConfig('logo_extension', $file_extension)) {
            return true;
        }
    }
}

function Br_UploadBackground($data = array())
{
    global $br, $sqlConnect;
    if (isset($data['file']) && !empty($data['file'])) {
        $data['file'] = Br_Secure($data['file']);
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = Br_Secure($data['name']);
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = Br_Secure($data['name']);
    }
    if (empty($data)) {
        return false;
    }
    $allowed = 'jpg,png,jpeg,gif';
    $new_string = pathinfo($data['name'], PATHINFO_FILENAME) . '.' . strtolower(pathinfo($data['name'], PATHINFO_EXTENSION));
    $extension_allowed = explode(',', $allowed);
    $file_extension = pathinfo($new_string, PATHINFO_EXTENSION);
    if (!in_array($file_extension, $extension_allowed)) {
        return false;
    }
    $dir = "themes/" . $br['config']['theme'] . "/img/backgrounds/";
    $filename = $dir . "background-1.{$file_extension}";
    if (move_uploaded_file($data['file'], $filename)) {
        if (Br_SaveConfig('background_extension', $file_extension)) {
            return true;
        }
    }
}

function Br_UploadFavicon($data = array())
{
    global $br, $sqlConnect;
    if (isset($data['file']) && !empty($data['file'])) {
        $data['file'] = Br_Secure($data['file']);
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = Br_Secure($data['name']);
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = Br_Secure($data['name']);
    }
    if (empty($data)) {
        return false;
    }
    $allowed = 'jpg,png,jpeg,gif';
    $new_string = pathinfo($data['name'], PATHINFO_FILENAME) . '.' . strtolower(pathinfo($data['name'], PATHINFO_EXTENSION));
    $extension_allowed = explode(',', $allowed);
    $file_extension = pathinfo($new_string, PATHINFO_EXTENSION);
    if (!in_array($file_extension, $extension_allowed)) {
        return false;
    }
    $dir = "themes/" . $br['config']['theme'] . "/img/";
    $filename = $dir . "icon.{$file_extension}";
    if (move_uploaded_file($data['file'], $filename)) {
        if (Br_SaveConfig('favicon_extension', $file_extension)) {
            return true;
        }
    }
}

function Br_Markup($text, $link = true, $hashtag = true, $mention = true)
{
    global $sqlConnect;
    if ($mention == true) {
        $Orginaltext = $text;
        $mention_regex = '/@\[([0-9]+)\]/i';
        if (preg_match_all($mention_regex, $text, $matches)) {
            foreach ($matches[1] as $match) {
                $match = Br_Secure($match);
                $match_search = '@[' . $match . ']';
                $match_replace = '';
                $Orginaltext = str_replace($match_search, $match_replace, $Orginaltext);
                $text = str_replace($match_search, $match_replace, $text);

            }
        }
    }
    if ($link == true) {
        $link_search = '/\[a\](.*?)\[\/a\]/i';
        if (preg_match_all($link_search, $text, $matches)) {
            foreach ($matches[1] as $match) {
                $match_decode = urldecode($match);
                $match_decode_url = $match_decode;
                $count_url = mb_strlen($match_decode);
                if ($count_url > 50) {
                    $match_decode_url = mb_substr($match_decode_url, 0, 30) . '....' . mb_substr($match_decode_url, 30, 20);
                }
                $match_url = $match_decode;
                if (!preg_match("/http(|s)\:\/\//", $match_decode)) {
                    $match_url = 'http://' . $match_url;
                }
                $text = str_replace('[a]' . $match . '[/a]', '<a href="' . strip_tags($match_url) . '" target="_blank" class="hash" rel="nofollow">' . $match_decode_url . '</a>', $text);
            }
        }
    }
    if ($hashtag == true) {
        $hashtag_regex = '/(#\[([0-9]+)\])/i';
        preg_match_all($hashtag_regex, $text, $matches);
    }
    return $text;
}

function Br_Emo($string = '')
{
    global $emo, $br;
    foreach ($emo as $code => $name) {
        $code = $code;
        $name = '<i class="twa-lg twa twa-' . $name . '"></i>';
        $string = str_replace($code, $name, $string);
    }
    return $string;
}