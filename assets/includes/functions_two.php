<?php

function Br_CreateSession()
{
    $hash = sha1(rand(1111, 9999));
    if (!empty($_SESSION['hash_id'])) {
        $_SESSION['hash_id'] = $_SESSION['hash_id'];
        return $_SESSION['hash_id'];
    }
    $_SESSION['hash_id'] = $hash;
    return $hash;
}
function Br_CheckSession($hash = '')
{
    if (!isset($_SESSION['hash_id']) || empty($_SESSION['hash_id'])) {
        return false;
    }
    if (empty($hash)) {
        return false;
    }
    if ($hash == $_SESSION['hash_id']) {
        return true;
    }
    return false;
}
function Br_CreateMainSession()
{
    $hash = substr(sha1(rand(1111, 9999)), 0, 20);
    if (!empty($_SESSION['main_hash_id'])) {
        $_SESSION['main_hash_id'] = $_SESSION['main_hash_id'];
        return $_SESSION['main_hash_id'];
    }
    $_SESSION['main_hash_id'] = $hash;
    return $hash;
}
function Br_CheckMainSession($hash = '')
{
    if (!isset($_SESSION['main_hash_id']) || empty($_SESSION['main_hash_id'])) {
        return false;
    }
    if (empty($hash)) {
        return false;
    }
    if ($hash == $_SESSION['main_hash_id']) {
        return true;
    }
    return false;
}

function Br_GetCustomPages()
{
    global $sqlConnect;
    $data = array();
    $query_one = "SELECT * FROM " . T_CUSTOM_PAGES . " ORDER BY `id` DESC";
    $sql_query_one = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql_query_one)) {
        $data[] = Br_GetCustomPage($fetched_data['page_name']);
    }
    return $data;
}
function Br_GetCustomPage($page_name)
{
    global $sqlConnect;
    if (empty($page_name)) {
        return false;
    }
    $data = array();
    $page_name = Br_Secure($page_name);
    $query_one = "SELECT * FROM " . T_CUSTOM_PAGES . " WHERE `page_name` = '{$page_name}'";
    $sql_query_one = mysqli_query($sqlConnect, $query_one);
    $fetched_data = mysqli_fetch_assoc($sql_query_one);
    return $fetched_data;
}
function Br_RegisterNewPage($registration_data)
{
    global $br, $sqlConnect;
    if (empty($registration_data)) {
        return false;
    }
    $fields = '`' . implode('`, `', array_keys($registration_data)) . '`';
    $data = '\'' . implode('\', \'', $registration_data) . '\'';
    $query = mysqli_query($sqlConnect, "INSERT INTO " . T_CUSTOM_PAGES . " ({$fields}) VALUES ({$data})");

    if ($query) {
        return true;
    }
    return false;
}
function Br_DeleteCustomPage($id)
{
    global $br, $sqlConnect;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (Br_IsAdmin() === false) {
        return false;
    }
    $id = Br_Secure($id);
    $query = mysqli_query($sqlConnect, "DELETE FROM " . T_CUSTOM_PAGES . " WHERE `id` = {$id}");
    if ($query) {
        return true;
    }
    return false;
}
function Br_UpdateCustomPageData($id, $update_data)
{
    global $br, $sqlConnect, $cache;
    if ($br['loggedin'] == false) {
        return false;
    }
    if (empty($id) || !is_numeric($id) || $id < 0) {
        return false;
    }
    if (empty($update_data)) {
        return false;
    }
    $id = Br_Secure($id);
    if (Br_IsAdmin() === false) {
        return false;
    }
    $update = array();
    foreach ($update_data as $field => $data) {
        $update[] = '`' . $field . '` = \'' . Br_Secure($data, 0) . '\'';
    }
    $impload = implode(', ', $update);
    $query_one = "UPDATE " . T_CUSTOM_PAGES . " SET {$impload} WHERE `id` = {$id} ";
    $query = mysqli_query($sqlConnect, $query_one);
    if ($query) {
        return true;
    }
    return false;
}

