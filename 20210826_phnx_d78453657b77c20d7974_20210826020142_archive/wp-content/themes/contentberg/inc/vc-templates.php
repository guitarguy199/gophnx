<?php
/**
 * Visual Composer templates for import
 */

$home = <<<EOF
[vc_row][vc_column width="2/3"][blog type="" show_excerpt="1" show_footer="1" pagination="" pagination_type="load-more" posts="1" sort_by="" sort_order="desc" heading="" heading_type="head-c" view_all="" link="" offset="" cat="" terms="" tags="" post_format="" post_type=""][/vc_column][vc_column width="1/3"][vc_widget_sidebar is_sidebar="1" is_sticky="1" sidebar_id="contentberg-home-1"][/vc_column][/vc_row][vc_row][vc_column][ts_ads code="JTNDaW1nJTIwc3JjJTNEJTIyaHR0cHMlM0ElMkYlMkZjb250ZW50YmxvZy50aGVtZS1zcGhlcmUuY29tJTJGd3AtY29udGVudCUyRnVwbG9hZHMlMkYyMDE4JTJGMDklMkZhZDQuanBnJTIyJTIwJTJGJTNF"][/vc_column][/vc_row][vc_row][vc_column][loop_grid type="loop-grid" show_excerpt="1" show_footer="" pagination="1" pagination_type="load-more" posts="2" sort_by="" sort_order="desc" heading="News & Trends" heading_type="head-c" view_all="View All" link="" offset="" cat="5" terms="" tags="" post_format="" post_type="post" excerpt_length=""][/vc_column][/vc_row][vc_row][vc_column][loop_grid_3 type="loop-grid-3" show_excerpt="1" show_footer="" pagination="1" pagination_type="load-more" posts="6" sort_by="" sort_order="desc" heading="" heading_type="head-c" view_all="View All" link="" offset="" cat="3" terms="" tags="" post_format="" post_type="" excerpt_length="12"][/vc_column][/vc_row][vc_row][vc_column][news_grid posts="5" sort_by="" sort_order="desc" heading="" heading_type="head-c" view_all="View All" link="" offset="1" cat="4" terms="" tags="" post_format="" post_type=""][/vc_column][/vc_row][vc_row][vc_column][loop_grid type="loop-grid" show_excerpt="1" show_footer="" pagination="1" pagination_type="load-more" posts="2" sort_by="" sort_order="desc" heading="" heading_type="head-c" view_all="View All" link="" offset="" cat="2" terms="" tags="" post_format="" post_type="" excerpt_length=""][/vc_column][/vc_row][vc_row][vc_column width="2/3"][blog type="" show_excerpt="1" show_footer="1" pagination="1" pagination_type="load-more" posts="3" sort_by="" sort_order="desc" heading="Latest" heading_type="head-c" view_all="" link="" offset="3" cat="" terms="" tags="" post_format="" post_type="post"][/vc_column][vc_column width="1/3"][vc_widget_sidebar is_sidebar="1" is_sticky="1" sidebar_id="contentberg-home-2"][/vc_column][/vc_row]
EOF;

$home_2 = <<<EOF
[vc_row][vc_column][blog type="loop-1st-large" show_excerpt="" show_footer="" pagination="1" pagination_type="load-more" posts="5" sort_by="" sort_order="desc" heading="" heading_type="head-c" view_all="" link="" offset="1" cat="4" terms="" tags="" post_format="" post_type="post"][/vc_column][/vc_row][vc_row][vc_column][blog type="loop-grid-3" show_excerpt="" show_footer="" pagination="1" pagination_type="load-more" posts="6" sort_by="" sort_order="desc" heading="" heading_type="head-c" view_all="" link="" offset="" cat="5" terms="" tags="" post_format="" post_type=""][/vc_column][/vc_row]
EOF;

$home_3 = <<<EOF
[vc_row][vc_column][ts_ads code="JTNDaW1nJTIwc3JjJTNEJTIyaHR0cHMlM0ElMkYlMkZjb250ZW50YmVyZy50aGVtZS1zcGhlcmUuY29tJTJGd3AtY29udGVudCUyRnVwbG9hZHMlMkYyMDE4JTJGMDklMkZhZDQuanBnJTIyJTIwJTJGJTNF"][/vc_column][/vc_row][vc_row][vc_column width="2/3"][loop_1_2_overlay_list type="loop-1-2-overlay-list" show_excerpt="1" show_footer="1" pagination="1" pagination_type="numbers" posts="9" sort_by="" sort_order="desc" heading="" heading_type="head-c" view_all="" link="" offset="" cat="3" terms="" tags="" post_format="" post_type=""][/vc_column][vc_column width="1/3"][vc_widget_sidebar is_sidebar="1" is_sticky="1" sidebar_id="contentberg-primary"][/vc_column][/vc_row][vc_row][vc_column][vc_separator color="custom" accent_color="#f2f2f2"][/vc_column][/vc_row][vc_row][vc_column][loop_1st_overlay_list type="loop-1st-overlay-list" show_excerpt="1" show_footer="1" pagination="" pagination_type="load-more" posts="1" sort_by="" sort_order="desc" heading="" heading_type="head-c" view_all="" link="" offset="2" cat="" terms="" tags="" post_format="" post_type="post"][/vc_column][/vc_row][vc_row][vc_column][news_grid posts="5" sort_by="" sort_order="desc" heading="" heading_type="head-c" view_all="" link="" offset="1" cat="4" terms="" tags="" post_format="" post_type="post"][/vc_column][/vc_row]
EOF;

$home_4 = <<<EOF
[vc_row][vc_column][loop_grid_3 type="loop-grid-3" show_excerpt="1" show_footer="1" pagination="1" pagination_type="load-more" posts="6" sort_by="" sort_order="desc" heading="Gadgets" heading_type="head-c" view_all="" link="" offset="" cat="3" terms="" tags="" post_format="" post_type="post" excerpt_length=""][/vc_column][/vc_row][vc_row][vc_column width="2/3"][loop_1st_large type="loop-1st-large" show_excerpt="" show_footer="" pagination="1" pagination_type="load-more" posts="13" sort_by="" sort_order="desc" heading="Recent" heading_type="head-c" view_all="" link="" offset="3" cat="" terms="" tags="" post_format="" post_type="post"][/vc_column][vc_column width="1/3"][vc_widget_sidebar is_sidebar="1" is_sticky="1" sidebar_id="contentberg-primary"][/vc_column][/vc_row]
EOF;

return apply_filters('bunyad_vc_templates', array(
	array(
		'name' => 'Default Home 1',
		'content' => $home,
	),
		
	array(
		'name' => 'Alt Home 2',
		'content' => $home_2,
	),

				
	array(
		'name' => 'Alt Home 3',
		'content' => $home_3,
	),
		
				
	array(
		'name' => 'Alt Home 4',
		'content' => $home_4,
	),
));