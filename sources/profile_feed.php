<?php
$br['description'] = $br['config']['siteDesc'];
$br['keywords']    = $br['config']['siteKeywords'];
$br['page']        = 'Profile Feed';
$br['title']       = "Profile Feed" . ' | ' . $br['config']['siteTitle'];
$br['content']     = Br_LoadPage('user/profile_feed');