function Br_CountUserData($type)
{
    global $br, $sqlConnect;
    $type_table = T_USERS;
    $type_id = 'uid';
    $where = '';
    if (in_array($type, array('Male', 'Female'))) {
        $where = "`gender` = '" . $type . "'";
    } else if ($type == 'active') {
        $where = "`active` = '1'";
    } else if ($type == 'not_active') {
        $where = "`active` <> '1'";
    } else if ($type == 'requested') {
        $where = "uid not in (SELECT uid FROM ".T_POSTS.")";
    } else if ($type == 'not_requested') {
        $where = "uid in (SELECT uid FROM ".T_POSTS.")";
    } else if ($type == 'admin') {
        $where = "`admin` = '1'";
    } else if ($type == 'not_admin') {
        $where = "`admin` <> '1'";
    } else if ($type == 'roles') {
        $type_id = 'id';
        $where = "`id` <> '0'";
        $type_table = T_ROLES;
    } else if ($type == 'amembers') {
        $type_id = 'id';
        $where = "`id` <> '0'";
        $type_table = T_PROJECTS_A;
    } else if ($type == 'tprojects') {
        $type_id = 'id';
        $where = "`id` <> '0'";
        $type_table = T_PROJECTS;
    } else if ($type == 'active_projects') {
        $type_id = 'id';
        $where = "`status` = '0'";
        $type_table = T_PROJECTS;
    } else if ($type == 'inactive_projects') {
        $type_id = 'id';
        $where = "`status` = '1'";
        $type_table = T_PROJECTS;
    } else if ($type == 'pending_projects') {
        $type_id = 'id';
        $where = "`status` = '2'";
        $type_table = T_PROJECTS;
    } else if ($type == 'cancelled_projects') {
        $type_id = 'id';
        $where = "`status` = '3'";
        $type_table = T_PROJECTS;
    }
    $query_one = mysqli_query($sqlConnect, "SELECT COUNT($type_id) as count FROM {$type_table} WHERE {$where}");
    $fetched_data = mysqli_fetch_assoc($query_one);
    return $fetched_data['count'];
}

function Br_GenirateSiteMap($updating = 'daily')
{
    global $sqlConnect, $br;
    if ($br['loggedin'] == false || !Br_IsAdmin()) {
        return false;
    }
    include('assets/libs/sitemap-php/Sitemap.php');
    $site = $br['config']['site_url'];
    $sitemap = new Sitemap($site . '/');
    $sitemap->setPath('./');
    if (
        !in_array($updating, array(
            'daily',
            'always',
            'hourly',
            'weekly',
            'monthly',
            'yearly',
            'never'
        )
        )
    ) {
        $updating = 'daily';
    }
    $sitemap->setFilename('sitemap');
    $profiles = mysqli_query($sqlConnect, "SELECT `fname` FROM " . T_USERS . " WHERE `active` = '1'");
    while ($fetched_data = mysqli_fetch_assoc($profiles)) {
        $sitemap->addItem($fetched_data['fname'], '1.0', $updating, 'Today');
    }

    $sitemap->addItem('terms/about-us', '0.1', 'never');
    $sitemap->addItem('contact-us', '0.1', 'never');
    $sitemap->addItem('terms/privacy-policy', '0.1', 'yearly');
    $sitemap->addItem('terms/terms', '0.1', 'yearly');

    $sitemap->createSitemapIndex($site . '/xml/', 'Today');
    return true;
}

function Br_IsNameExist($username, $active = 0)
{
    global $br, $sqlConnect;
    $data = array();
    if (empty($username)) {
        return false;
    }
    $active_text = '';
    if ($active == 1) {
        $active_text = "AND `active` = '1'";
    }
    $username = Br_Secure($username);

    $query = mysqli_query($sqlConnect, "SELECT COUNT(`uid`) as users FROM " . T_USERS . " WHERE `email` = '{$username}' {$active_text}");
    $fetched_data = mysqli_fetch_assoc($query);
    if ($fetched_data['users'] == 1) {
        return array(
        true,
            'type' => 'user'
        );
    }

    return array(false);
}
//  function Br_IsPhoneExist($phone) {
//      global $br, $sqlConnect;
//      $data = array();
//      if (empty($phone)) {
//          return false;
//      }
//      $phone     = Br_Secure($phone);
//      $query_text   = "SELECT (SELECT COUNT(`user_id`) FROM " . T_USERS . " WHERE `phone_number` = '{$phone}') as users";
//      $query        = mysqli_query($sqlConnect, $query_text);
//      $fetched_data = mysqli_fetch_assoc($query);
//      if ($fetched_data['users'] == 1) {
//          return array(
//              true
//          );
//      }else {
//          return array(
//              false
//          );
//      }
//  }

