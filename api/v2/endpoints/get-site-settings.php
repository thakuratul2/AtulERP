<?php
$get_config = Br_GetConfig();
foreach ($non_allowed_config as $key => $value) {
    unset($get_config[$value]);
}
$get_config['logo_url'] = $config['theme_url'] . '/img/logo.' . $get_config['logo_extension'];
$get_config['page_categories'] = $br['page_categories'];
$get_config['group_categories'] = $br['group_categories'];
$get_config['blog_categories'] = $br['blog_categories'];
$get_config['products_categories'] = $br['products_categories'];
$get_config['job_categories'] = $br['job_categories'];
$get_config['genders'] = $br['genders'];
$get_config['currency_array'] = (Array) json_decode($get_config['currency_array']);
$get_config['currency_symbol_array'] = (Array) json_decode($get_config['currency_symbol_array']);
foreach ($br['family'] as $key => $value) {
	$br['family'][$key] = $br['lang'][$value];
}
$get_config['family'] = $br['family'];
if (!empty($br['post_colors'])) {
	foreach ($br['post_colors'] as $key => $color) {
		if (!empty($color->image)) {
			$br['post_colors'][$key]->image = Br_GetMedia($color->image);
		}
	}
}
$get_config['fields'] = Br_GetUserCustomFields();
$get_config['movie_category'] = $br['film-genres'];
$get_config['post_colors'] = $br['post_colors'];
$get_config['page_sub_categories'] = $br['page_sub_categories'];
$get_config['group_sub_categories'] = $br['group_sub_categories'];
$get_config['products_sub_categories'] = $br['products_sub_categories'];

$get_config['page_custom_fields'] = Br_GetCustomFields('page');
$get_config['group_custom_fields'] = Br_GetCustomFields('group');
$get_config['product_custom_fields'] = Br_GetCustomFields('product');

$get_config['post_reactions_types'] = $br['reactions_types'];
$response_data      = array(
    'api_status' => 200,
    'config' => $get_config
);