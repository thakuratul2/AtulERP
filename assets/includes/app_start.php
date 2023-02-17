<?php

//error_reporting(0);
header('Cache-Control: max-age=846000');

@ini_set('max_execution_time', 0);
require_once('config.php');

require_once('assets/libs/DB/vendor/autoload.php');


$br = array();
// $br['config']['site_url'] = "http://localhost/brsoftsol";
// $br['config']['theme_url'] = "http://localhost/brsoftsol/themes";
// $br['config']['siteTitle'] = "BR SoftSol";
// $br['config']['siteEmail'] = "info@brsofsol.com";
// $br['config']['censored_words'] = "";
// $br['config']['seoLink'] = 0;
// $br['config']['smooth_loading'] = 0;
// $br['config']['useSeoFrindly'] = 0;
// $br['config']['emailValidation'] = 0;
// $br['config']['sms_or_email'] = "mail";
// $br['config']['membership_system'] = 0;
// $br['config']['user_registration'] = 1;
// $br['config']['login_auth'] = 0;
// $br['config']['prevent_system'] = 0;
// $br['config']['siteName'] = "BR SoftSol";
// $br['config']['maintenance_mode'] = 0;
// $br['config']['siteDesc'] = "ERP";
// $br['config']['siteKeywords'] = "BR";
// $br['config']['date_style'] = "m-d-y";
// $br['config']['google_map_api'] = "";
// $br['config']['googleAnalytics'] = "";
// $br['config']['bank_withdrawal_system'] = 0;
// $br['config']['password_complexity_system'] = 0;
// $br['config']['afternoon_system'] = 1;
// $br['config']['smtp_or_mail'] = "mail";
// $br['config']['smtp_host'] = "";
// $br['config']['smtp_username'] = "";
// $br['config']['smtp_password'] = "";
// $br['config']['smtp_port'] = "";
// $br['config']['smtp_encryption'] = 0;
// $br['config']['sms_provider'] = "fast2sms";
// $br['config']['fast2sms_authKey'] = "";
// $br['config']['sms_twilio_username'] = "";
// $br['config']['sms_twilio_password'] = "";
// $br['config']['sms_t_phone_number'] = "";
// $br['config']['sms_phone_number'] = "7017442328";
// $br['config']['developers_page'] = 0;
// $br['config']['user_registration'] = 1;
// $br['config']['emailValidation'] = 0;
// $br['config']['emailNotification'] = 0;
// $br['config']['prevent_system'] = 1;
// $br['config']['bad_login_limit'] = 5;
// $br['config']['lock_time'] = 5;
// $br['config']['user_lastseen'] = 0;
// $br['config']['deleteAccount'] = 0;
// $br['config']['last_backup'] = "00-00-0000";

$br['user']['avatar'] = $site_url."/admin-panel/images/user.png";
$br['script_version'] = "1.0 "."Alpha";

$br['genders'] = array('male', 'female');

$br['loggedin'] = false;
$br['user']['is_pro'] = 0;

$br['terms']['terms_of_use'] = "";
$br['terms']['privacy_policy'] = "";
$br['terms']['about'] = "";
$br['terms']['refund'] = "";


$success_icon = '<i class="fa fa-check"></i> ';
$error_icon   = '<i class="fa fa-exclamation-circle"></i> ';

// Connect to SQL Server
$sqlConnect   = $br['sqlConnect'] = mysqli_connect($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name, 3306);
// Handling Server Errors
$ServerErrors = array();
if (mysqli_connect_errno()) {
    $ServerErrors[] = "Failed to connect to MySQL: " . mysqli_connect_error();
}

$query = mysqli_query($sqlConnect, "SET NAMES utf8mb4");
if (isset($ServerErrors) && !empty($ServerErrors)) {
    foreach ($ServerErrors as $Error) {
        echo "<h3>" . $Error . "</h3>";
    }
    die();
}

$baned_ips = Br_GetBanned('user');
if (in_array($_SERVER["REMOTE_ADDR"], $baned_ips)) {
    exit();
}


$config = Br_GetConfig();

// Config Url
$config['theme_url'] = $site_url . '/themes/' . $config['theme'];
$config['site_url']  = $site_url;
$br['site_url']      = $site_url;

$br['config'] = $config;

$ccode                     = Br_CustomCode('g');
$ccode                     = (is_array($ccode))  ? $ccode    : array();
$br['config']['header_cc'] = (!empty($ccode[0])) ? $ccode[0] : '';
$br['config']['footer_cc'] = (!empty($ccode[1])) ? $ccode[1] : '';
$br['config']['styles_cc'] = (!empty($ccode[2])) ? $ccode[2] : '';