function Br_CountPaymentHistory($id)
{
    global $sqlConnect;
    $data = array();
    $id = Br_Secure($id);
    $query_one = "SELECT COUNT(`id`) as count FROM " . T_A_REQUESTS . " WHERE `status` = '{$id}'";
    $sql_query_one = mysqli_query($sqlConnect, $query_one);
    $fetched_data = mysqli_fetch_assoc($sql_query_one);
    return $fetched_data['count'];
}

function Br_CountRefs($user_id = 0)
{
    global $sqlConnect;
    $data = array();
    $user_id = Br_Secure($user_id);
    $query_one = "SELECT COUNT(`uid`) as count FROM " . T_USERS . " WHERE `referrer` = '{$user_id}'";
    $sql_query_one = mysqli_query($sqlConnect, $query_one);
    $fetched_data = mysqli_fetch_assoc($sql_query_one);
    return $fetched_data['count'];
}

function Br_GetPaymentHistory($id)
{
    global $sqlConnect, $br;
    if (empty($id)) {
        return false;
    }
    $data = array();
    $id = Br_Secure($id);
    $query_one = "SELECT * FROM " . T_A_REQUESTS . " WHERE `id` = '{$id}'";
    $sql_query_one = mysqli_query($sqlConnect, $query_one);
    $fetched_data = mysqli_fetch_assoc($sql_query_one);
    $fetched_data['user'] = Br_UserData($fetched_data['uid']);
    $fetched_data['total_refs'] = Br_CountRefs($fetched_data['uid']);
    $fetched_data['time_text'] = Br_Time_Elapsed_String($fetched_data['time']);
    $fetched_data['callback_url'] = $br['config']['site_url'] . '/' . 'requests.php?f=admincp&paid_user_id=' . $fetched_data['uid'] . '&paid_ref_id=' . $fetched_data['id'];
    return $fetched_data;
}

function Br_GetPaymentsHistory($user_id = 0)
{
    global $sqlConnect;
    if (empty($user_id)) {
        return false;
    }
    $user_id = Br_Secure($user_id);
    $data = array();
    $query_one = "SELECT `id` FROM " . T_A_REQUESTS . " WHERE `user_id` = '{$user_id}' ORDER BY `id` DESC";
    $sql_query_one = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql_query_one)) {
        $data[] = Br_GetPaymentHistory($fetched_data['id']);
    }
    return $data;
}
function Br_GetAllPaymentsHistory($type = 0)
{
    global $sqlConnect;
    $type = Br_Secure($type);
    $data = array();
    $where = "";
    if ($type != 'all') {
        $where = "WHERE `status` = '{$type}'";
    }
    $query_one = "SELECT * FROM " . T_A_REQUESTS . " {$where} ORDER BY `id` DESC";
    $sql_query_one = mysqli_query($sqlConnect, $query_one);
    while ($fetched_data = mysqli_fetch_assoc($sql_query_one)) {
        $data[] = Br_GetPaymentHistory($fetched_data['id']);
    }
    return $data;
}

function Br_GetUsersByName($name = '', $limit = 25)
{
    global $sqlConnect, $br;
    if ($br['loggedin'] == false || !$name) {
        return false;
    }
    $user = $br['user']['id'];
    $name = Br_Secure($name);
    $data = array();
    $sub_sql = "";
    $limit_text = '';
    if (!empty($limit) && is_numeric($limit)) {
        $limit = Br_Secure($limit);
        $limit_text = 'LIMIT ' . $limit;
    }
    $sql = "SELECT `uid` FROM " . T_USERS . " WHERE `uid` <> {$user} AND `fname`  LIKE '%$name%' {$sub_sql} $limit_text";
    $query = mysqli_query($sqlConnect, $sql);
    while ($fetched_data = mysqli_fetch_assoc($query)) {
        $data[] = Br_UserData($fetched_data['uid']);
    }
    return $data;
}

