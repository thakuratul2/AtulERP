<?php
header("HTTP/1.0 404 Not Found");
$br['description'] = '';
$br['keywords']    = '';
$br['page']        = '404';
$br['title']       = 'Sorry Page Not Found'. " | " .$br['config']['siteTitle'];
$br['content']     = Br_LoadPage('404/content');