<?php
require_once('assets/init.php');

if ($br['loggedin'] == true) {
    $update_last_seen = Br_LastSeen($br['user']['uid']);
}
$page = '';
if ($br['loggedin'] == true && !isset($_GET['link1'])) {
    $page = 'home';
} elseif (isset($_GET['link1'])) {
    $page = Br_Secure($_GET['link1'], 0);
}
if ((!isset($_GET['link1']) && $br['loggedin'] == false) || (isset($_GET['link1']) && $br['loggedin'] == false && $page == 'home')) {
    $page = 'welcome';
}
$came_from = false;
if ($page == 'timeline') {
    $came_from = true;
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        exit("Restrcited Area");
    }
} else {
    exit("Restrcited Area");
}


if ($br['loggedin'] == true) {
    switch ($page) {
        case 'maintenance':
            include('sources/maintenance.php');
            break;
        case 'home':
            include('sources/home.php');
            break;
        case 'welcome':
            include('sources/welcome.php');
            break;
        case 'register':
            include('sources/register.php');
            break;
        case 'confirm-sms':
            include('sources/confirm_sms.php');
            break;
        case 'confirm-sms-password':
            include('sources/confirm_sms_password.php');
            break;
        case 'forgot-password':
            include('sources/forgot_password.php');
            break;
        case 'reset-password':
            include('sources/reset_password.php');
            break;
        case 'start-up':
            include('sources/start_up.php');
            break;
        case 'search':
            include('sources/search.php');
            break;
        case 'logout':
            include('sources/logout.php');
            break;
        case 'terms':
            include('sources/term.php');
            break;
        case 'contact-us':
            include('sources/contact.php');
            break;
        case 'oops':
            include('sources/oops.php');
            break;
    }
} else {
    switch ($page) {
        case 'maintenance':
            include('sources/maintenance.php');
            break;
        case 'welcome':
            include('sources/welcome.php');
            break;
        case 'register':
            include('sources/register.php');
            break;
        case 'terms':
            include('sources/term.php');
            break;
    }
}


if (empty($br['content'])) {
    include('sources/404.php');
}
$data = array();
if (empty($br['title'])) {
    $data['title'] = $br['config']['siteTitle'];
}
$data['url'] = '';
$actual_link = "http://$_SERVER[HTTP_HOST]";
$data['title'] = stripslashes(Br_Secure($br['title']));
$data['page'] = $br['page'];
$data['welcome_page'] = 0;
$data['is_css_file'] = 0;
$data['css_file_header'] = '';
$data['welcome_url'] = Br_SeoLink('index.php?link1=welcome');
if ($br['page'] == 'welcome') {
    $data['welcome_page'] = 1;
}

$data['is_footer'] = 0;
if (in_array($br['page'], $br['footer_pages'])) {
    $data['is_footer'] = 1;
}
$url = '';
if (!empty($_POST['url'])) {
    $url = $_POST['url'];
}
$data['redirect'] = 0;
if ($br['redirect'] == 1) {
    $data['redirect'] = 1;
}

$data['url'] = Br_SeoLink('index.php' . $url);
?>
<input type="hidden" id="json-data" value='<?php echo htmlspecialchars(json_encode($data)); ?>'>
<?php
echo $br['content'];
?>