function Br_GetAnnouncement($id)
{
    global $sqlConnect, $br;
    if ($br['loggedin'] == false) {
        return false;
    }
    // $user_id = Br_Secure($br['user']['uid']);
    // $data    = array();
    if (empty($id) || !is_numeric($id) || $id < 1) {
        return false;
    }
    $query = mysqli_query($sqlConnect, "SELECT * FROM " . T_ANNOUNCEMENT . " WHERE `id` = {$id} ORDER BY `id` DESC");
    if (mysqli_num_rows($query) == 1) {
        $fetched_data = mysqli_fetch_assoc($query);
        $fetched_data['text'] = Br_Markup($fetched_data['text']);
        $fetched_data['text'] = Br_Emo($fetched_data['text']);
        return $fetched_data;
    }
}
function Br_GetActiveAnnouncements()
{
    global $sqlConnect, $br;
    if ($br['loggedin'] == false) {
        return false;
    }
    $user_id = Br_Secure($br['user']['uid']);
    $data = array();
    if (Br_IsAdmin($user_id) === false) {
        return false;
    }
    $query = mysqli_query($sqlConnect, "SELECT `id` FROM " . T_ANNOUNCEMENT . " WHERE `active` = '1' ORDER BY `id` DESC");
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = Br_GetAnnouncement($row['id']);
    }
    return $data;
}

function Br_GetInactiveAnnouncements()
{
    global $sqlConnect, $br;
    if ($br['loggedin'] == false) {
        return false;
    }
    $user_id = Br_Secure($br['user']['uid']);
    $data = array();
    if (Br_IsAdmin($user_id) === false) {
        return false;
    }
    $query = mysqli_query($sqlConnect, "SELECT `id` FROM " . T_ANNOUNCEMENT . " WHERE `active` = '0' ORDER BY `id` DESC");
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = Br_GetAnnouncement($row['id']);
    }
    return $data;
}

function Br_AddNewAnnouncement($text)
{
    global $sqlConnect, $br;
    if ($br['loggedin'] == false) {
        return false;
    }
    $user_id = Br_Secure($br['user']['uid']);
    $text = mysqli_real_escape_string($sqlConnect, $text);
    if (Br_IsAdmin($user_id) === false) {
        return false;
    }
    if (empty($text)) {
        return false;
    }
    $query = mysqli_query($sqlConnect, "INSERT INTO " . T_ANNOUNCEMENT . " (`text`, `time`, `active`) VALUES ('{$text}', " . time() . ", '1')");
    if ($query) {
        return mysqli_insert_id($sqlConnect);
    }
}

function Br_GetAnnouncementViews($id)
{
    global $sqlConnect, $br;
    $id = Br_Secure($id);
    $query_one = mysqli_query($sqlConnect, "SELECT COUNT(`id`) as `count` FROM " . T_ANNOUNCEMENT_VIEWS . " WHERE `announcement_id` = {$id}");
    $sql_query_one = mysqli_fetch_assoc($query_one);
    return $sql_query_one['count'];
}

function Br_DeleteAnnouncement($id)
{
    global $sqlConnect, $br;
    if ($br['loggedin'] == false) {
        return false;
    }
    $id = Br_Secure($id);
    $user_id = Br_Secure($br['user']['uid']);
    if (Br_IsAdmin($user_id) === false) {
        return false;
    }
    $query_one = mysqli_query($sqlConnect, "DELETE FROM " . T_ANNOUNCEMENT . " WHERE `id` = {$id}");
    $query_one .= mysqli_query($sqlConnect, "DELETE FROM " . T_ANNOUNCEMENT_VIEWS . " WHERE `announcement_id` = {$id}");
    if ($query_one) {
        return true;
    }
}

