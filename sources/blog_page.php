<?php

$br['description'] = $br['config']['siteDesc'];
$br['keywords']    = $br['config']['siteKeywords'];
$br['page']        = 'Blog';
$br['title']       = "Blog Page".' | '.$br['config']['siteTitle'];
$br['content']     = Br_LoadPage('blog/content');