<?php
// +------------------------------------------------------------------------+
// | @author Brijesh Chauhan
// | @author_url: http://www.brsoftsol.com
// | @author_team: BR Team
// | @author_email: brijeshch80580@gmail.com   
// +------------------------------------------------------------------------+
// | Copyright (c) 2022 BR SoftSol. All rights reserved.
// +------------------------------------------------------------------------+
require_once('assets/init.php');
if ($br['loggedin'] == true) {
    $update_last_seen = Br_LastSeen($br['user']['uid']);
} else if (!empty($_SERVER['HTTP_HOST'])) {
}
if (!isset($_COOKIE['src'])) {
    @setcookie('src', '1', time() + 31556926, '/');
}
$page = '';
if ($br['loggedin'] == true && !isset($_GET['link1'])) {
    $page = 'home';
} elseif (isset($_GET['link1'])) {
    $page = $_GET['link1'];
}
if ((!isset($_GET['link1']) && $br['loggedin'] == false) || (isset($_GET['link1']) && $br['loggedin'] == false && $page == 'home')) {
    $page = 'welcome';
}
if ($br['config']['maintenance_mode'] == 1) {
    if ($br['loggedin'] == false) {
        if ($page == 'admincp' || $page == 'admin-cp') {
            $page = 'welcome';
        } else {
            $page = 'maintenance';
        }
    } else {
        if (Br_IsAdmin() === false) {
            $page = 'maintenance';
        }
    }
}
if (!empty($_GET['m'])) {
    $page = 'welcome';
}

//printArray($br);
//$page = "start-up";
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
        case 'login':
            include('sources/login.php');
            break;
        case 'start-up':
            include('sources/start_up.php');
            break;
        case 'user':
            include('sources/user.php');
            break;
        case 'profile-feed':
            include('sources/profile_feed.php');
            break;
        case 'edit-profile':
            include('sources/edit_profile.php');
            break;
        case 'certificates':
            include('sources/certificates.php');
            break;
        case 'documents':
            include('sources/documents.php');
            break;
        case 'idcard':
            include('sources/idcard.php');
            break;
        case 'team':
            include('sources/team.php');
            break;
        case 'projects':
            include('sources/projects.php');
            break;
        case 'logout':
            include('sources/logout.php');
            break;
        case '404':
            include('sources/404.php');
            break;
        case 'terms':
            include('sources/term.php');
            break;
        case 'site-pages':
            include('sources/site_pages.php');
            break;
        case 'oops':
            include('sources/oops.php');
            break;
        case 'contact-us':
            include('sources/contact.php');
            break;
        case 'company-about':
            include('sources/company_about.php');
            break;
        case 'company-services':
            include('sources/company_services.php');
            break;
        case 'company-client':
            include('sources/company_clients.php');
            break;
        case 'blog-page':
            include('sources/blog_page.php');
            break;
    }
} else {
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
        case 'login':
            include('sources/login.php');
            break;
        case 'forgot-password':
            include('sources/forgot_password.php');
            break;
        case 'reset-password':
            include('sources/reset_password.php');
            break;
        case 'activate':
            include('sources/activate.php');
            break;
        case 'confirm-sms':
            include('sources/confirm_sms.php');
            break;
        case 'confirm-sms-password':
            include('sources/confirm_sms_password.php');
            break;
        case 'unusual-login':
            include('sources/unusual_login.php');
            break;
        case 'user-activation':
            include('sources/user_activation.php');
            break;
        case 'user':
            include('sources/user.php');
            break;
        case 'logout':
            include('sources/logout.php');
            break;
        case '404':
            include('sources/404.php');
            break;
        case 'contact-us':
            include('sources/contact.php');
            break;
        case 'oops':
            include('sources/oops.php');
            break;
        case 'terms':
            include('sources/term.php');
            break;
        case 'site-pages':
            include('sources/site_pages.php');
            break;
        case 'company-about':
            include('sources/company_about.php');
            break;
        case 'company-services':
            include('sources/company_services.php');
            break;
        case 'company-affiliate':
            include('sources/company_affiliate.php');
            break;
        case 'blog-page':
            include('sources/blog_page.php');
            break;
    }
}



if (empty($br['content'])) {
    include('sources/404.php');
}

echo Br_Loadpage('container');

mysqli_close($sqlConnect);
unset($br);