function Br_IsActiveAnnouncement($id)
{
    global $sqlConnect;
    $id = Br_Secure($id);
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`id`) FROM " . T_ANNOUNCEMENT . " WHERE `id` = '{$id}' AND `active` = '1'");
    return (Br_Sql_Result($query, 0) == 1) ? true : false;
}
function Br_IsViewedAnnouncement($id)
{
    global $sqlConnect, $br;
    if ($br['loggedin'] == false) {
        return false;
    }
    $id = Br_Secure($id);
    $user_id = Br_Secure($br['user']['uid']);
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`id`) FROM " . T_ANNOUNCEMENT_VIEWS . " WHERE `announcement_id` = '{$id}' AND `uid` = '{$user_id}'");
    return (Br_Sql_Result($query, 0) > 0) ? true : false;
}
function Br_IsThereAnnouncement()
{
    global $sqlConnect, $br;
    if ($br['loggedin'] == false) {
        return false;
    }
    $user_id = Br_Secure($br['user']['uid']);
    $query = mysqli_query($sqlConnect, "SELECT COUNT(`id`) as count FROM " . T_ANNOUNCEMENT . " WHERE `active` = '1' AND `id` NOT IN (SELECT `announcement_id` FROM " . T_ANNOUNCEMENT_VIEWS . " WHERE `uid` = {$user_id})");
    $sql = mysqli_fetch_assoc($query);
    return ($sql['count'] > 0) ? true : false;
}

function Br_GetHomeAnnouncements()
{
    global $sqlConnect, $br;
    if ($br['loggedin'] == false) {
        return false;
    }
    $user_id = Br_Secure($br['user']['uid']);
    $query = mysqli_query($sqlConnect, "SELECT `id` FROM " . T_ANNOUNCEMENT . " WHERE `active` = '1' AND `id` NOT IN (SELECT `announcement_id` FROM " . T_ANNOUNCEMENT_VIEWS . " WHERE `uid` = {$user_id}) ORDER BY RAND() LIMIT 1");
    $fetched_data = mysqli_fetch_assoc($query);
    $data = Br_GetAnnouncement($fetched_data['id']);
    return $data;
}

function Br_DisableAnnouncement($id)
{
    global $sqlConnect, $br;
    if ($br['loggedin'] == false) {
        return false;
    }
    $id = Br_Secure($id);
    $user_id = Br_Secure($br['user']['uid']);
    if (Br_IsAdmin($user_id) === false) {
        return false;
    }
    if (Br_IsActiveAnnouncement($id) === false) {
        return false;
    }
    $query_one = mysqli_query($sqlConnect, "UPDATE " . T_ANNOUNCEMENT . " SET `active` = '0' WHERE `id` = {$id}");
    if ($query_one) {
        return true;
    }
}
function Br_ActivateAnnouncement($id)
{
    global $sqlConnect, $br;
    if ($br['loggedin'] == false) {
        return false;
    }
    $id = Br_Secure($id);
    $user_id = Br_Secure($br['user']['uid']);
    if (Br_IsAdmin($user_id) === false) {
        return false;
    }
    if (Br_IsActiveAnnouncement($id) === true) {
        return false;
    }
    $query_one = mysqli_query($sqlConnect, "UPDATE " . T_ANNOUNCEMENT . " SET `active` = '1' WHERE `id` = {$id}");
    if ($query_one) {
        return true;
    }
}
function Br_UpdateAnnouncementViews($id)
{
    global $sqlConnect, $br;
    if ($br['loggedin'] == false) {
        return false;
    }
    $id = Br_Secure($id);
    $user_id = Br_Secure($br['user']['uid']);
    if (Br_IsActiveAnnouncement($id) === false) {
        return false;
    }
    if (Br_IsViewedAnnouncement($id) === true) {
        return false;
    }
    $query_one = mysqli_query($sqlConnect, "INSERT INTO " . T_ANNOUNCEMENT_VIEWS . " (`uid`, `announcement_id`) VALUES ('{$user_id}', '{$id}')");
    if ($query_one) {
        return true;
    }
}