$db = new MysqliDb($sqlConnect);


$br['loggedin_pages'] = array(
    'home',
    'Profile-Feed',
    'edit-profile',
    'Team',
    'start-up',
    'logout',
    'projects',
    'terms'
);

// Get LoggedIn User Data
$br['loggedin'] = false;
if (Br_IsLogged() == true) {
    $session_id         = (!empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : $_COOKIE['user_id'];
    $br['user_session'] = Br_GetUserFromSessionID($session_id);
    $br['user']         = Br_UserData($br['user_session']);
    if ($br['user']['uid'] < 0 || empty($br['user']['uid']) || !is_numeric($br['user']['uid'])) {
        header("Location: " . Br_SeoLink('index.php?link1=logout'));
    }
    
    $br['loggedin'] = true;
}

$br['marker']                   = '?';
if ($br['config']['seoLink'] == 0) {
    $br['marker'] = '&';
}

$emo = array(
    ':)' => 'smile',
    '(&lt;' => 'joy',
    '**)' => 'relaxed',
    ':p' => 'stuck-out-tongue-winking-eye',
    ':_p' => 'stuck-out-tongue',
    'B)' => 'sunglasses',
    ';)' => 'wink',
    ':D' => 'grin',
    '/_)' => 'smirk',
    '0)' => 'innocent',
    ':_(' => 'cry',
    ':__(' => 'sob',
    ':(' => 'disappointed',
    ':*' => 'kissing-heart',
    '&lt;3' => 'heart',
    '&lt;/3' => 'broken-heart',
    '*_*' => 'heart-eyes',
    '&lt;5' => 'star',
    ':o' => 'open-mouth',
    ':0' => 'scream',
    'o(' => 'anguished',
    '-_(' => 'unamused',
    'x(' => 'angry',
    'X(' => 'rage',
    '-_-' => 'expressionless',
    ':-/' => 'confused',
    ':|' => 'neutral-face',
    '!_' => 'exclamation',
    ':|' => 'neutral-face',
    ':|' => 'neutral-face',
    ':yum:' => 'yum',
    ':triumph:' => 'triumph',
    ':imp:' => 'imp',
    ':hear_no_evil:' => 'hear-no-evil',
    ':alien:' => 'alien',
    ':yellow_heart:' => 'yellow-heart',
    ':sleeping:' => 'sleeping',
    ':mask:' => 'mask',
    ':no_mouth:' => 'no-mouth',
    ':weary:' => 'weary',
    ':dizzy_face:' => 'dizzy-face',
    ':man:' => 'man',
    ':woman:' => 'woman',
    ':boy:' => 'boy',
    ':girl:' => 'girl',
    ':оlder_man:' => 'older-man',
    ':оlder_woman:' => 'older-woman',
    ':cop:' => 'cop',
    ':dancers:' => 'dancers',
    ':speak_no_evil:' => 'speak-no-evil',
    ':lips:' => 'lips',
    ':see_no_evil:' => 'see-no-evil',
    ':dog:' => 'dog',
    ':bear:' => 'bear',
    ':rose:' => 'rose',
    ':gift_heart:' => 'gift-heart',
    ':ghost:' => 'ghost',
    ':bell:' => 'bell',
    ':video_game:' => 'video-game',
    ':soccer:' => 'soccer',
    ':books:' => 'books',
    ':moneybag:' => 'moneybag',
    ':mortar_board:' => 'mortar-board',
    ':hand:' => 'hand',
    ':tiger:' => 'tiger',
    ':elephant:' => 'elephant',
    ':scream_cat:' => 'scream-cat',
    ':monkey:' => 'monkey',
    ':bird:' => 'bird',
    ':snowflake:' => 'snowflake',
    ':sunny:' => 'sunny',
    ':оcean:' => 'ocean',
    ':umbrella:' => 'umbrella',
    ':hibiscus:' => 'hibiscus',
    ':tulip:' => 'tulip',
    ':computer:' => 'computer',
    ':bomb:' => 'bomb',
    ':gem:' => 'gem',
    ':ring:' => 'ring'
);

$br['emo']                           = $emo;
$br['redirect']                      = 0;
$br['footer_pages']                  = array(
    'terms',
    'oops',
    'messages',
    'start_up',
    '404',
    'search',
    'admin',
    'user_activation',
    'custom_page',
    'developers',
    'setting',
    'contact-us',
    'advertise'
);