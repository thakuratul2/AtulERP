<?php
require 'assets/init.php';

$is_admin = Br_IsAdmin();
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

// autoload admin panel files
require 'admin-panel/autoload.php';