function Br_SendMessage($data = array())
{
    global $br, $sqlConnect;
    include_once('assets/libs/PHPMailer-Master/vendor/autoload.php');
    $mail = new PHPMailer\PHPMailer\PHPMailer;
    $email_from = $data['from_email'] = Br_Secure($data['from_email']);
    $to_email = $data['to_email'] = Br_Secure($data['to_email']);
    $subject = $data['subject'];
    $message_body = mysqli_real_escape_string($sqlConnect, $data['message_body']);
    $data['charSet'] = Br_Secure($data['charSet']);
    if (isset($data['insert_database'])) {
        if ($data['insert_database'] == 1) {
            $user_id = Br_Secure($br['user']['uid']);
            $query_one = mysqli_query($sqlConnect, "INSERT INTO " . T_EMAILS . " (`email_to`, `uid`, `subject`, `message`) VALUES ('{$to_email}', '{$user_id}', '{$subject}', '{$message_body}')");
            if ($query_one) {
                return true;
            }
        }
        return true;
        exit();
    }
    if ($br['config']['smtp_or_mail'] == 'mail') {
        $mail->IsMail();
    } else if ($br['config']['smtp_or_mail'] == 'smtp') {
        $mail->isSMTP();
        $mail->Host = $br['config']['smtp_host']; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $br['config']['smtp_username']; // SMTP username
        $mail->Password = $br['config']['smtp_password']; // SMTP password
        $mail->SMTPSecure = $br['config']['smtp_encryption']; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $br['config']['smtp_port'];
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
    } else {
        return false;
    }
    $mail->IsHTML($data['is_html']);
    $mail->setFrom($data['from_email'], $data['from_name']);
    $mail->addAddress($data['to_email'], $data['to_name']); // Add a recipient
    $mail->Subject = $data['subject'];
    $mail->CharSet = $data['charSet'];
    $mail->MsgHTML($data['message_body']);
    if (!empty($data['reply-to'])) {
        $mail->ClearReplyTos();
        $mail->AddReplyTo($data['reply-to'], $data['from_name']);
    }
    if ($mail->send()) {
        $mail->ClearAddresses();
        return true;
    }
}

function Br_BanNewIp($ip)
{
    global $sqlConnect;
    $ip = Br_Secure($ip);
    $query_one = mysqli_query($sqlConnect, "SELECT COUNT(`id`) as count FROM " . T_BANNED_IPS . " WHERE `ip_address` = '{$ip}'");
    $fetched_data = mysqli_fetch_assoc($query_one);
    if ($fetched_data['count'] > 0) {
        return false;
    }
    $time = time();
    $query_two = mysqli_query($sqlConnect, "INSERT INTO " . T_BANNED_IPS . " (`ip_address`,`time`) VALUES ('{$ip}','{$time}')");
    if ($query_two) {
        return true;
    }
}

function Br_IsIpBanned($id)
{
    global $sqlConnect;
    $id = Br_Secure($id);
    $query_one = mysqli_query($sqlConnect, "SELECT COUNT(`id`) as count FROM " . T_BANNED_IPS . " WHERE `id` = '{$id}'");
    $fetched_data = mysqli_fetch_assoc($query_one);
    if ($fetched_data['count'] > 0) {
        return true;
    } else {
        return false;
    }
}
function Br_DeleteBanned($id)
{
    global $sqlConnect;
    $id = Br_Secure($id);
    if (Br_IsIpBanned($id) === false) {
        return false;
    }
    $query_two = mysqli_query($sqlConnect, "DELETE FROM " . T_BANNED_IPS . " WHERE `id` = {$id}");
    if ($query_two) {
        return true;
    }
}

function Br_IsPhoneExist($phone) {
    global $br, $sqlConnect;
    $data = array();
    if (empty($phone)) {
        return false;
    }
    $phone     = Br_Secure($phone);
    $query_text   = "SELECT (SELECT COUNT(`uid`) FROM " . T_USERS . " WHERE `phone_number` = '{$phone}') as users";
    $query        = mysqli_query($sqlConnect, $query_text);
    $fetched_data = mysqli_fetch_assoc($query);
    if ($fetched_data['users'] == 1) {
        return array(
            true
        );
    }else {
        return array(
            false
        );
    }
}

function Br_ConfirmUser($user_id, $code) {
    global $sqlConnect;
    $user_id = Br_Secure($user_id);
    $code    = Br_Secure($code);
    if (!is_numeric($code) || $code <= 0) {
        return false;
    }
    if (!is_numeric($user_id) || $user_id <= 0) {
        return false;
    }
    $query   = mysqli_query($sqlConnect, " SELECT COUNT(`uid`)  FROM " . T_USERS . "  WHERE `sms_code` = '{$code}' AND `uid` = '{$user_id}' AND `active` = '0'");
    $result  = Br_Sql_Result($query, 0);
    if ($result == 1) {
        $email_code = md5(rand(1111, 9999) . time());
        $query_two = mysqli_query($sqlConnect, " UPDATE " . T_USERS . "  SET `active` = '1', `email_code` = '$email_code' WHERE `uid` = '{$user_id}' ");
        if ($query_two) {
            return true;
        }
    } else {
        return false;
    }
}

