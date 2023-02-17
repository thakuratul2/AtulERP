<?php
// BR
require_once('assets/init.php');
$is_admin     = Br_IsAdmin();
$is_moderoter = Br_IsModerator();
if ($br['config']['maintenance_mode'] == 1) {
    if ($br['loggedin'] == false) {
        header("Location: " . Br_SeoLink('index.php?link1=welcome') . $br['marker'] . 'm=true');
        exit();
    } else {
        if ($is_admin === false) {
            header("Location: " . Br_SeoLink('index.php?link1=welcome') . $br['marker'] . 'm=true');
            exit();
        }
    }
}
if ($is_admin == false && $is_moderoter == false) {
    header("Location: " . Br_SeoLink('index.php?link1=welcome'));
    exit();
}
if (!empty($_GET)) {
    foreach ($_GET as $key => $value) {
        $value      = preg_replace('/on[^<>=]+=[^<>]*/m', '', $value);
        $_GET[$key] = strip_tags($value);
    }
}
if (!empty($_REQUEST)) {
    foreach ($_REQUEST as $key => $value) {
        $value          = preg_replace('/on[^<>=]+=[^<>]*/m', '', $value);
        $_REQUEST[$key] = strip_tags($value);
    }
}
if (!empty($_POST)) {
    foreach ($_POST as $key => $value) {
        $value       = preg_replace('/on[^<>=]+=[^<>]*/m', '', $value);
        $_POST[$key] = strip_tags($value);
    }
}
$path  = (!empty($_GET['path'])) ? getPageFromPath($_GET['path']) : null;
$files = scandir('admin-panel/pages');
unset($files[0]);
unset($files[1]);
unset($files[2]);
$page = 'dashboard';
if (!empty($path['page']) && in_array($path['page'], $files) && file_exists('admin-panel/pages/' . $path['page'] . '/content.phtml')) {
    $page = $path['page'];
}
// $br['user']['permission'] = json_decode($br['user']['permission'], true);
// if (!empty($br['user']['permission'][$page])) {
//   if (!empty($br['user']['permission']) && $br['user']['permission'][$page] == 0) {
//       header("Location: " . Br_SeoLink('index.php?link1=welcome'));
//       exit();
//   }
// }
$data = array();
$text = Br_LoadAdminPage($page . '/content');
?>
<input type="hidden" id="json-data" value='<?php
echo htmlspecialchars(json_encode($data));
?>'>
<?php
echo $text;
?>
