<?php
$page = 'dashboard';

$pages = array(
    'general-settings',
    'dashboard',
    'site-settings',
    'dashboard',
    'site-features',
    'email-settings',
    'manage-users',
    'requested-users',
    'payment-requests',
    'affiliates-settings',
    'payment-settings',
    'manage-admins',
    'user-roles',
    'team-memebers',
    'project-manage',
    'project-view',
    'payments-team',
    'payments-project',
    'manage-templates',
    'issue-certificates',
    'manage-themes',
    'manage-site-design',
    'manage-announcements',
    'mailing-list',
    'mass-notifications',
    'ban-users',
    'generate-sitemap',
    'backups',
    'manage-custom-pages',
    'add-new-custom-page',
    'edit-custom-page',
    'edit-terms-pages',
    'push-notifications-system',
    'manage-api-access-keys',
    'system_status',
    'changelog',
    'online-users',
    'custom-code',
    'send_email',
    'send_sms',
    'product'
);
$mod_pages = array('dashboard', 'post-settings', 'manage-stickers', 'manage-gifts', 'manage-users', 'online-users', 'manage-stories', 'manage-pages', 'manage-groups', 'manage-posts', 'manage-articles', 'manage-events', 'manage-forum-threads', 'manage-forum-messages', 'manage-movies', 'manage-games', 'add-new-game', 'manage-user-ads', 'manage-reports', 'manage-third-psites', 'edit-movie', 'bank-receipts', 'job-categories', 'manage-jobs');


if (!empty($_GET['page'])) {
    $page = Br_Secure($_GET['page'], 0);
}

if ($is_moderoter == true && $is_admin == false) {
    if (!in_array($page, $mod_pages)) {
        header("Location: " . Br_SeoLink('index.php?link1=admin-cp'));
        exit();
    }
}
if (in_array($page, $pages)) {
    $page_loaded = Br_LoadAdminPage("$page/content");
}