function Br_ConfirmSMSUser($user_id, $code, $email_code = '') {
    global $sqlConnect;
    $user_id = Br_Secure($user_id);
    $code    = Br_Secure($code);
    if (!is_numeric($code) || $code <= 0) {
        return false;
    }
    if (!is_numeric($user_id) || $user_id <= 0) {
        return false;
    }
    $query   = mysqli_query($sqlConnect, " SELECT COUNT(`uid`)  FROM " . T_USERS . "  WHERE `sms_code` = '{$code}' AND `uid` = '{$user_id}'");
    $result  = Br_Sql_Result($query, 0);
    if ($result == 1) {
        $email_code = md5(rand(1111, 9999) . time());
        $query_two = mysqli_query($sqlConnect, " UPDATE " . T_USERS . "  SET `active` = '1', `email_code` = '$email_code' WHERE `uid` = '{$user_id}' ");
        if ($query_two) {
            return true;
        }
    } else {
        return false;
    }
}

function Br_getStatus($config = array())
{
    global $br, $db;

    $errors = [];


    if (!ini_get('allow_url_fopen')) {
        $errors[] = ["type" => "error", "message" => "PHP function <strong>allow_url_fopen</strong> is disabled on your server, it is required to be enabled."];
    }
    if (!function_exists('mime_content_type')) {
        $errors[] = ["type" => "error", "message" => "PHP <strong>FileInfo</strong> extension is disabled on your server, it is required to be enabled."];
    }
    if (!class_exists('DOMDocument')) {
        $errors[] = ["type" => "error", "message" => "PHP <strong>dom & xml</strong> extensions are disabled on your server, they are required to be enabled."];
    }
    if (!is_writable('./upload')) {
        $errors[] = ["type" => "error", "message" => "The folder: <strong>/upload</strong> is not writable, upload folder and all subfolder(s) permission should be set to <strong>777</strong>."];
    }
    // if (!is_writable('./xml')) {
    //     $errors[] = ["type" => "error", "message" => "The folder: <strong>/xml</strong> is not writable, xml folder  permission should be set to <strong>777</strong>."];
    // }

    if (!is_writable('./sitemap.xml')) {
        $errors[] = ["type" => "error", "message" => "The file: <strong>./sitemap.xml</strong> is not writable, the file permission should be set to <strong>777</strong>."];
    }
    if (!is_writable('./sitemap-index.xml')) {
        $errors[] = ["type" => "error", "message" => "The file: <strong>./sitemap-index.xml</strong> is not writable, the file permission should be set to <strong>777</strong>."];
    }


    if (session_status() == PHP_SESSION_NONE) {
        $errors[] = ["type" => "error", "message" => "PHP Session can't start, please check the session settings on your server, the session path should be writable, contact your server for more Information."];
    }

    if (!empty($config['curl'])) {
        $ch = curl_init();
        $timeout = 10;
        $myHITurl = "https://www.google.com";
        curl_setopt($ch, CURLOPT_URL, $myHITurl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $file_contents = curl_exec($ch);
        if (curl_errno($ch)) {
            $errors[] = ["type" => "error", "message" => "<strong>cURL</strong> is not functioning, can't connect to the outside world, error found: <strong>" . curl_error($ch) . "</strong>, please contact your hosting provider to fix it."];
        }
        curl_close($ch);
    }

    if (!empty($config['htaccess'])) {
        if (!file_exists('./.htaccess')) {
            $errors[] = ["type" => "error", "message" => "The file: <strong>.htaccess</strong> is not uploaded to your server, make sure the file <strong>.htaccess</strong> is uploaded to your server."];
        } else {
            $file_gethtaccess = file_get_contents("./.htaccess");
            if (strpos($file_gethtaccess, "index.php?link1") === false) {
                $errors[] = ["type" => "error", "message" => "The file: <strong>.htaccess</strong> is not updated, please re-upload the original .htaccess file."];
            }
        }
    }


    $dirs = array_filter(glob('upload/*'), 'is_dir');
    foreach ($dirs as $key => $value) {
        if (!is_writable($value)) {
            $errors[] = ["type" => "error", "message" => "The folder: <strong>{$value}</strong> is not writable, folder permission should be set to <strong>777</strong>."];
        }
    }

    if (empty($br['config']['smtp_host']) && empty($br['config']['smtp_username'])) {
        $errors[] = ["type" => "error", "message" => "<strong>SMTP</strong> is not configured, it's recommended to setup <strong>SMTP</strong>, so the system can send e-mails from the server. <br> <a href=" . Br_LoadAdminLinkSettings('email-settings') . ">Click Here To Setup SMTP</a>"];
    }



    if (!is_writable('./themes/' . $br['config']['theme'] . '/imgs')) {
        $errors[] = ["type" => "error", "message" => "The folder: <strong>/themes/{$br['config']['theme']}/img</strong> is not writable, the path and all subfolder(s) permission should be set to <strong>777</strong>, including <strong>logo.png</strong>"];
    }


    if (file_exists('./install')) {
        $errors[] = ["type" => "error", "message" => "The folder: <strong>./install</strong> is not deleted or renamed, make sure the folder <strong>./install</strong> is deleted."];
    }



    $getSqlModes = $db->rawQuery("SELECT @@sql_mode as modes;");
    if (!empty($getSqlModes[0]->modes)) {
        $results = @explode(',', strtolower($getSqlModes[0]->modes));
        if (in_array('strict_trans_tables', $results)) {
            $errors[] = ["type" => "error", "message" => "The sql-mode <b>strict_trans_tables</b> is enabled in your mysql server, please contact your host provider to disable it."];
        }
        if (in_array('only_full_group_by', $results)) {
            $errors[] = ["type" => "error", "message" => "The sql-mode <b>only_full_group_by</b> is enabled in your mysql server, this can cause some issues on your website, please contact your host provider to disable it."];
        }
    }



    if (ini_get('max_execution_time') < 100 && ini_get('max_execution_time') > 0) {
        $errors[] = ["type" => "warning", "message" => "Your server max_execution_time is less than 100 seconds, Current: <strong>" . ini_get('max_execution_time') . "</strong> Recommended is <strong>3000</strong>."];
    }

    // if ($br['config']['developer_mode'] == "1") {
    //     $errors[] = ["type" => "warning", "message" => "<strong>Developer Mode</strong> is enabled in <strong>Settings -> General Configuration</strong>, it's not recommended to enable <strong>Developer Mode</strong> if your website is live, some errors may show."];
    // }

    if (!function_exists('exif_read_data')) {
        $errors[] = ["type" => "warning", "message" => "PHP <strong>exif</strong> extension is disabled on your server, it is recommended to be enabled."];
    }

    try {
        $getSqlWait = $db->rawQuery("show variables where Variable_name='wait_timeout';");
        if (!empty($getSqlWait[0]->Value)) {
            if ($getSqlWait[0]->Value < 1000) {
                $errors[] = ["type" => "warning", "message" => "The MySQL variable <b>wait_timeout</b> is {$getSqlWait[0]->Value}, minumum required is <strong>1000</strong>, please contact your host provider to update it."];
            }
        }
    } catch (Exception $e) {

    }

    return $errors;
}

function Br_RedirectSmooth($url)
{
    global $br;
    if ($br['config']['smooth_loading'] == 0) {
        return header("Location: $url");
        exit();
    } else {
        return $br['redirect'] = 1;
    }
}

function Br_HashPassword($password = '', $hashed_password)
{
    global $br, $sqlConnect;
    if (empty($password)) {
        return '';
    }
    $hashed_password = base64_decode($hashed_password, true);
    $hash = 'password_hash';
    if ($hash == 'password_hash') {
        if ($password == $hashed_password) {
            return true;
        }
    } else {
        $password = $hash($password);
    }
    if ($password == $hashed_password) {
        return true;
    }
    return false;
}

?>