if (empty($page_loaded)) {
    header("Location: " . Br_SeoLink('index.php?link1=admin-cp'));
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Admin Panel |
        <?php echo $br['config']['siteTitle']; ?>
    </title>
    <link rel="icon" href="<?php echo $br['config']['theme_url']; ?>/imgs/icon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
        type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <script src="<?php echo Br_LoadAdminLink('plugins/jquery/jquery.min.js'); ?>"></script>
    <link href="<?php echo Br_LoadAdminLink('plugins/bootstrap/css/bootstrap.css'); ?>" rel="stylesheet">
    <link href="<?php echo Br_LoadAdminLink('plugins/node-waves/waves.css'); ?>" rel="stylesheet" />
    <link href="<?php echo Br_LoadAdminLink('plugins/animate-css/animate.css'); ?>" rel="stylesheet" />
    <link href="<?php echo Br_LoadAdminLink('css/style.css'); ?>" rel="stylesheet">
    <link href="<?php echo Br_LoadAdminLink('plugins/sweetalert/sweetalert.css'); ?>" rel="stylesheet" />
    <link href="<?php echo Br_LoadAdminLink('css/themes/all-themes.css'); ?>" rel="stylesheet" />
    <link href="<?php echo Br_LoadAdminLink('plugins/bootstrap-select/css/bootstrap-select.css'); ?>"
        rel="stylesheet" />
    <link href="<?php echo Br_LoadAdminLink('plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css'); ?>"
        rel="stylesheet">
    <link href="<?php echo Br_LoadAdminLink('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css'); ?>"
        rel="stylesheet">
    <script src="<?php echo Br_LoadAdminLink('plugins/codemirror-5.30.0/lib/codemirror.js'); ?>"></script>
    <script src="<?php echo Br_LoadAdminLink('plugins/codemirror-5.30.0/mode/css/css.js'); ?>"></script>
    <script src="<?php echo Br_LoadAdminLink('plugins/jquery.form.min.js'); ?>"></script>
    <script src="<?php echo Br_LoadAdminLink('plugins/codemirror-5.30.0/mode/javascript/javascript.js'); ?>"></script>
    <link href="<?php echo Br_LoadAdminLink('plugins/codemirror-5.30.0/lib/codemirror.css'); ?>" rel="stylesheet">
    <link href="<?php echo $br['config']['theme_url']; ?>/css/font-awesome-4.7.0/css/font-awesome.min.css"
        rel="stylesheet" />
    <script src="<?php echo Br_LoadAdminLink('plugins/m-popup/jquery.magnific-popup.min.js'); ?>"></script>
    <link href="<?php echo Br_LoadAdminLink('plugins/m-popup/magnific-popup.css'); ?>" rel="stylesheet">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script>
        function Br_Ajax_Requests_File() {
            return "<?php echo $br['config']['site_url'] . '/requests.php'; ?>"
        }

        function Br_Ajax_Requests_File_load() {
            return "<?php echo $br['config']['site_url'] . '/admin_load.php'; ?>"
        }
    </script>
</head>

<?php if($br['config']['admincp_ajax_loading'] == 1){ ?>
<script type="text/javascript">
    $(function () {

        $(document).on('click', 'a[data-ajax]', function (e) {
            $(document).off('click', '.ranges ul li');
            $(document).off('click', '.applyBtn');
            e.preventDefault();
            if (($(this)[0].hasAttribute("data-sent") && $(this).attr('data-sent') == '0') || !$(this)[0].hasAttribute("data-sent")) {
                if (!$(this)[0].hasAttribute("data-sent") && $(this).hasClass('waves-effect')) {
                    // $("li").removeClass('active');
                    // var ac = $(this);
                    //$(this).parent().addClass('active');
                }
                //console.log($(this).parent().attr('class'));
                window.history.pushState({
                    state: 'new'
                }, '', $(this).attr('href'));
                $(".barloading").css("display", "block");
                if ($(this)[0].hasAttribute("data-sent")) {
                    $(this).attr('data-sent', "1");
                }
                var url = $(this).attr('data-ajax');
                $.post(Br_Ajax_Requests_File_load() + url, {
                    url: url
                }, function (data) {
                    $(".barloading").css("display", "none");
                    //ac.parent().addClass('active');
                    if ($('#redirect_link')[0].hasAttribute("data-sent")) {
                        $('#redirect_link').attr('data-sent', "0");
                    }
                    //console.log(data);
                    json_data = JSON.parse($(data).filter('#json-data').val());
                    //console.log(json_data);
                    $('.content').html(data);
                    setTimeout(function () {
                        //$(".content").getNiceScroll().resize()
                    }, 500);
                    $(".content").animate({
                        scrollTop: 0
                    }, "slow");
                });
            }
        });
        $(window).on("popstate", function (e) {
            location.reload();
        });
    });
</script>
<?php } ?>
<body class="theme-red">
    <div class="barloading"></div>
    <a id="redirect_link" href="" data-ajax="" data-sent="0"></a>
    <input type="hidden" class="main_session" value="<?php echo Br_CreateMainSession(); ?>">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="<?php echo $br['config']['site_url']; ?>"><img
                        src="<?php echo $br['config']['theme_url']; ?>/imgs/logo.<?php echo $br['config']['logo_extension'];?>" alt="Logo"></a>
            </div>
            <div class="navbar-header pull-right">
                <div class="form-group form-float Br_admin_hdr_srch">
                    <div class="form-line">
                        <input type="text" id="search_for" name="search_for" class="form-control"
                            onkeyup="searchInFiles($(this).val())" placeholder="Search Settings">
                    </div>
                    <div class="Br_admin_hdr_srch_reslts" id="search_for_bar"></div>
                </div>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="<?php echo $br['user']['profile_pic']; ?>" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name">Welcome back, <a href="<?php echo "BR"; ?>" target="_blank">
                            <?php echo $br['user']['name']; ?>
                        </a></div>
                    <div class="name" style="font-size: 12px">Logged in as
                        <?php echo ($is_admin) ? 'Administrator' : 'Moderator' ?>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li <?php echo ($page=='dashboard') ? 'class="active"' : ''; ?>>
                        <a href="<?php echo Br_LoadAdminLinkSettings(''); ?>">
                            <i class="material-icons">dashboard</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                   
                    <?php if ($is_admin == true) { ?>
                    <li <?php echo ($page=='general-settings' || $page=='site-settings' || $page=='email-settings' ||
                        $page=='site-features') ? 'class="active"' : ''; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">settings</i>
                            <span>Settings</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo ($page=='general-settings') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('general-settings'); ?>"
                                    data-ajax="?path=general-settings">General Settings</a>
                            </li>
                            <li <?php echo ($page=='site-settings') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('site-settings'); ?>"
                                    data-ajax="?path=site-settings">Site Settings</a>
                            </li>
                            <li <?php echo ($page=='site-features') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('site-features'); ?>"
                                    data-ajax="?path=site-features">Manage Site Features</a>
                            </li>
                            <li <?php echo ($page=='email-settings') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('email-settings'); ?>"
                                    data-ajax="?path=email-settings">E-mail & SMS Settings</a>
                            </li>
                       
                        </ul>
                        <li <?php echo ($page=='product') ? 'class="active"' : ''; ?>>
                        <a href="<?php echo Br_LoadAdminLinkSettings('product'); ?>">
                            <i class="material-icons">dashboard</i>
                            <span>Product Management</span>
                        </a>
                    </li>
                    </li>
                    <?php } ?>

                    <li <?php echo ($page=='manage-users' || $page=='requested-users' || $page=='affiliates-settings' ||
                        $page=='payment-reqeuests' || $page=='manage-admins') ? 'class="active"' : ''; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">account_circle</i>
                            <span>Users</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo ($page=='manage-users') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('manage-users'); ?>"
                                    data-ajax="?path=manage-users">Manage Users</a>
                            </li>
                            <li <?php echo ($page=='requested-users') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('requested-users'); ?>"
                                    data-ajax="?path=requested-users">Requested Users</a>
                            </li>

                            <?php if ($is_admin == true) { ?>
                            <li <?php echo ($page=='affiliates-settings' || $page=='payment-reqeuests' ||
                                $page=='referrals-list') ? 'class="active"' : ''; ?>>
                                <a href="javascript:void(0);" class="menu-toggle">Affiliates System</a>
                                <ul class="ml-menu">
                                    <li <?php echo ($page=='affiliates-settings') ? 'class="active"' : ''; ?>>
                                        <a href="<?php echo Br_LoadAdminLinkSettings('affiliates-settings'); ?>"
                                            data-ajax="?path=affiliates-settings">
                                            <span>Affiliates Settings</span>
                                        </a>
                                    </li>
                                    <li <?php echo ($page=='payment-requests') ? 'class="active"' : ''; ?>>
                                        <a href="<?php echo Br_LoadAdminLinkSettings('payment-requests'); ?>"
                                            data-ajax="?path=payment-requests">
                                            <span>Payment Requests</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <?php } ?>
                            <?php if ($is_admin == true) { ?>
                            <li <?php echo ($page=='manage-admins') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('manage-admins'); ?>"
                                    data-ajax="?path=manage-admins">Manage Admins</a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php if ($is_admin == true || $is_moderoter == true) { ?>
                    <li <?php echo ($page=='user-roles' || $page=='team-memebers' || $page=='team-projects' ||
                        $page=='team-payments') ? 'class="active"' : ''; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">group</i>
                            <span>Team</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo ($page=='user-roles') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('user-roles'); ?>"
                                    data-ajax="?path=user-roles">User Roles</a>
                            </li>
                            <li <?php echo ($page=='team-memebers') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('team-memebers'); ?>"
                                    data-ajax="?path=team-memebers">Team Members</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php if ($is_admin == true || $is_moderoter == true) { ?>
                    <li <?php echo ($page=='project-manage' || $page=='project-view') ? 'class="active"' : ''; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">picture_in_picture_alt</i>
                            <span>Projects</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo ($page=='project-manage') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('project-manage'); ?>"
                                    data-ajax="?path=project-manage">Manage Projects</a>
                            </li>
                            <li <?php echo ($page=='project-view') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('project-view'); ?>"
                                    data-ajax="?path=project-view">View Projects</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php if ($is_admin == true) { ?>
                    <li <?php echo ($page=='payments-team' || $page=='payments-project') ? 'class="active"' : ''; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">payment</i>
                            <span>Payments</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo ($page=='payments-team') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('payments-team'); ?>"
                                    data-ajax="?path=payments-team">Team Payments</a>
                            </li>
                            <li <?php echo ($page=='payments-project') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('payments-project'); ?>"
                                    data-ajax="?path=payments-project">Project Payments</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php if ($is_admin == true) { ?>
                    <li <?php echo ($page=='manage-templates' || $page=='issue-certificates') ? 'class="active"' : '';
                        ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">description</i>
                            <span>Certificates</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo ($page=='manage-templates') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('manage-templates'); ?>"
                                    data-ajax="?path=manage-templates">Templates</a>
                            </li>
                            <li <?php echo ($page=='issue-certificates') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('issue-certificates'); ?>"
                                    data-ajax="?path=issue-certificates">Issue Certificate</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php if ($is_admin == true) { ?>
                    <li <?php echo ($page=='manage-themes' || $page=='manage-site-design' || $page=='custom-code') ?
                        'class="active"' : ''; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">color_lens</i>
                            <span>Design</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo ($page=='manage-themes') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('manage-themes'); ?>"
                                    data-ajax="?path=manage-themes">Themes</a>
                            </li>
                            <li <?php echo ($page=='manage-site-design') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('manage-site-design'); ?>"
                                    data-ajax="?path=manage-site-design">Change Site Design</a>
                            </li>
                            <li <?php echo ($page=='custom-code') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('custom-code'); ?>"
                                    data-ajax="?path=custom-code">Custom JS / CSS</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if ($is_admin == true || $is_moderoter == true) { ?>
                    <li <?php echo ($page=='send_sms' || $page=='manage-announcements' || $page=='mailing-list' ||
                        $page=='mass-notifications' || $page=='ban-users' || $page=='generate-sitemap' ||
                        $page=='manage-invitation-keys' || $page=='backups' || $page=='auto-delete' ||
                        $page=='auto-friend' || $page=='fake-users' || $page=='auto-like' || $page=='auto-join' ||
                        $page=='send_email' || $page=='manage-invitation') ? 'class="active"' : ''; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">build</i>
                            <span>Tools</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo ($page=='send_sms') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('send_sms'); ?>"
                                    data-ajax="?path=send_sms">Send SMS</a>
                            </li>
                            <li <?php echo ($page=='send_email') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('send_email'); ?>"
                                    data-ajax="?path=send_email">Send E-mail</a>
                            </li>
                            <li <?php echo ($page=='manage-announcements') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('manage-announcements'); ?>"
                                    data-ajax="?path=manage-announcements">Announcements</a>
                            </li>
                            <li <?php echo ($page=='mailing-list') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('mailing-list'); ?>"
                                    data-ajax="?path=mailing-list">Maling List</a>
                            </li>
                            <li <?php echo ($page=='mass-notifications') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('mass-notifications'); ?>"
                                    data-ajax="?path=mass-notifications">Mass Notifications</a>
                            </li>
                            <li <?php echo ($page=='ban-users') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('ban-users'); ?>"
                                    data-ajax="?path=ban-users">BlackList</a>
                            </li>
                            <li <?php echo ($page=='generate-sitemap') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('generate-sitemap'); ?>"
                                    data-ajax="?path=generate-sitemap">Generate SiteMap</a>
                            </li>

                            <li <?php echo ($page=='backups') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('backups'); ?>"
                                    data-ajax="?path=backups">Backup SQL & Files</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if ($is_admin == true || $is_moderoter == true) { ?>
                    <li <?php echo ($page=='edit-terms-pages' || $page=='manage-custom-pages' ||
                        $page=='add-new-custom-page' || $page=='edit-custom-page') ? 'class="active"' : ''; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">description</i>
                            <span>Pages</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo ($page=='manage-custom-pages') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('manage-custom-pages'); ?>"
                                    data-ajax="?path=manage-custom-pages">Manage Custom Pages</a>
                            </li>
                            <li <?php echo ($page=='edit-terms-pages') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('edit-terms-pages'); ?>"
                                    data-ajax="?path=edit-terms-pages">Edit Terms Pages</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php if ($is_admin == true) { ?>
                    <li <?php echo ($page=='push-notifications-system' || $page=='manage-api-access-keys') ?
                        'class="active"' : ''; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">compare_arrows</i>
                            <span>API Settings</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo ($page=='manage-api-access-keys') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('manage-api-access-keys'); ?>"
                                    data-ajax="?path=manage-api-access-keys">Manage API Server Key</a>
                            </li>
                            <li <?php echo ($page=='push-notifications-system') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('push-notifications-system'); ?>"
                                    data-ajax="?path=push-notifications-system">Push Notifications Settings</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php if ($is_admin == true || $is_moderoter == true) { ?>
                    <li <?php echo ($page=='system_status') ? 'class="active"' : ''; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">info</i>
                            <span>System Status</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo ($page=='system_status') ? 'class="active"' : ''; ?>>
                                <a href="<?php echo Br_LoadAdminLinkSettings('system_status'); ?>"
                                    data-ajax="?path=system_status">System Status</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>

                    <?php if ($is_admin == true || $is_moderoter == true) { ?>
                    <li <?php echo ($page=='changelog') ? 'class="active"' : ''; ?>>
                        <a href="<?php echo Br_LoadAdminLinkSettings('changelog'); ?>" data-ajax="?path=changelog">
                            <i class="material-icons">update</i>
                            <span>Changelogs</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if ($is_admin == true || $is_moderoter == true) { ?>
                    <li>
                        <a href="<?php echo Br_LoadAdminLinkSettings('faqs'); ?>" data-ajax="?path=faqs">
                            <i class="material-icons">more_vert</i>
                            <span>FAQs</span>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    Copyright &copy;
                    <?php echo date('Y') ?> <a href="javascript:void(0);">
                        <?php echo $br['config']['siteName'] ?>
                    </a>.
                </div>
                <div class="version">
                    <b>Version: </b>
                    <?php echo $br['script_version'] ?>
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if (is_dir('install')) { ?>
            <div class="alert alert-danger">
                <i class="fa fa-fw fa-exclamation-triangle"></i> <strong>Risk:</strong> Please delete the ./install
                folder for security reasons.
            </div>
            <?php } ?>
            <?php
            //$warnings = Br_GetScriptWarnings();
            $warnings = [];
            if (!empty($warnings)) {
                foreach ($warnings as $key => $value1) { ?>
            <div class="alert alert-warning">
                <i class="fa fa-fw fa-exclamation-circle"></i>
                <?php
                    if ($key == "STRICT_TRANS_TABLES") {
                        echo "<strong>Warning:</strong> The sql-mode <b>STRICT_TRANS_TABLES</b> is enabled in your mysql server, please contact your host provider to disable it.";
                    }
                    if ($key == "STRICT_ALL_TABLES") {
                        echo "<strong>Warning:</strong> The sql-mode <b>STRICT_ALL_TABLES</b> is enabled in your mysql server, please contact your host provider to disable it.";
                    }
                    if ($key == "safe_mode") {
                        echo "<strong>Warning:</strong> The php-mode <b>safe_mode</b> is enabled in your server, please contact your host provider to disable it.";
                    }
                    if ($key == "allow_url_fopen") {
                        echo "<strong>Warning:</strong> The php-extension <b>allow_url_fopen</b> is disabled in your server, please contact your host provider to enable it.";
                    }
                    if ($key == 'update_file') {
                        echo "<strong>Important:</strong> The file <b>update.php</b> is uploaded and not run yet, <a href='" . $br['config']['site_url'] . "/update.php' style='color:#fff; text-decoration:underline;'>Click Here</a> to update the script to v" . $br['script_version'];
                    }
                        ?>
            </div>
            <?php }
            }
            ?>
        </div>
        <?php echo $page_loaded; ?>
    </section>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo Br_LoadAdminLink('plugins/bootstrap/js/bootstrap.js'); ?>"></script>

    <script src="<?php echo Br_LoadAdminLink('plugins/jquery-datatable/jquery.dataTables.js'); ?>"></script>
    <script
        src="<?php echo Br_LoadAdminLink('plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js'); ?>"></script>
    <script
        src="<?php echo Br_LoadAdminLink('plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js'); ?>"></script>
    <script
        src="<?php echo Br_LoadAdminLink('plugins/jquery-datatable/extensions/export/buttons.flash.min.js'); ?>"></script>
    <script src="<?php echo Br_LoadAdminLink('plugins/jquery-datatable/extensions/export/jszip.min.js'); ?>"></script>
    <script src="<?php echo Br_LoadAdminLink('plugins/jquery-datatable/extensions/export/pdfmake.min.js'); ?>"></script>
    <script src="<?php echo Br_LoadAdminLink('plugins/jquery-datatable/extensions/export/vfs_fonts.js'); ?>"></script>
    <script
        src="<?php echo Br_LoadAdminLink('plugins/jquery-datatable/extensions/export/buttons.html5.min.js'); ?>"></script>
    <script
        src="<?php echo Br_LoadAdminLink('plugins/jquery-datatable/extensions/export/buttons.print.min.js'); ?>"></script>
    <script src="<?php echo Br_LoadAdminLink('js/pages/tables/jquery-datatable.js'); ?>"></script>

    <!-- Select Plugin Js -->
    <script src="<?php echo Br_LoadAdminLink('plugins/bootstrap-select/js/bootstrap-select.js'); ?>"></script>
    <script src="<?php echo Br_LoadAdminLink('plugins/sweetalert/sweetalert.min.js'); ?>"></script>

    <!-- ColorPicker Plugin Js -->
    <script src="<?php echo Br_LoadAdminLink('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js'); ?>"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo Br_LoadAdminLink('plugins/jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo Br_LoadAdminLink('plugins/node-waves/waves.js'); ?>"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="<?php echo Br_LoadAdminLink('plugins/jquery-countto/jquery.countTo.js'); ?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo Br_LoadAdminLink('js/admin.js'); ?>"></script>
    <script src="<?php echo Br_LoadAdminLink('js/pages/index.js'); ?>"></script>
</body>

</html>
<style>
    .sidebar .user-info {
        background-size: cover;
    }

    .theme-red .sidebar .menu .list li.active> :first-child i,
    .theme-red .sidebar .menu .list li.active> :first-child span {
        color: <?php echo $br['config']['btn_background_color'] ?>;
    }

    .theme-red .navbar {
        background: <?php echo $br['config']['header_background'] ?>;
    }

    .sidebar .user-info {
        /* background: <?php // echo $br['config']['btn_background_color'] ?> !important; */
    }

    [type="radio"]:checked+label:after,
    [type="radio"].with-gap:checked+label:after {
        background-color: <?php echo $br['config']['btn_background_color'] ?> !important;
    }

    [type="radio"]:checked+label:after,
    [type="radio"].with-gap:checked+label:before,
    [type="radio"].with-gap:checked+label:after {
        border: 2px solid <?php echo $br['config']['btn_background_color'] ?> !important;
    }

    .btn-primary,
    .btn-primary:hover,
    .btn-primary:active,
    .btn-primary:focus {
        background-color: <?php echo $br['config']['btn_background_color'] ?> !important;
    }

    .sidebar .user-info {
        height: 135px !important;
    }

    .sidebar .menu .list .ml-menu span {
        margin: 0 !important;
    }

    .sidebar .menu .list .ml-menu li.active a.toggled:not(.menu-toggle):before,
    .sidebar .menu .list .ml-menu li.active a.toggled:not(.menu-toggle),
    .theme-red .sidebar .legal .copyright a {
        color: <?php echo $br['config']['btn_background_color'] ?> !important;
    }

    .spinner-layer.pl-red {
        border-color: <?php echo $br['config']['btn_background_color'] ?>;
    }
</style>

<script>
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();
        var hash = $('.main_session').val();
        $.ajaxSetup({
            data: {
                hash: hash
            },
            cache: false
        });
    });



    function searchInFiles(keyword) {
        if (keyword.length > 2) {
            $.post(Br_Ajax_Requests_File() + '?f=admin_setting&s=search_in_pages', {
                keyword: keyword
            }, function (data, textStatus, xhr) {
                if (data.html != '') {
                    $('#search_for_bar').html(data.html)
                } else {
                    $('#search_for_bar').html('')
                }
            });
        } else {
            $('#search_for_bar').html('')
        }
    }
    $(window).load(function () {
        jQuery.fn.highlight = function (str, className) {
            if (str != '') {
                var aTags = document.getElementsByTagName("h2");
                var bTags = document.getElementsByTagName("label");
                var searchText = str.toLowerCase();

                if (aTags.length > 0) {
                    for (var i = 0; i < aTags.length; i++) {
                        var tag_text = aTags[i].textContent.toLowerCase();
                        if (tag_text.indexOf(searchText) != -1) {
                            $(aTags[i]).addClass(className)
                        }
                    }
                }

                if (bTags.length > 0) {
                    for (var i = 0; i < bTags.length; i++) {
                        var tag_text = bTags[i].textContent.toLowerCase();
                        if (tag_text.indexOf(searchText) != -1) {
                            $(bTags[i]).addClass(className)
                        }
                    }
                }
            }
        };
        jQuery.fn.highlight("<?php echo (!empty($_GET['highlight']) ? $_GET['highlight'] : '') ?>", 'highlight_text');
    });
</script>