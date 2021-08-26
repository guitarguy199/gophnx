<?php
/**
 * Theme Settings - All the relevant options!
 * 
 * @see Bunyad_Options
 * @see Bunyad_Theme_Customizer
 */


$info = <<<EOF
	
	<p>To get up and running with the theme, start with <a href="https://contentberg.theme-sphere.com/documentation/" target="_blank">theme documentation</a>.</p>
	
	<p>Resources:</p>
	<ul>
		<li>- <a href="https://contentberg.theme-sphere.com/documentation/" target="_blank">Documentation</a></li>
		<li>- <a href="https://theme-sphere.com" target="_blank">Theme Support</a></li>
		<li>- <a href="https://theme-sphere.com/feedback/" target="_blank">Suggestions & Feedback?</a> (We want to know if your experience has been pleasant)</li>
	</ul>
	
EOF;

$privacy_info = <<<EOF

	<p>ContentBerg by itself is compliant with EU GDPR laws and offers a guide and further tools to help your site become compliant.</p>

	<p>We cannot offer legal advice, but we have some exclusive plugins and have added support for relevant plugins. We have created a few helpful guides here:</p>

	<ul>
		<li>- <a href="https://contentberg.theme-sphere.com/documentation/#gdpr-guide" target="_blank"><strong>GDPR Main Guide</strong></a></li>
		<li>- <a href="https://contentberg.theme-sphere.com/documentation/#gdpr-mailchimp" target="_blank">MailChimp Consent</a></li>
		<li>- <a href="https://contentberg.theme-sphere.com/documentation/#gdpr-google-fonts" target="_blank">Google Fonts</a></li>
		<li>- <a href="https://contentberg.theme-sphere.com/documentation/#gdpr-google-analytics" target="_blank">Google Analytics</a></li>
		<li>- <a href="https://contentberg.theme-sphere.com/documentation/#gdpr-cookie-notices" target="_blank">Cookie Notice</a></li>
	</ul>

EOF;

return apply_filters('bunyad_theme_options', array(

	array(
		'sections' => array(
			array(
				'id' => 'options-welcome',
				'title'  => esc_html_x('Theme Intro & Help', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name' => 'welcome',
						'type' => 'content',
						'text' => $info
					),

					array(
						'name' => 'predefined_style',
						'value'   => '',
						'desc'    => '',
						'type'    => 'hidden'
					),
				) // fields
				
			) // section
			
		) // sections
		
	), // pseudo panel
	
	array(
		'title' => esc_html_x('Homepage', 'Admin', 'contentberg'),
		'id'    => 'options-homepage',
		'sections' => array(
				
			array(
				'id' => 'home-layout',
				'title'  => esc_html_x('Home Layout', 'Admin', 'contentberg'),
				'fields' => array(

					array(
						'name' => 'home_widgets',
						'label'   => esc_html_x('Widgetized Homepage?', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => esc_html_x('Build your homepage by placing widgets into widget area "Homepage Widgets". If no widgets are set, will fallback to home layout.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
					),


					array(
						'name'  => 'home_widgets_info',
						'label' => esc_html_x('Setting Home Widgets', 'Admin', 'contentberg'),
						'type'  => 'content',
						'text'  => sprintf(
							esc_html_x('Go to Appearance > Widgets and add ContentBerg Home widgets to widget area named %1$sHomepage Widgets%2$s', 'Admin', 'contentberg'), 
							'<strong>', '</strong>'
						) . '<hr />',
						'context' => array('control' => array('key' => 'home_widgets', 'value' => 1))
					),
			
					array(
						'name' => 'home_layout',
						'label'   => esc_html_x('Home Layout', 'Admin', 'contentberg'),
						'value'   => '',
						'desc'    => esc_html_x('Note: If Widgetized Homepage is enabled and has widgets, this will do nothing.', 'Admin', 'contentberg'),
						'type'    => 'radio',
						'options' => array(
							'' => esc_html_x('Classic Large Posts', 'Admin', 'contentberg'),
							'loop-1st-large' => esc_html_x('One Large Post + Grid', 'Admin', 'contentberg'),
							'loop-1st-large-list' => esc_html_x('One Large Post + List', 'Admin', 'contentberg'),
							'loop-1st-overlay' => esc_html_x('One Overlay Post + Grid', 'Admin', 'contentberg'),
							'loop-1st-overlay-list' => esc_html_x('One Overlay Post + List', 'Admin', 'contentberg'),
								
							'loop-1-2' => esc_html_x('Mixed: Large Post + 2 Grid ', 'Admin', 'contentberg'),
							'loop-1-2-list' => esc_html_x('Mixed: Large Post + 2 List ', 'Admin', 'contentberg'),

							'loop-1-2-overlay' => esc_html_x('Mixed: Overlay Post + 2 Grid ', 'Admin', 'contentberg'),
							'loop-1-2-overlay-list' => esc_html_x('Mixed: Overlay Post + 2 List ', 'Admin', 'contentberg'),
								
								
							'loop-list' => esc_html_x('List Posts', 'Admin', 'contentberg'),
							'loop-grid' => esc_html_x('Grid Posts', 'Admin', 'contentberg'),
							'loop-grid-3' => esc_html_x('Grid Posts (3 Columns)', 'Admin', 'contentberg'),
						),
					),

						
					array(
						'name'  => 'home_grid_large_info',
						'label' => esc_html_x('Grid / Large Post Style | Pagination', 'Admin', 'contentberg'),
						'type'  => 'content',
						'text'  => sprintf(
							esc_html_x('There are multiple Grid Posts, Large Posts, and Pagination styles available. Make your choice by going back and to %1$sPosts & Listings > Post Listings%2$s.', 'Admin', 'contentberg'),
							'<a href="#" class="focus-link" data-section="posts-listings">', '</a>'
						)
					),
					
					array(
						'name' => 'home_sidebar',
						'label'   => esc_html_x('Home Sidebar', 'Admin', 'contentberg'),
						'value'   => 'right',
						'desc'    => '',
						'type'    => 'radio',
						'options' => array(
							'none'  => esc_html_x('No Sidebar', 'Admin', 'contentberg'),
							'right' => esc_html_x('Right Sidebar', 'Admin', 'contentberg') 
						),
					),
						
					array(
						'name'  => 'home_posts_limit',
						'label' => esc_html_x('Number of Posts', 'Admin', 'contentberg'),
						'value' => get_option('posts_per_page'),
						'desc'  => esc_html_x('When you wish to use different posts per page from global setting in Settings > Reading which applies to all archives too.', 'Admin', 'contentberg'),
						'type'  => 'number',
					),
					
				), // fields
				
			), // section
			
			array(
				'id' => 'home-slider',
				'title' => esc_html_x('Home Slider', 'Admin', 'contentberg'),
				'fields' => array(
					array(
						'name' => 'home_slider',
						'label'   => esc_html_x('Slider on Home', 'Admin', 'contentberg'),
						'value'   => '',
						'desc'    => '',
						'type'    => 'select',
						'options' => array(
							''        => esc_html_x('Disabled', 'Admin', 'contentberg'),
							'stylish' => esc_html_x('Stylish (3 images)', 'Admin', 'contentberg'),
							'fashion' => esc_html_x('Fashion (Single Image)', 'Admin', 'contentberg'),
							'grid-tall' => esc_html_x('Tall Grid (1 Large + 2 small)', 'Admin', 'contentberg'),
							'default'   => esc_html_x('Classic Slider (3 Images)', 'Admin', 'contentberg'),
							'carousel'  => esc_html_x('Carousel (3 Small Posts)', 'Admin', 'contentberg'),
							'bold'    => esc_html_x('Bold Full Width', 'Admin', 'contentberg'),
						),
					),
					
					
					array(
						'name' => 'slider_posts',
						'label'   => esc_html_x('Slider Post Count', 'Admin', 'contentberg'),
						'value'   => 6,
						'desc'    => esc_html_x('Total number of posts for slider.', 'Admin', 'contentberg'),
						'type'    => 'number',
					),
					
					array(
						'name' => 'slider_tag',
						'label'   => esc_html_x('Slider Posts Tag', 'Admin', 'contentberg'),
						'value'   => 'featured',
						'desc'    => esc_html_x('Posts with this tag will be shown in the slider. Leaving it empty will show latest posts.', 'Admin', 'contentberg'),
						'type'    => 'text',
					),
						
					array(
						'name' => 'slider_post_ids',
						'label'   => esc_html_x('Slider Post IDs', 'Admin', 'contentberg'),
						'value'   => '',
						'desc'    => esc_html_x('Advance Usage: Enter post ids separated by comma you wish to show in the slider, in order you wish to show them in. Example: 11, 105, 2', 'Admin', 'contentberg'),
						'type'    => 'text',
					),
						
					array(
						'name'    => 'slider_parallax',
						'label'   => esc_html_x('Enable Parallax Effect?', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => '',
						'type'    => 'checkbox',
					),
					
					array(
						'name'    => 'slider_autoplay',
						'label'   => esc_html_x('Slider Autoplay', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => '',
						'type'    => 'checkbox',
					),
					
					array(
						'name'    => 'slider_animation',
						'label'   => esc_html_x('Slider Animation', 'Admin', 'contentberg'),
						'value'   => 'fade',
						'desc'    => '',
						'type'    => 'radio',
						'options' => array(
							'fade'  => esc_html_x('Fade Animation', 'Admin', 'contentberg'),
							'slide' => esc_html_x('Slide Animation', 'Admin', 'contentberg'),
						),
						'context' => array('control' => array('key' => 'home_slider', 'value' => array('beauty', 'fashion', 'large', 'bold')))
					),
					
					array(
						'name'    => 'slider_delay',
						'label'   => esc_html_x('Slide Autoplay Delay', 'Admin', 'contentberg'),
						'value'   => 5000,
						'desc'    => '',
						'type'    => 'number',
						'input_attrs' => array('min' => 500, 'max' => 50000, 'step' => 500),
					),
						
				), // fields
			), // section
				
			array(
				'id' => 'home-subscribe',
				'title' => esc_html_x('Home Subscribe', 'Admin', 'contentberg'),
				'desc'  => 
					sprintf(
						esc_html_x('Enable a Mailchimp subscribe box below the slider on home. IMPORTANT: Setup your form first by following %sthis guide%s.', 'Admin', 'contentberg'),
						'<a href="https://contentberg.theme-sphere.com/documentation/#widget-subscribe" target="_blank">', '</a>'
					),
				'fields' => array(
					array(
						'name'  => 'home_subscribe',
						'label' => esc_html_x('Enable Subscribe Box?', 'Admin', 'contentberg'),
						'value' => 0,
						'desc' => '',
						'type'  => 'checkbox',
					),
						
					array(
						'name' => 'home_subscribe_url',
						'label' => esc_html_x('Mailchimp Form URL', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text',
					),
						
					array(
						'name' => 'home_subscribe_label',
						'label' => esc_html_x('Subscribe Message', 'Admin', 'contentberg'),
						'value' => esc_html__('Subscribe to my newsletter to get updates in your inbox!', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),

					array(
						'name' => 'home_subscribe_btn_label',
						'label' => esc_html_x('Button Label', 'Admin', 'contentberg'),
						'value' => esc_html__('Subscribe Now', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
						
				)
			), // section

			array(
				'id' => 'home-cta',
				'title' => esc_html_x('Home Call to Action Boxes', 'Admin', 'contentberg'),
				'desc'  => '',
				'fields' => array(
					array(
						'name'  => 'home_cta',
						'label' => esc_html_x('Enable Home CTA?', 'Admin', 'contentberg'),
						'value' => 1,
						'desc' => '',
						'type'  => 'checkbox',
					),

					array(
						'name'  => 'home_cta_info',
						'label' => esc_html_x('Enabling CTAs', 'Admin', 'contentberg'),
						'type'  => 'content',
						'text'  => sprintf(
							esc_html_x('After enabling the CTAs, you have to add the widget named ContentBerg - CTA to the widget area "Home Call To Action Boxes". You can do so from %1$sWidgets > Home Call to Action Boxes%2$s.', 'Admin', 'contentberg'),
							'<a href="#" class="focus-link" data-section="sidebar-widgets-contentberg-home-cta">', '</a>'
						)
					),
						
				)
			), // section
					
			
				
		) // sections
	), // panel

			
	array(
		'title' => esc_html_x('Header/Logo & Nav', 'Admin', 'contentberg'),
		'id'    => 'sphere-header',
		'sections' => array(
			array(
				'id' => 'header-topbar',
				'title'  => esc_html_x('General & Top Bar', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name' => 'header_layout',
						'label'   => esc_html_x('Header Layout Style', 'Admin', 'contentberg'),
						'value'   => 'simple-boxed',
						'desc'    => '',
						'type'    => 'select',
						'options' => array(
							'simple-boxed' => esc_html_x('Style 1: Logo + Nav + Icons (Boxed)', 'Admin', 'contentberg'),
							'compact' => esc_html_x('Style 2: Topbar + Logo & Nav (Compact)', 'Admin', 'contentberg'),
							'top-below' => esc_html_x('Style 3: Nav Below Logo with Social Icons', 'Admin', 'contentberg'),
							'nav-below-b' => esc_html_x('Style 4: Special Topbar + Nav Below Logo', 'Admin', 'contentberg'),
							'logo-ad'   => esc_html_x('Style 5: Logo Left + Ad', 'Admin', 'contentberg'),
							'simple' => esc_html_x('Style 6: Logo + Nav + Icons (Full-width)', 'Admin', 'contentberg'),
							'' => esc_html_x('Style 7: Classic', 'Admin', 'contentberg'),
							'nav-below' => esc_html_x('Style 8: Nav Below Logo', 'Admin', 'contentberg'),
							'full-top'  => esc_html_x('Style 9: Full-width Top', 'Admin', 'contentberg'),
							'alt' => esc_html_x('Style 10: Default + Social Icons + Search Icon', 'Admin', 'contentberg'),
						),
					),
					
					array(
						'name' => 'header_ad',
						'label'   => esc_html_x('Header Ad Code', 'Admin', 'contentberg'),
						'value'   => '',
						'desc'    => '',
						'type'    => 'textarea',
						'context' => array('control' => array('key' => 'header_layout', 'value' => array('logo-ad', 'compact'))),
						'sanitize_callback' => ''
					),
	
					array(
						'name' => 'css_header_bg_image',
						'label'   => esc_html_x('Header Background', 'Admin', 'contentberg'),
						'value'   => '',
						'desc'    => '',
						'type'    => 'upload',
						'options' => array('type' => 'image'),
						'bg_type' => array('value' => 'no-repeat'),
						'css' => array(
							'selectors' => array(
								'.main-head .logo-wrap' => 'background-image: url(%s); background-position: top center;'
							),
						),
						'context' => array('control' => array('key' => 'header_layout', 'value' => array('simple', 'simple-boxed'), 'compare' => '!=')),
					),
						
					array(
						'name' => 'css_header_bg_full',
						'label'   => esc_html_x('Header Background Full Width', 'Admin', 'contentberg'),
						'value'   => '',
						'desc'    => '',
						'type'    => 'upload',
						'options' => array('type' => 'image'),
						'bg_type' => array('value' => 'cover-nonfixed'),
						'css' => array(
							'selectors' => array(
								'.main-head > .inner' => 'background-image: url(%s); background-position: top center;'
							),
						),
						'context' => array('control' => array('key' => 'header_layout', 'value' => array('simple', 'simple-boxed'), 'compare' => '!=')),
					),

					array(
						'name' => 'topbar_style',
						'label'   => esc_html_x('Topbar Style', 'Admin', 'contentberg'),
						'value'   => 'light',
						'desc'    => '',
						'type'    => 'select',
						'options' => array(
							'light' => esc_html_x('Light', 'Admin', 'contentberg'),
							'dark'  => esc_html_x('Dark / Contrast', 'Admin', 'contentberg')
						),
							
						'context' => array('control' => array('key' => 'header_layout', 'value' => array('simple', 'simple-boxed'), 'compare' => '!=')),
					),
						
					array(
						'name' => 'nav_style',
						'label'   => esc_html_x('Navigation Style', 'Admin', 'contentberg'),
						'value'   => 'light',
						'desc'    => '',
						'type'    => 'select',
						'options' => array(
							'light' => esc_html_x('Light', 'Admin', 'contentberg'),
							'dark'  => esc_html_x('Dark', 'Admin', 'contentberg')
						),
							
						// Only show this setting if header_layout is nav_below type
						'context' => array('control' => array('key' => 'header_layout', 'value' => array('nav-below', 'nav-below-b'))),
					),

					array(
						'name' => 'header_nosep_home',
						'label'   => esc_html_x('Disable Header Separator On Home', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => esc_html_x('Don\'t show a separator border on header?', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
						'context' => array('control' => array('key' => 'header_layout', 'value' => array('simple-boxed'))),
					),
						
					array(
						'name' => 'topbar_ticker_text',
						'label'   => esc_html_x('Topbar Latest Posts Label', 'Admin', 'contentberg'),
						'value'   => 'Latest Posts:',
						'desc'    => '',
						'type'    => 'text',
						'context' => array('control' => array('key' => 'header_layout', 'value' => array('nav-below-b', 'compact'))),
					),
						
					array(
						'name' => 'topbar_top_menu',
						'label'   => esc_html_x('Enable Topbar Navigation', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => esc_html_x('Enabling this will disable topbar latest posts.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
						'context' => array('control' => array('key' => 'header_layout', 'value' => array('nav-below-b', 'compact'))),
					),

					array(
						'name' => 'topbar_search',
						'label'   => esc_html_x('Show Search', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => '',
						'type'    => 'checkbox',
					),

					array(
						'name' => 'topbar_cart',
						'label'   => esc_html_x('Shopping Cart Icon', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => esc_html_x('Only works when WooCommerce is installed.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
					),
					
					array(
						'name' => 'topbar_sticky',
						'label'   => esc_html_x('Sticky Top Bar/Navigation', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => esc_html_x('Make topbar or navigation sticky on scrolling.', 'Admin', 'contentberg'),
						'type'    => 'select',
						'options' => array(
							'' => esc_html_x('Disabled', 'Admin', 'contentberg'),
							1 => esc_html_x('Enabled - Normal', 'Admin', 'contentberg'),
							'smart' => esc_html_x('Enabled - Smart (Show when scrolling to top)', 'Admin', 'contentberg'),
						)
					),

					array(
						'label'   => esc_html_x('Top Bar Social Icons', 'Admin', 'contentberg'),
						'name'    => 'topbar_social',
						// Enable defaults with:  array('facebook', 'twitter', 'instagram')
						'value'   => array(),
						'desc'    => sprintf(
							esc_html_x('NOTE: Configure these icons URLs from %1$sGeneral Settings > Social Media%2$s.', 'Admin', 'contentberg'),
							'<a href="#" class="focus-link" data-section="general-social">', '</a>'
						),
						'type'    => 'checkboxes',
					
						// Show only if header layout is default
						'context' => array('control' => array('key' => 'header_layout', 'value' => array('nav-below', 'full-top', 'nav-below-b', 'alt', 'top-below', 'compact', 'simple', 'simple-boxed'))),
						'options' => array(
							'facebook'  => esc_html_x('Facebook', 'Admin', 'contentberg'),
							'twitter'   => esc_html_x('Twitter', 'Admin', 'contentberg'),
							'gplus'     => esc_html_x('Google Plus', 'Admin', 'contentberg'),
							'instagram' => esc_html_x('Instagram', 'Admin', 'contentberg'),
							'pinterest' => esc_html_x('Pinterest', 'Admin', 'contentberg'),
							'vimeo'     => esc_html_x('Vimeo', 'Admin', 'contentberg'),
							'bloglovin' => esc_html_x('BlogLovin', 'Admin', 'contentberg'),
							'rss'       => esc_html_x('RSS', 'Admin', 'contentberg'),
							'youtube'   => esc_html_x('Youtube', 'Admin', 'contentberg'),
							'dribbble'  => esc_html_x('Dribbble', 'Admin', 'contentberg'),
							'tumblr'    => esc_html_x('Tumblr', 'Admin', 'contentberg'),
							'linkedin'  => esc_html_x('LinkedIn', 'Admin', 'contentberg'),
							'flickr'    => esc_html_x('Flickr', 'Admin', 'contentberg'),
							'soundcloud' => esc_html_x('SoundCloud', 'Admin', 'contentberg'),
							'lastfm'     => esc_html_x('Last.fm', 'Admin', 'contentberg'),
							'vk'         => esc_html_x('VKontakte', 'Admin', 'contentberg'),
							'steam'      => esc_html_x('Steam', 'Admin', 'contentberg'),
								
						),
					),
					
				), // fields
			
			), // section
			
			array(
				'id' => 'header-logo',
				'title'  => esc_html_x('Logos', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name'    => 'image_logo',
						'value'   => '',
						'label'   => esc_html_x('Logo Image', 'Admin', 'contentberg'),
						'desc'    => esc_html_x('Highly recommended to use a logo image in PNG format.', 'Admin', 'contentberg'),
						'type'    => 'upload',
						'options' => array(
							'type'  => 'image',
						),
					),
					
					array(
						'name'    => 'image_logo_2x',
						'label'   => esc_html_x('Logo Image Retina (2x)', 'Admin', 'contentberg'),
						'value'   => '',
						'desc'    => esc_html_x('This will be used for higher resolution devices like iPhone/Macbook.', 'Admin', 'contentberg'),
						'type'    => 'upload',
						'options' => array(
							'type'  => 'image',
						),
					),
					
					array(
						'name'    => 'mobile_logo_2x',
						'value'   => '',
						'label'   => esc_html_x('Mobile Logo Retina (2x - Optional)', 'Admin', 'contentberg'),
						'desc'    => esc_html_x('Use a different logo for mobile devices. Upload a logo twice the normal width and height.', 'Admin', 'contentberg'),
						'type'    => 'upload',
						'options' => array(
							'type'  => 'media',
						),
					),
			
				), // fields
			
			), // section
						
		), // sections
		
	),	// panel		

	array(
		'title' => esc_html_x('Posts & Listings', 'Admin', 'contentberg'),
		'id'    => 'sphere-posts',
		'sections' => array(
			array(
				'id' => 'posts-general',
				'title'  => esc_html_x('Common Post Settings', 'Admin', 'contentberg'),
				'fields' => array(
						
					array(
						'name' => 'featured_crop',
						'label'   => esc_html_x('Crop Featured Images?', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => esc_html_x('Crop featured image for consistent sizing and least bandwidth usage. Applies to: Classic Style listings, Single Post.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
					),
						
					array(
						'name'  => 'meta_style',
						'label' => esc_html_x('Meta Style', 'Admin', 'contentberg'),
						'value' => 'style-b',
						'type'  => 'radio',
						'desc'  => esc_html_x('Affects grid and large posts only.', 'Admin', 'contentberg'),
						'options' => array(
							'' => esc_html_x('Style 1: Category and Date', 'Admin', 'contentberg'),
							'style-b' => esc_html_x('Style 2: Category, Date, Comments', 'Admin', 'contentberg'),
							'style-c' => esc_html_x('Style 3: Magazine - Left, Author, Date', 'Admin', 'contentberg'),
						)
					),

					array(
						'name' => 'meta_date',
						'label'   => esc_html_x('Post Meta: Show Date', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => '',
						'type'    => 'checkbox',
					),

					array(
						'name' => 'meta_category',
						'label'   => esc_html_x('Post Meta: Show Category', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => '',
						'type'    => 'checkbox',
					),

					array(
						'name' => 'meta_read_time',
						'label'   => esc_html_x('Post Meta: Reading Time', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => '',
						'type'    => 'checkbox',
						'context' => array('control' => array('key' => 'meta_style', 'value' => 'style-b'))
					),

					array(
						'name' => 'meta_comments',
						'label'   => esc_html_x('Post Meta: Show Comment Count', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => '',
						'type'    => 'checkbox',
						'context' => array('control' => array('key' => 'meta_style', 'value' => 'style-b'))
					),
						
					array(
						'name' => 'meta_cat_labels',
						'label'   => esc_html_x('Post Image: Category Overlay', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => '',
						'type'    => 'checkbox',
					),

					array(
						'name' => 'posts_likes',
						'label'   => esc_html_x('Enable Post Likes', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => '',
						'type'    => 'checkbox',
					),

						
				), // fields
			), // section
			
			array(
				'id' => 'posts-single',
				'title'  => esc_html_x('Single Post & Layout', 'Admin', 'contentberg'),
				'fields' => array(

					array(
						'name'  => 'post_layout_template',
						'label' => esc_html_x('Default Post Style', 'Admin', 'contentberg'),
						'value' => 'creative',
						'type'  => 'radio',
						'options' => array(
							'classic' => esc_html_x('Classic', 'Admin', 'contentberg'),
							'creative' => esc_html_x('Creative - Large Style', 'Admin', 'contentberg'),
							'cover' => esc_html_x('Cover - Overlay Style', 'Admin', 'contentberg'),
							'dynamic'  => esc_html_x('Dynamic (Affects Full Width Layout Only)', 'Admin', 'contentberg'),
							'magazine' => esc_html_x('Magazine/News Style', 'Admin', 'contentberg'),
						)
					),

					array(
						'name' => 'post_layout_spacious',
						'label'   => esc_html_x('Spacious Post Style?', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => esc_html_x('Enable to add extra left/right spacing to text to create a dynamic spacious feel. Especially great when used with Full Width.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
					),

					array(
						'name' => 'single_sidebar',
						'label'   => esc_html_x('Single Post/Page Sidebar', 'Admin', 'contentberg'),
						'value'   => '',
						'desc'    => esc_html_x('This setting can be changed per post or page.', 'Admin', 'contentberg'),
						'type'    => 'radio',
						'options' => array(
							''      => esc_html_x('Default / Global', 'Admin', 'contentberg'),
							'none'  => esc_html_x('No Sidebar', 'Admin', 'contentberg'),
							'right' => esc_html_x('Right Sidebar', 'Admin', 'contentberg') 
						),
					),
						
					array(
						'name' => 'single_share_float',
						'label'   => esc_html_x('Social: Floating/Sticky Buttons', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => '',
						'type'    => 'checkbox',
					),

					array(
						'name' => 'share_float_text',
						'label'   => esc_html_x('Social: Float Share Text', 'Admin', 'contentberg'),
						'value'   => esc_html__('Share', 'contentberg'),
						'desc'    => '',
						'type'    => 'input',
						'context' => array('control' => array('key' => 'single_share_float', 'value' => 1)),
					),

					array(
						'name' => 'share_float_services',
						'label'   => esc_html_x('Social: Float Share Services', 'Admin', 'contentberg'),
						'value'   => array('facebook', 'twitter', 'pinterest', 'email'),
						'desc'    => '',
						'type'    => 'checkboxes',
						'options' => array(
							'facebook'  => esc_html_x('Facebook', 'Admin', 'contentberg'),
							'twitter'   => esc_html_x('Twitter', 'Admin', 'contentberg'),
							'pinterest' => esc_html_x('Pinterest', 'Admin', 'contentberg'),
							'gplus'     => esc_html_x('Google Plus', 'Admin', 'contentberg'),
							'linkedin'  => esc_html_x('LinkedIn', 'Admin', 'contentberg'),
							'tumblr'    => esc_html_x('Tumblr', 'Admin', 'contentberg'),
							'vk'        => esc_html_x('VKontakte', 'Admin', 'contentberg'),
							'email'     => esc_html_x('Email', 'Admin', 'contentberg'),
						),
						'context' => array('control' => array('key' => 'single_share_float', 'value' => 1)),
					),
						
					array(
						'name' => 'single_share',
						'label'   => esc_html_x('Social: Show Post Share', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => '',
						'type'    => 'checkbox',
					),
					
					array(
						'name' => 'single_tags',
						'label'   => esc_html_x('Show Post Tags', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => '',
						'type'    => 'checkbox',
					),
					
					array(
						'name' => 'show_featured',
						'label'   => esc_html_x('Show Featured Image Area', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => esc_html_x('Stops displaying the featured image in large posts. Can also be set per set while adding/edit a post.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',	
					),
					
					array(
						'name' => 'single_all_cats',
						'label'   => esc_html_x('All Categories in Meta', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => esc_html_x('If unchecked, only the Primary Category is displayed.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',	
					),
					
					array(
						'name' => 'author_box',
						'label'   => esc_html_x('Show Author Box', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => '',
						'type'    => 'checkbox',	
					),
						
					array(
						'name' => 'single_navigation',
						'label'   => esc_html_x('Show Next/Previous Post', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => '',
						'type'    => 'checkbox',	
					),	

					array(
						'name' => 'related_posts',
						'label'   => esc_html_x('Show Related Posts', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => '',
						'type'    => 'checkbox',	
					),
					
					array(
						'name' => 'related_posts_by',
						'label'   => esc_html_x('Related Posts Match By', 'Admin', 'contentberg'),
						'value'   => 'cat_tags',
						'desc'    => '',
						'type'    => 'radio',
						'options' => array(
							''     => esc_html_x('Categories', 'Admin', 'contentberg'),
							'tags' => esc_html_x('Tags', 'Admin', 'contentberg'),
							'cat_tags' => esc_html_x('Both', 'Admin', 'contentberg'),
							 
						),
					),
						
					array(
						'name' => 'related_posts_number',
						'label'   => esc_html_x('Related Posts Number', 'Admin', 'contentberg'),
						'value'   => 4,
						'desc'    => '',
						'type'    => 'number',
					),

					array(
						'name' => 'related_posts_number_full',
						'label'   => esc_html_x('Number on Full Width Posts', 'Admin', 'contentberg'),
						'value'   => 3,
						'desc'    => '',
						'type'    => 'number',
					),
						
					array(
						'name' => 'related_posts_grid',
						'label'   => esc_html_x('Related Posts Columns', 'Admin', 'contentberg'),
						'value'   => 3,
						'desc'    => '',
						'type'    => 'select',
						'options' => array(
							3 => esc_html_x('3 Columns', 'Admin', 'contentberg'),
							2 => esc_html_x('2 Columns', 'Admin', 'contentberg'),
						)
						
					),

				), // fields
			), // section
			
			array(
				'id' => 'posts-listings',
				'title'  => esc_html_x('Post Listings', 'Admin', 'contentberg'),
				'fields' => array(
						
					array(
						'name' => 'post_grid_style',
						'label'   => esc_html_x('Grid Posts Style', 'Admin', 'contentberg'),
						'value'   => 'grid',
						'desc'    => esc_html_x('When using a layout that uses grid posts, there are two types of grid posts to choose from', 'Admin', 'contentberg'),
						'type'    => 'select',
						'options' => array(
							'grid' => esc_html_x('Style 1: Default - With Social', 'Admin', 'contentberg'),
							'grid-b' => esc_html_x('Style 2: Centered Text & Read More', 'Admin', 'contentberg')
						),
					),
						
					array(
						'name' => 'post_grid_masonry',
						'label'   => esc_html_x('Masonry Grid Posts', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => esc_html_x('When using a layout that uses grid posts, you can use a masonry layout.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
					),
						
					array(
						'name'  => 'post_grid_meta_style',
						'label' => esc_html_x('Grid Posts: Meta Style', 'Admin', 'contentberg'),
						'value' => '',
						'type'  => 'select',
						'desc'  => esc_html_x('Default uses global setting from Common Post Settings.', 'Admin', 'contentberg'),
						'options' => array(
							'' => esc_html_x('Default - Global', 'Admin', 'contentberg'),
							'style-a' => esc_html_x('Style 1: Category and Date', 'Admin', 'contentberg'),
							'style-b' => esc_html_x('Style 2: Category, Date, Comments', 'Admin', 'contentberg'),
							'style-c' => esc_html_x('Style 3: Magazine - Left, Author, Date', 'Admin', 'contentberg'),
						)
					),
					
					array(
						'name' => 'post_large_style',
						'label'   => esc_html_x('Large Posts Style', 'Admin', 'contentberg'),
						'value'   => 'large',
						'desc'    => esc_html_x('When using a layout that uses large post, there are two styles to choose from.', 'Admin', 'contentberg'),
						'type'    => 'select',
						'options' => array(
							'large' => esc_html_x('Style 1: Default - Title Below', 'Admin', 'contentberg'),
							'large-b' => esc_html_x('Style 2: Title Above', 'Admin', 'contentberg'),
							'large-c' => esc_html_x('Style 3: Overlay Bottom & No Excerpt', 'Admin', 'contentberg'),
						),
					),
						
					array(
						'name' => 'post_list_style',
						'label'   => esc_html_x('List Posts Style', 'Admin', 'contentberg'),
						'value'   => 'list-b',
						'desc'    => esc_html_x('When using a layout that uses list posts, there are two types of grid posts to choose from', 'Admin', 'contentberg'),
						'type'    => 'select',
						'options' => array(
							'list' => esc_html_x('Style 1: Default - With Social', 'Admin', 'contentberg'),
							'list-b' => esc_html_x('Style 2: Spacious & Read More', 'Admin', 'contentberg')
						),
					),

					array(
						'name' => 'pagination_style',
						'label'   => esc_html_x('Pagination Style', 'Admin', 'contentberg'),
						'value'   => '',
						'type'    => 'radio',
						'options' => array(
							''     => esc_html_x('Older / Newer', 'Admin', 'contentberg'),
							'numbers' => esc_html_x('Page Numbers', 'Admin', 'contentberg'),
							'load-more' => esc_html_x('Load More', 'Admin', 'contentberg'),
						),
					),
						
					array(
						'name' => 'post_format_icons',
						'label'   => esc_html_x('Show Post Format Icons?', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => esc_html_x('Post format icons (video, gallery) can be enabled on a few listing styles such as list and grid.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
					),
						
					array(
						'name' => 'post_footer_blog',
						'label'   => esc_html_x('Large Post: Show Post Footer', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => esc_html_x('Post footer is the extar info shown below post such as author, read more, and social icons.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
					),
						
					array(
						'name' => 'post_footer_author',
						'label'   => esc_html_x('Large Post: Show Author', 'Admin', 'contentberg'),
						'value'   => 1,
						'type'    => 'checkbox',
					),
						
						
					array(
						'name' => 'post_footer_read_more',
						'label'   => esc_html_x('Large Post: Show Read More', 'Admin', 'contentberg'),
						'value'   => 1,
						'type'    => 'checkbox',
					),
						
					array(
						'name' => 'post_footer_social',
						'label'   => esc_html_x('Large Post: Show Social', 'Admin', 'contentberg'),
						'value'   => 1,
						'type'    => 'checkbox',
					),
						
					array(
						'name' => 'post_footer_grid',
						'label'   => esc_html_x('Grid: Show Post Footer', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => esc_html_x('Area below posts for Social Icons or Read More depending on chosen style.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
					),
						
					array(
						'name' => 'post_footer_list',
						'label'   => esc_html_x('List: Show Post Footer', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => esc_html_x('Area below posts that shows likes count & social icons.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
					),
					
					array(
						'name'    => 'post_body',
						'label'   => esc_html_x('Post Body', 'Admin', 'contentberg'),
						'value'   => 'excerpt',
						'type'    => 'radio',
						'desc'    => esc_html_x('Note: Only applies to Blog Listing style. Both support WordPress <!--more--> teaser.', 'Admin', 'contentberg'),
						'options' => array(
							'full' => esc_html_x('Full Post', 'Admin', 'contentberg'),
							'excerpt' => esc_html_x('Excerpts', 'Admin', 'contentberg'),
						),
					),
					
					array(
						'name'    => 'post_excerpt_blog',
						'label'   => esc_html_x('Excerpt Words: Classic Style', 'Admin', 'contentberg'),
						'value'   => 75,
						'type'    => 'number',
						'desc'    => '',
					),
					
					array(
						'name'    => 'post_excerpt_grid',
						'label'   => esc_html_x('Excerpt Words: Grid Style', 'Admin', 'contentberg'),
						'value'   => 20,
						'type'    => 'number',
						'desc'    => '',
					),
					
					
					array(
						'name'    => 'post_excerpt_list',
						'label'   => esc_html_x('Excerpt Words: List Style', 'Admin', 'contentberg'),
						'value'   => 24,
						'type'    => 'number',
						'desc'    => '',
					),
			
				), // fields
			), // section
			
			array(
				'id' => 'posts-pinterest',
				'title'  => esc_html_x('Pinterest on Images', 'Admin', 'contentberg'),
				'fields' => array(
						
					array(
						'name' => 'pinit_button',
						'label'   => esc_html_x('Show Pin It On Hover?', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => esc_html_x('When enabled, on single posts and large posts body, pin it button will show on hover (only works on non-touch devices).', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
					),
						
	
					array(
						'name'    => 'pinit_button_label',
						'label'   => esc_html_x('Show Label', 'Admin', 'contentberg'),
						'value'   => 0,
						'type'    => 'checkbox',
					),
						
					array( 
						'name'    => 'pinit_button_text',
						'label'   => esc_html_x('Button Label', 'Admin', 'contentberg'),
						'value'   => esc_html__('Pin It', 'contentberg'),
						'type'    => 'input',
					),
						
					array(
						'name'    => 'pinit_show_on',
						'label'   => esc_html_x('Show On:', 'Admin', 'contentberg'),
						'value'   => array('single'),
						'type'    => 'checkboxes',
						'options' => array(
							'single' => esc_html_x('Single Post Images', 'Admin', 'contentberg'),
							'listing' => esc_html_x('Listings/Categories: Featured Images', 'Admin', 'contentberg'), 
						)
					)
					
						
				), // fields
			), // section
			
		) // sections
			
	), // panel
	
	

	array(
		'title' => esc_html_x('Footer Settings', 'Admin', 'contentberg'),
		'id'    => 'sphere-footer',
		'desc'  => esc_html_x('Middle footer is activated by adding an instagram widget.', 'Admin', 'contentberg'),
		'sections' => array(
						
			array(
				'id' => 'footer-upper',
				'title'  => esc_html_x('General & Upper Footer', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name'  => 'footer_layout',
						'value' => 'bold-light',
						'label' => esc_html_x('Select layout', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'select',
						'options' => array(
							'' => esc_html_x('Default Light', 'Admin', 'contentberg'),
							'contrast' => esc_html_x('Dark Contrast', 'Admin', 'contentberg'),
							'alt' => esc_html_x('Alternate Light', 'Admin', 'contentberg'),
							'stylish' => esc_html_x('Stylish Dark', 'Admin', 'contentberg'),
							'stylish-b' => esc_html_x('Stylish Dark Alt', 'Admin', 'contentberg'),
							'classic' => esc_html_x('Magazine / Classic Dark', 'Admin', 'contentberg'),
							'bold' => esc_html_x('Bold Dark (Footer Links Supported)', 'Admin', 'contentberg'),
							'bold-light' => esc_html_x('Bold Light (Footer Links Supported)', 'Admin', 'contentberg')
						)
					),
						
					array(
						'name'  => 'footer_upper',
						'value' => 1,
						'label' => esc_html_x('Enable Upper Footer', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'checkbox'
					),
					
					array(
						'name'  => 'footer_logo',
						'value' => '',
						'label' => esc_html_x('Footer Logo', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'upload',
						'options' => array(
							'type' => 'image'
						),
						'context' => array('control' => array('key' => 'footer_layout', 'value' => array('contrast', 'stylish', 'stylish-b'))),
					),
					
					array(
						'name'  => 'footer_logo_2x',
						'value' => '',
						'label' => esc_html_x('Footer Logo Retina (2x)', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'upload',
						'options' => array(
							'type' => 'image'
						),
						'context' => array('control' => array('key' => 'footer_layout', 'value' => array('contrast', 'stylish', 'stylish-b'))),
					),
					
					array(
						'name'  => 'css_footer_bg',
						'value' => '',
						'label' => esc_html_x('Footer Background Image', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'upload',
						'options' => array(
							'type' => 'image'
						),
						'context' => array('control' => array('key' => 'footer_layout', 'value' => array('stylish', 'stylish-b'))),
						'bg_type' => array('value' => 'cover-nonfixed'),
						'css' => array(
							'selectors' => array(
								'.main-footer .bg-wrap:before' => 'background-image: url(%s)'
							),
						),
						
					),
						
					array(
						'name' => 'css_footer_bg_opacity',
						'label'   => esc_html_x('Footer Bg Image Opacity', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => esc_html_x('An opacity of 0.2 is recommended.', 'Admin', 'contentberg'),
						'type'    => 'number',
						'input_attrs' => array('min' => 0, 'max' => 1, 'step' => 0.1),
						'css' => array(
							'selectors' => array(
								'.main-footer .bg-wrap:before' => 'opacity: %s'
							)
						),
						'context' => array('control' => array('key' => 'footer_layout', 'value' => array('stylish', 'stylish-b'))),
					),
			
				), // fields
			), // section
			
			array(
				'id' => 'footer-lower',
				'title'  => esc_html_x('Lower Footer', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name'  => 'footer_lower',
						'value' => 1,
						'label' => esc_html_x('Enable Lower Footer', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'checkbox'
					),

					array(
						'name'  => 'footer_links',
						'value' => 1,
						'label' => esc_html_x('Enable Footer Links', 'Admin', 'contentberg'),
						'desc'  => esc_html_x('After ticking here, save and add a menu from Appearance > Menus and assign it to footer links.', 'Admin', 'contentberg'),
						'type'  => 'checkbox',
						'context' => array('control' => array('key' => 'footer_layout', 'value' => array('bold', 'bold-light'))),
					),
					
					array(
						'name'  => 'footer_copyright',
						'value' => '&copy; 2019 ThemeSphere. Designed by <a href="http://theme-sphere.com">ThemeSphere</a>.', // Example copyright message in Customizer
						'label' => esc_html_x('Copyright Message', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'  => 'footer_back_top',
						'value' => 1,
						'label' => esc_html_x('Show Back to Top', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'checkbox',
						'context' => array('control' => array('key' => 'footer_layout', 'value' => ''))
					),
						
					array(
						'label'   => esc_html_x('Footer Social Icons', 'Admin', 'contentberg'),
						'name'    => 'footer_social',
						// Enable defaults with:  array('facebook', 'twitter', 'instagram')
						'value'   => array(),
						'desc'    => esc_html_x('NOTE: Configure these icons URLs from General Settings > Social Media.', 'Admin', 'contentberg'),
						'type'    => 'checkboxes',
					
						// Show only if header layout is default
						'context' => array('control' => array('key' => 'footer_layout', 'value' => array('contrast', 'alt', 'stylish', 'stylish-b', 'bold', 'bold-light'))),
						'options' => array(
							'facebook'  => esc_html_x('Facebook', 'Admin', 'contentberg'),
							'twitter'   => esc_html_x('Twitter', 'Admin', 'contentberg'),
							'gplus'     => esc_html_x('Google Plus', 'Admin', 'contentberg'),
							'instagram' => esc_html_x('Instagram', 'Admin', 'contentberg'),
							'pinterest' => esc_html_x('Pinterest', 'Admin', 'contentberg'),
							'vimeo'     => esc_html_x('Vimeo', 'Admin', 'contentberg'),
							'bloglovin' => esc_html_x('BlogLovin', 'Admin', 'contentberg'),
							'rss'       => esc_html_x('RSS', 'Admin', 'contentberg'),
							'youtube'   => esc_html_x('Youtube', 'Admin', 'contentberg'),
							'dribbble'  => esc_html_x('Dribbble', 'Admin', 'contentberg'),
							'tumblr'    => esc_html_x('Tumblr', 'Admin', 'contentberg'),
							'linkedin'  => esc_html_x('LinkedIn', 'Admin', 'contentberg'),
							'flickr'    => esc_html_x('Flickr', 'Admin', 'contentberg'),
							'soundcloud' => esc_html_x('SoundCloud', 'Admin', 'contentberg'),
							'lastfm'     => esc_html_x('Last.fm', 'Admin', 'contentberg'),
							'vk'         => esc_html_x('VKontakte', 'Admin', 'contentberg'),
							'steam'      => esc_html_x('Steam', 'Admin', 'contentberg'),
						),
					),
			
				), // fields
			), // section
				
		) // sections
			
	), // panel
	
	'sphere-general' => array(
		'title' => esc_html_x('General Settings', 'Admin', 'contentberg'),
		'id'    => 'sphere-general',
		'sections' => array(
			
			array(
				'id' => 'general-archives',
				'title'  => esc_html_x('Categories & Archives', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name' => 'archive_sidebar',
						'label'   => esc_html_x('Listings Sidebar', 'Admin', 'contentberg'),
						'value'   => '',
						'desc'    => esc_html_x('Applies to all type of archives except home.', 'Admin', 'contentberg'),
						'type'    => 'radio',
						'options' => array(
							''  => esc_html_x('Default', 'Admin', 'contentberg'),
							'none'  => esc_html_x('No Sidebar', 'Admin', 'contentberg'),
							'right' => esc_html_x('Right Sidebar', 'Admin', 'contentberg') 
						),
					),
			
					array(
						'name' => 'category_loop',
						'label'   => esc_html_x('Category Listing Style', 'Admin', 'contentberg'),
						'value'   => '',
						'desc'    => '',
						'type'    => 'select',
						'options' => array(
							'' => esc_html_x('Classic Large Posts', 'Admin', 'contentberg'),
							'loop-1st-large' => esc_html_x('One Large Post + Grid', 'Admin', 'contentberg'),
							'loop-1st-large-list' => esc_html_x('One Large Post + List', 'Admin', 'contentberg'),
							'loop-1st-overlay' => esc_html_x('One Overlay Post + Grid', 'Admin', 'contentberg'),
							'loop-1st-overlay-list' => esc_html_x('One Overlay Post + List', 'Admin', 'contentberg'),
								
							'loop-1-2' => esc_html_x('Mixed: Large Post + 2 Grid ', 'Admin', 'contentberg'),
							'loop-1-2-list' => esc_html_x('Mixed: Large Post + 2 List ', 'Admin', 'contentberg'),

							'loop-1-2-overlay' => esc_html_x('Mixed: Overlay Post + 2 Grid ', 'Admin', 'contentberg'),
							'loop-1-2-overlay-list' => esc_html_x('Mixed: Overlay Post + 2 List ', 'Admin', 'contentberg'),
								
							'loop-list' => esc_html_x('List Posts', 'Admin', 'contentberg'),
							'loop-grid' => esc_html_x('Grid Posts', 'Admin', 'contentberg'),
							'loop-grid-3' => esc_html_x('Grid Posts (3 Columns)', 'Admin', 'contentberg'),
						),
					),
					
					array(
						'name' => 'archive_loop',
						'label'   => esc_html_x('Archive Listing Style', 'Admin', 'contentberg'),
						'value'   => '',
						'desc'    => '',
						'type'    => 'select',
						'options' => array(
							'' => esc_html_x('Classic Large Posts', 'Admin', 'contentberg'),
							'loop-1st-large' => esc_html_x('One Large Post + Grid', 'Admin', 'contentberg'),
							'loop-1st-large-list' => esc_html_x('One Large Post + List', 'Admin', 'contentberg'),
							'loop-1st-overlay' => esc_html_x('One Overlay Post + Grid', 'Admin', 'contentberg'),
							'loop-1st-overlay-list' => esc_html_x('One Overlay Post + List', 'Admin', 'contentberg'),
								
							'loop-1-2' => esc_html_x('Mixed: Large Post + 2 Grid ', 'Admin', 'contentberg'),
							'loop-1-2-list' => esc_html_x('Mixed: Large Post + 2 List ', 'Admin', 'contentberg'),

							'loop-1-2-overlay' => esc_html_x('Mixed: Overlay Post + 2 Grid ', 'Admin', 'contentberg'),
							'loop-1-2-overlay-list' => esc_html_x('Mixed: Overlay Post + 2 List ', 'Admin', 'contentberg'),
								
							'loop-list' => esc_html_x('List Posts', 'Admin', 'contentberg'),
							'loop-grid' => esc_html_x('Grid Posts', 'Admin', 'contentberg'),
							'loop-grid-3' => esc_html_x('Grid Posts (3 Columns)', 'Admin', 'contentberg'),
						),
					),
					
					array(
						'name' => 'search_loop',
						'label'   => esc_html_x('Search Listing Style', 'Admin', 'contentberg'),
						'value'   => '',
						'desc'    => '',
						'type'    => 'select',
						'options' => array(

							'' => esc_html_x('Classic Large Posts', 'Admin', 'contentberg'),
							'loop-1st-large' => esc_html_x('One Large Post + Grid', 'Admin', 'contentberg'),
							'loop-1st-large-list' => esc_html_x('One Large Post + List', 'Admin', 'contentberg'),
							'loop-1st-overlay' => esc_html_x('One Overlay Post + Grid', 'Admin', 'contentberg'),
							'loop-1st-overlay-list' => esc_html_x('One Overlay Post + List', 'Admin', 'contentberg'),
								
							'loop-1-2' => esc_html_x('Mixed: Large Post + 2 Grid ', 'Admin', 'contentberg'),
							'loop-1-2-list' => esc_html_x('Mixed: Large Post + 2 List ', 'Admin', 'contentberg'),

							'loop-1-2-overlay' => esc_html_x('Mixed: Overlay Post + 2 Grid ', 'Admin', 'contentberg'),
							'loop-1-2-overlay-list' => esc_html_x('Mixed: Overlay Post + 2 List ', 'Admin', 'contentberg'),
								
							'loop-list' => esc_html_x('List Posts', 'Admin', 'contentberg'),
							'loop-grid' => esc_html_x('Grid Posts', 'Admin', 'contentberg'),
							'loop-grid-3' => esc_html_x('Grid Posts (3 Columns)', 'Admin', 'contentberg'),
						),
					),
					
					array(
						'name'  => 'archive_descriptions',
						'value' => 0,
						'label' => esc_html_x('Show Category Descriptions?', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'checkbox'
					),
					
				) // fields
			
			), // section
			
			array(
				'id' => 'general-social',
				'title'  => esc_html_x('Social Media Links', 'Admin', 'contentberg'),
				'desc'   => esc_html_x('Enter full URLs to your social media profiles. These are used in Top Bar social icons.', 'Admin', 'contentberg'),
				'fields' => array(

					array(
						'name'   => 'social_profiles[facebook]',
						'value' => '',
						'label' => esc_html_x('Facebook', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[twitter]',
						'value' => '',
						'label' => esc_html_x('Twitter', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[instagram]',
						'value' => '',
						'label' => esc_html_x('Instagram', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),	
					
					array(
						'name'   => 'social_profiles[pinterest]',
						'value' => '',
						'label' => esc_html_x('Pinterest', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[bloglovin]',
						'value' => '',
						'label' => esc_html_x('BlogLovin', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[bloglovin]',
						'value' => '',
						'label' => esc_html_x('BlogLovin', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[gplus]',
						'value' => '',
						'label' => esc_html_x('Google+', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[youtube]',
						'value' => '',
						'label' => esc_html_x('YouTube', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[dribbble]',
						'value' => '',
						'label' => esc_html_x('Dribbble', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[tumblr]',
						'value' => '',
						'label' => esc_html_x('Tumblr', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[linkedin]',
						'value' => '',
						'label' => esc_html_x('LinkedIn', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[flickr]',
						'value' => '',
						'label' => esc_html_x('Flickr', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[soundcloud]',
						'value' => '',
						'label' => esc_html_x('SoundCloud', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[vimeo]',
						'value' => '',
						'label' => esc_html_x('Vimeo', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[rss]',
						'value' => get_bloginfo('rss2_url'),
						'label' => esc_html_x('RSS', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
					
					array(
						'name'   => 'social_profiles[vk]',
						'value' => '',
						'label' => esc_html_x('VKontakte', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
						
					array(
						'name'   => 'social_profiles[lastfm]',
						'value' => '',
						'label' => esc_html_x('Last.fm', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
						
					array(
						'name'   => 'social_profiles[steam]',
						'value' => '',
						'label' => esc_html_x('Steam', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'text'
					),
			
				) // fields
			
			), // section
			
						
			array(
				'id' => 'general-layout',
				'title'  => esc_html_x('Layout & Sidebar', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name' => 'default_sidebar',
						'label'   => esc_html_x('Default Sidebar', 'Admin', 'contentberg'),
						'value'   => 'right',
						'desc'    => esc_html_x('This setting can be changed per post or page.', 'Admin', 'contentberg'),
						'type'    => 'radio',
						'options' => array(
							'none'  => esc_html_x('No Sidebar', 'Admin', 'contentberg'),
							'right' => esc_html_x('Right Sidebar', 'Admin', 'contentberg') 
						),
					),
						
					array(
						'name' => 'sidebar_sticky',
						'label'   => esc_html_x('Sticky Sidebar', 'Admin', 'contentberg'),
						'value'   => 0,
						'desc'    => esc_html_x('Make the sidebar always stick around while scrolling.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
					),
					
				) // fields
				
			), // section

			array(
				'id' => 'general-misc',
				'title'  => esc_html_x('Other Settings', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name'   => 'search_posts_only',
						'value' => 1,
						'label' => esc_html_x('Limit Search To Posts', 'Admin', 'contentberg'),
						'desc'  => esc_html_x('Enabling this feature will exclude pages from WordPress search.', 'Admin', 'contentberg'),
						'type'  => 'checkbox'
					),
					
					array(
						'name' => 'enable_lightbox',
						'label'   => esc_html_x('Enable Lightbox for Images', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => '',
						'type'    => 'checkbox',
					),

					array(
						'name' => 'amp_enabled',
						'label'   => esc_html_x('AMP: Enable Theme Styles', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => esc_html_x('Enable our special changes for the AMP plugin. Note: Only works when the "Bunyad AMP" plugin is active.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
					),

					array(
						'name' => 'guten_styles',
						'label'   => esc_html_x('Gutenberg: Add front-end Styles', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => esc_html_x('By default Gutenberg has its own styling. Ticking this will enable our custom styles so that the backend is similar looking to frontend.', 'Admin', 'contentberg'),
						'type'    => 'checkbox',
					),

					
				) // fields
				
			), // section

			array(
				'id' => 'general-woocommerce',
				'title'  => esc_html_x('WooCommerce/Shop', 'Admin', 'contentberg'),
				'desc'   => esc_html_x('Settings here only apply if you have WooCommerce installed.', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name' => 'woocommerce_per_page',
						'label'   => esc_html_x('Shop Products / Page', 'Admin', 'contentberg'),
						'value'   => 9,
						'desc'    => '',
						'type'    => 'number'
					),
						
					array(
						'name' => 'woocommerce_image_zoom',
						'label'   => esc_html_x('Product Page - Image Zoom', 'Admin', 'contentberg'),
						'value'   => 1,
						'desc'    => '',
						'type'    => 'checkbox'
					),
				),
			),

			
		) // sections
		
	), // panel
	
	array(
		'title' => esc_html_x('Colors & Style', 'Admin', 'contentberg'),
		'id'    => 'sphere-style',
		'sections' => array(
			array(
				'id' => 'style-general',
				'title'  => esc_html_x('General', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name'  => 'css_main_color',
						'value' => '#2d53fe',
						'label' => esc_html_x('Main Theme Color', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(

							'selectors' => array(
					
								'::selection' => 'background: rgba(%s, 0.7)',
								'::-moz-selection' => 'background: rgba(%s, 0.7)',
								':root' => '--main-color: %s',

								'.cart-action .cart-link .counter,
								.main-head.compact .posts-ticker .heading,
								.single-cover .overlay .post-cat a,
								.main-footer.bold-light .lower-footer .social-link,
								.cat-label a:hover,
								.cat-label.color a,
								.post-thumb:hover .cat-label a,
								.carousel-slider .category,
								.grid-b-slider .category,
								.page-links .current,
								.page-links a:hover,
								.page-links > span,
								.post-content .read-more a:after,
								.widget-posts .posts.full .counter:before,
								.dark .widget_mc4wp_form_widget input[type="submit"],
								.dark .widget-subscribe input[type="submit"],
								.woocommerce span.onsale,
								.woocommerce a.button,
								.woocommerce button.button,
								.woocommerce input.button,
								.woocommerce #respond input#submit,
								.woocommerce a.button.alt,
								.woocommerce a.button.alt:hover,
								.woocommerce button.button.alt,
								.woocommerce button.button.alt:hover,
								.woocommerce input.button.alt,
								.woocommerce input.button.alt:hover,
								.woocommerce #respond input#submit.alt,
								.woocommerce #respond input#submit.alt:hover,
								.woocommerce a.button:hover,
								.woocommerce button.button:hover,
								.woocommerce input.button:hover,
								.woocommerce #respond input#submit:hover,
								.woocommerce nav.woocommerce-pagination ul li span.current,
								.woocommerce nav.woocommerce-pagination ul li a:hover,
								.woocommerce .widget_price_filter .price_slider_amount .button'
									=> 'background: %s',
									
								'blockquote:before,
								.modern-quote:before,
								.wp-block-quote.is-style-large:before,
								.main-color,
								.top-bar .social-icons a:hover,
								.navigation .menu > li:hover > a,
								.navigation .menu > .current-menu-item > a,
								.navigation .menu > .current-menu-parent > a,
								.navigation .menu li li:hover > a,
								.navigation .menu li li.current-menu-item > a,
								.navigation.simple .menu > li:hover > a,
								.navigation.simple .menu > .current-menu-item > a,
								.navigation.simple .menu > .current-menu-parent > a,
								.tag-share .post-tags a:hover,
								.post-share-icons a:hover,
								.post-share-icons .likes-count,
								.author-box .author > span,
								.comments-area .section-head .number,
								.comments-list .comment-reply-link,
								.comment-form input[type=checkbox],
								.main-footer.dark .social-link:hover,
								.lower-footer .social-icons .fa,
								.archive-head .sub-title,
								.social-share a:hover,
								.social-icons a:hover,
								.post-meta .post-cat > a,
								.post-meta-c .post-author > a,
								.large-post-b .post-footer .author a,
								.main-pagination .next a:hover,
								.main-pagination .previous a:hover,
								.main-pagination.number .current,
								.post-content a,
								.textwidget a,
								.widget-about .more,
								.widget-about .social-icons .social-btn:hover,
								.widget-social .social-link:hover,
								.wp-block-pullquote blockquote:before,
								.egcf-modal .checkbox,
								.woocommerce .star-rating:before,
								.woocommerce .star-rating span:before,
								.woocommerce .amount,
								.woocommerce .order-select .drop a:hover,
								.woocommerce .order-select .drop li.active,
								.woocommerce-page .order-select .drop a:hover,
								.woocommerce-page .order-select .drop li.active,
								.woocommerce .widget_price_filter .price_label .from,
								.woocommerce .widget_price_filter .price_label .to,
								.woocommerce div.product div.summary p.price,
								.woocommerce div.product div.summary span.price,
								.woocommerce #content div.product div.summary p.price,
								.woocommerce #content div.product div.summary span.price,
								.woocommerce .widget_price_filter .ui-slider .ui-slider-handle'
									=> 'color: %s',
									
								'.page-links .current,
								.page-links a:hover,
								.page-links > span,
								.woocommerce nav.woocommerce-pagination ul li span.current,
								.woocommerce nav.woocommerce-pagination ul li a:hover'
									=> 'border-color: %s',
									
								'.block-head-b .title' 
									=> 'border-bottom: 1px solid %s',
									
								'.widget_categories a:before,
								.widget_product_categories a:before,
								.widget_archive a:before'
									=> 'border: 1px solid %s',
								
							)
						)
					),
					
					array(
						'name'  => 'css_body_color',
						'value' => '#494949',
						'label' => esc_html_x('Post/Excerpt Body Color', 'Admin', 'contentberg'),
						'desc'  => 'Shared between excerpts and single.',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.post-content, .entry-content' => 'color: %s'
							)
						)
					),

					array(
						'name'  => 'css_single_body_color',
						'value' => '#494949',
						'label' => esc_html_x('Single Post Body Color', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.entry-content' => 'color: %s'
							)
						)
					),

					array(
						'name'  => 'css_site_bg',
						'value' => '#ffffff',
						'label' => esc_html_x('Site Background Color', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'body' => 'background-color: %s'
							)
						)
					),

					array(
						'name'  => 'css_footer_upper_bg',
						'value' => '',
						'label' => esc_html_x('Upper Footer Background', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.upper-footer' => 'background-color: %s; border-top: 0'
							)
						)
					),
						
					array(
						'name'  => 'css_footer_lower_bg',
						'value' => '',
						'label' => esc_html_x('Lower Footer Background', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.lower-footer' => 'background-color: %s; border-top: 0'
							)
						)
					),
						


				), // fields
			), // section
			
			array(
				'id' => 'style-header',
				'title'  => esc_html_x('Header', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name'  => 'css_topbar_bg',
						'value' => '#fff',
						'label' => esc_html_x('Top Bar Background', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.top-bar-content, .top-bar.dark .top-bar-content, .main-head.simple .inner' => 'background-color: %1$s; border-color: %1$s',
								'.top-bar .navigation' => 'background: transparent',
							)
						)
					),

					array(
						'name'  => 'css_header_top_border',
						'value' => '',
						'label' => esc_html_x('Top Border Color', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.main-head.simple-boxed' => 'border-top-color: %s',
							)
						),
						'context' => array('control' => array('key' => 'header_layout', 'value' => array('simple-boxed')))
					),

					array(
						'name'  => 'css_header_bottom_border',
						'value' => '',
						'label' => esc_html_x('Bottom Border Color', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.main-head' => 'border-bottom-color: %s !important',
							)
						),
					),
						
					array(
						'name'  => 'css_topbar_social',
						'value' => '#fff',
						'label' => esc_html_x('Social Icons Color', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.main-head .social-icons a' => 'color: %s !important',
							)
						)
					),
						
					array(
						'name'  => 'css_topbar_search',
						'value' => '#fff',
						'label' => esc_html_x('Search Icon Color', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.main-head .search-submit, .main-head .search-link' => 'color: %s !important',
							)
						)
					),

					array(
						'name'  => 'css_logo_padding_top',
						'value' => 70,
						'label' => esc_html_x('Logo Padding Top', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'number',
						'context' => array('control' => array('key' => 'header_layout', 'value' => array('simple', 'simple-boxed', 'compact'), 'compare' => '!=')),
						'css'   => array(
							'selectors' => array(
								'.main-head:not(.simple):not(.compact):not(.logo-left) .title' => 'padding-top: %spx !important'
							)
						)
					),
					
					array(
						'name'  => 'css_logo_padding_bottom',
						'value' => 70,
						'label' => esc_html_x('Logo Padding Bottom', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'number',
						'context' => array('control' => array('key' => 'header_layout', 'value' => array('simple', 'simple-boxed', 'compact'), 'compare' => '!=')),
						'css'   => array(
							'selectors' => array(
								'.main-head:not(.simple):not(.compact):not(.logo-left) .title' => 'padding-bottom: %spx !important'
							)
						)
					),

				), // fields
			), // section
			
			
			array(
				'id' => 'style-navigation',
				'title'  => esc_html_x('Navigation', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name'  => 'css_nav_color',
						'value' => '',
						'label' => esc_html_x('Top-level Links Color', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.navigation .menu > li:not(:hover) > a, .navigation.dark .menu > li:not(:hover) > a' => 'color: %s'
							)
						)
					),
					
					array(
						'name'  => 'css_nav_hover',
						'value' => '',
						'label' => esc_html_x('Top-level Hover/Active', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.navigation .menu > li:hover > a, 
								.navigation .menu > .current-menu-item > a, 
								.navigation .menu > .current-menu-parent > a, 
								.navigation .menu > .current-menu-ancestor > a' 
									=> 'color: %s !important'
							)
						)
					),
					
					array(
						'name'  => 'css_nav_drop_bg',
						'value' => '#fff',
						'label' => esc_html_x('Dropdown Background', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.navigation .menu ul, .navigation .menu .sub-menu' => 'border-color: transparent; background: %s !important',

								// Use transparent borders to adapt to background
								'.navigation .menu > li li a' => 'border-color: rgba(255, 255, 255, 0.07)'
							)
						)
					),

					array(
						'name'  => 'css_nav_drop_color',
						'value' => '',
						'label' => esc_html_x('Dropdown Links Color', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.navigation .menu > li li a' => 'color: %s !important'
							)
						)
					),
					
					array(
						'name'  => 'css_nav_drop_hover',
						'value' => '',
						'label' => esc_html_x('Dropdown Links Hover/Active', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.navigation .menu li li:hover > a, .navigation .menu li li.current-menu-item > a' => 'color: %s !important'
							)
						)
					),

					array(
						'name'  => 'css_posts_title_menu',
						'value' => '',
						'label' => esc_html_x('Mega Menu: Post Titles', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.mega-menu .recent-posts .post-title' 
									=> 'color: %s !important'
							)
						)
					),

				), // fields
			), // section
			
			array(
				'id' => 'style-posts',
				'title'  => esc_html_x('Posts & Listings', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name'  => 'css_posts_title_color',
						'value' => '',
						'label' => esc_html_x('Post Titles Color', 'Admin', 'contentberg'),
						'desc'  => esc_html_x('Changing this affects post title colors globally. May require adjusting post titles in other areas below.', 'Admin', 'contentberg'),
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.post-title, 
								.post-title-alt' 
									=> 'color: %s !important'
							)
						)
					),
						
					array(
						'name'  => 'css_posts_title_footer',
						'value' => '',
						'label' => esc_html_x('Footer: Post Titles', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.main-footer .post-title, 
								.main-footer .product-title' 
									=> 'color: %s !important'
							)
						)
					),
						
					array(
						'name'  => 'css_posts_title_footer',
						'value' => '',
						'label' => esc_html_x('Footer: Post Titles', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.main-footer .post-title, 
								.main-footer .product-title' 
									=> 'color: %s !important'
							)
						)
					),

					array(
						'name'  => 'css_posts_content_color',
						'value' => '',
						'label' => esc_html_x('Post Content Color', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.post-content' => 'color: %s'
							)
						)
					),
						
						
					array(
						'name'  => 'css_post_meta',
						'value' => '',
						'label' => esc_html_x('Post Meta Color', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.post-meta, 
								.post-meta-b .date-link, 
								.post-meta-b .comments,
								.post-meta .post-date,
								.post-meta .meta-item' => 'color: %s'
							)
						)
					),
											
					array(
						'name'  => 'css_post_meta_cat',
						'value' => '',
						'label' => esc_html_x('Meta Category Color', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.post-meta .post-cat > a' => 'color: %s !important'
							)
						)
					),

				), // fields
			), // section
			
				
			array(
				'id' => 'style-sidebar',
				'title'  => esc_html_x('Sidebar', 'Admin', 'contentberg'),
				'fields' => array(
									
					array(
						'name'  => 'css_sidebar_title_color',
						'value' => '',
						'label' => esc_html_x('Widget Titles Color', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'color',
						'css'   => array(
							'selectors' => array(
								'.sidebar .widget:not(.widget_mc4wp_form_widget):not(.widget-subscribe) .widget-title' => 'color: %s',
							)
						)
					),

					array(
						'name'  => 'css_sidebar_widget_margin',
						'value' => 50,
						'label' => esc_html_x('Widget Bottom Spacing', 'Admin', 'contentberg'),
						'desc'  => '',
						'type'  => 'number',
						'css'   => array(
							'selectors' => array(
								'.sidebar .widget' => 'margin-bottom: %spx'
							)
						)
					),

				), // fields
			), // section
			

				
		) // sections
	), // panel
	
	

	array(
		'title' => esc_html_x('Typography & Fonts', 'Admin', 'contentberg'),
		'id'    => 'sphere-typography',
		'desc'  => esc_html_x('All the typography fonts are from Google Fonts. You can either select one from the list or to type your own: Click on field, press Backspace key (Delete on MacOS), type the name of any font from Google Fonts directory, and click Add.', 'Admin', 'contentberg'),
		'sections' => array(
			array(
				'id' => 'typography-fonts',
				'title'  => esc_html_x('Fonts & Sizes', 'Admin', 'contentberg'),
				'fields' => array(
					array(
						'name'  => 'fonts_info',
						'text'  => sprintf(
							esc_html_x('%1$sAll the fonts are from %3$sGoogle Fonts%4$s. You can either select one from the list or to type your own:%2$s Click on field, press %5$sBackspace%6$s key (Delete on MacOS), type the name of any font from Google Fonts directory, and click Add.', 'Admin', 'contentberg'),
							'<p>', '</p>', 
							'<a href="https://fonts.google.com" target=_blank>', '</a>',
							'<code>', '</code>'
						) . '<hr />',
						'type'  => 'content'
					),
					
					array(
						'name' => 'css_font_main',
						'label' => esc_html_x('Main Font', 'Admin', 'contentberg'),
						'value' => array('font_name' => ''),
						'desc'  => esc_html_x('Main font used for most of the site. Select from list or click and type your own Google Font name (or TypeKit if you have configured it).', 'Admin', 'contentberg'),
						'type'  => 'typography',
						'typeface' => true, // is family
						'css'   => array(
							'selectors' => '
								body,
								h1,
								h2,
								h3,
								h4,
								h5,
								h6,
								input,
								textarea,
								select,
								input[type="submit"],
								button,
								input[type="button"],
								.button,
								blockquote cite,
								blockquote .wp-block-pullquote__citation,
								.modern-quote cite,
								.wp-block-quote.is-style-large cite,
								.top-bar-content,
								.search-action .search-field,
								.main-head .title,
								.navigation,
								.tag-share,
								.post-share-b .service,
								.post-share-float .share-text,
								.author-box,
								.comments-list .comment-content,
								.post-nav .label,
								.main-footer.dark .back-to-top,
								.lower-footer .social-icons,
								.main-footer .social-strip .social-link,
								.main-footer.bold .links .menu-item,
								.main-footer.bold .copyright,
								.archive-head,
								.archive-head .description,
								.cat-label a,
								.text,
								.section-head,
								.post-title-alt,
								.post-title,
								.block-heading,
								.block-head-b,
								.block-head-c,
								.small-post .post-title,
								.likes-count .number,
								.post-meta,
								.post-meta .text-in,
								.grid-post-b .read-more-btn,
								.list-post-b .read-more-btn,
								.post-footer .read-more,
								.post-footer .social-share,
								.post-footer .social-icons,
								.large-post-b .post-footer .author a,
								.main-slider,
								.slider-overlay .heading,
								.carousel-slider .category,
								.carousel-slider .heading,
								.grid-b-slider .heading,
								.bold-slider,
								.bold-slider .heading,
								.main-pagination,
								.main-pagination .load-button,
								.page-links,
								.post-content .wp-block-image figcaption,
								.textwidget .wp-block-image figcaption,
								.post-content .wp-caption-text,
								.textwidget .wp-caption-text,
								.post-content figcaption,
								.textwidget figcaption,
								.post-content,
								.post-content .read-more,
								.entry-content table,
								.widget-about .more,
								.widget-posts .post-title,
								.widget-posts .posts.full .counter:before,
								.widget-cta .label,
								.social-follow .service-link,
								.widget-twitter .meta .date,
								.widget-twitter .follow,
								.textwidget,
								.widget_categories,
								.widget_product_categories,
								.widget_archive,
								.widget_categories a,
								.widget_product_categories a,
								.widget_archive a,
								.wp-caption-text,
								figcaption,
								.wp-block-button .wp-block-button__link,
								.mobile-menu,
								.woocommerce .woocommerce-message,
								.woocommerce .woocommerce-error,
								.woocommerce .woocommerce-info,
								.woocommerce form .form-row,
								.woocommerce .main .button,
								.woocommerce .quantity .qty,
								.woocommerce nav.woocommerce-pagination,
								.woocommerce-cart .post-content,
								.woocommerce .woocommerce-ordering,
								.woocommerce-page .woocommerce-ordering,
								.woocommerce ul.products,
								.woocommerce.widget,
								.woocommerce .woocommerce-noreviews,
								.woocommerce div.product,
								.woocommerce #content div.product,
								.woocommerce #reviews #comments ol.commentlist .description,
								.woocommerce-cart .cart-empty,
								.woocommerce-cart .cart-collaterals .cart_totals table,
								.woocommerce-cart .cart-collaterals .cart_totals .button,
								.woocommerce .checkout .shop_table thead th,
								.woocommerce .checkout .shop_table .amount,
								.woocommerce-checkout #payment #place_order,
								.top-bar .posts-ticker,
								.post-content h1,
								.post-content h2,
								.post-content h3,
								.post-content h4,
								.post-content h5,
								.post-content h6
								',
						)
					),

					array(
						'name' => 'css_font_text',
						'label' => esc_html_x('Text Font', 'Admin', 'contentberg'),
						'value' => array('font_name' => ''),
						'desc'  => esc_html_x('Used for text chunks mainly.', 'Admin', 'contentberg'),
						'type'  => 'typography',
						'typeface' => true, // is family
						'css'   => array(
							'selectors' => '
								blockquote,
								.archive-head .description,
								.text,
								.post-content,
								.entry-content,
								.textwidget
							',
						)
					),

					array(
						'name' => 'css_font_post_titles',
						'label' => esc_html_x('Post Titles Font', 'Admin', 'contentberg'),
						'value' => array('font_name' => ''),
						'desc'  => esc_html_x('Font for all post titles.', 'Admin', 'contentberg'),
						'type'  => 'typography',
						'typeface' => true, // is family
						'css'   => array(
							'selectors' => '
								.post-title,
								.post-title-alt',
						)
					),
					
					array(
						'name' => 'css_font_sidebar_title',
						'label' => esc_html_x('Widget Titles', 'Admin', 'contentberg'),
						'value' => array('font_name' => '', 'font_weight' => '600', 'font_size' => '12'),
						'desc'  => '',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.sidebar .widget-title',
						)
					),
					
					
					array(
						'name' => 'css_font_nav_links',
						'label' => esc_html_x('Navigation Links', 'Admin', 'contentberg'),
						'value' => array('font_name' => '', 'font_weight' => '500', 'font_size' => '15'),
						'desc'  => '',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.navigation .menu > li > a, .navigation.inline .menu > li > a',
						)
					),
					
					array(
						'name' => 'css_font_nav_drops',
						'label' => esc_html_x('Navigation Dropdowns', 'Admin', 'contentberg'),
						'value' => array('font_name' => '', 'font_weight' => '500', 'font_size' => '15'),
						'desc'  => '',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.navigation .menu > li li a, .navigation.inline .menu > li li a',
						)
					),
					
					array(
						'name' => 'css_font_titles_large',
						'label' => esc_html_x('Large Post Titles', 'Admin', 'contentberg'),
						'value' => array('font_name' => '', 'font_weight' => '500', 'font_size' => '27'),
						'desc'  => '',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.post-title-alt',
						)
					),
					
					array(
						'name' => 'css_font_titles_grid',
						'label' => esc_html_x('Grid: Post Titles', 'Admin', 'contentberg'),
						'value' => array('font_name' => '', 'font_weight' => '500', 'font_size' => '24'),
						'desc'  => '',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.grid-post .post-title-alt',
						)
					),
						
					array(
						'name' => 'css_font_titles_list',
						'label' => esc_html_x('List: Post Titles', 'Admin', 'contentberg'),
						'value' => array('font_name' => '', 'font_weight' => '500', 'font_size' => '25'),
						'desc'  => '',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.list-post .post-tite',
						)
					),
					
					array(
						'name' => 'css_font_post_body',
						'label' => esc_html_x('Single Post/Excerpts Body', 'Admin', 'contentberg'),
						'value' => array('font_name' => '', 'font_weight' => '400', 'font_size' => '16'),
						'desc'  => 'Shared between both excerpts and single post.',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.post-content, .entry-content',
						)
					),

					array(
						'name' => 'css_font_post_body',
						'label' => esc_html_x('Single Post Body', 'Admin', 'contentberg'),
						'value' => array('font_name' => '', 'font_weight' => '400', 'font_size' => '19'),
						'desc'  => 'For single post only.',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.entry-content',
						)
					),
					
					array(
						'name' => 'css_font_post_h1',
						'label' => esc_html_x('Post Body H1', 'Admin', 'contentberg'),
						'value' => array('font_size' => '38'),
						'desc'  => '',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.post-content h1',
						)
					),
					
					array(
						'name' => 'css_font_post_h2',
						'label' => esc_html_x('Post Body H2', 'Admin', 'contentberg'),
						'value' => array('font_size' => '30'),
						'desc'  => '',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.post-content h2',
						)
					),
					
					array(
						'name' => 'css_font_post_h3',
						'label' => esc_html_x('Post Body H3', 'Admin', 'contentberg'),
						'value' => array('font_size' => '26'),
						'desc'  => '',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.post-content h3',
						)
					),
					
					array(
						'name' => 'css_font_post_h4',
						'label' => esc_html_x('Post Body H4', 'Admin', 'contentberg'),
						'value' => array('font_size' => '23'),
						'desc'  => '',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.post-content h4',
						)
					),
										
					array(
						'name' => 'css_font_post_h5',
						'label' => esc_html_x('Post Body H5', 'Admin', 'contentberg'),
						'value' => array('font_size' => '20'),
						'desc'  => '',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.post-content h5',
						)
					),
					
					array(
						'name' => 'css_font_post_h6',
						'label' => esc_html_x('Post Body H6', 'Admin', 'contentberg'),
						'value' => array('font_size' => '19'),
						'desc'  => '',
						'type'  => 'typography',
						'css'   => array(
							'selectors' => '.post-content h6',
						)
					),
				) // fields
			), // section
			
			array(
				'id' => 'typography-advanced',
				'title'  => esc_html_x('Typekit / Advanced', 'Admin', 'contentberg'),
				'fields' => array(
						
					array(
						'name' => 'typekit_id',
						'label' => esc_html_x('Typekit Kit ID', 'Admin', 'contentberg'),
						'value' => '',
						'desc'  => esc_html_x('Refer to the documentation to learn about using Typekit.', 'Admin', 'contentberg'),
						'type'  => 'text'
					),
						
					array(
						'name' => 'font_charset',
						'label' => esc_html_x('Google Font Charsets', 'Admin', 'contentberg'),
						'value' => array(),
						'type'  => 'checkboxes',
						'options' => array(
							'latin' => 'Latin',
							'latin-ext' => 'Latin Extended',
							'cyrillic'  => 'Cyrillic',
							'cyrillic-ext'  => 'Cyrillic Extended', 
							'greek'  => 'Greek',
							'greek-ext' => 'Greek Extended',
							'vietnamese' => 'Vietnamese'
						),
					),
					
				), // fields	
			), // section
			
		), // sections
	), // panel
	
	array(
		'sections' => array(
			array(
				'id' => 'import-demos',
				'title'  => esc_html_x('Import Demos', 'Admin', 'contentberg'),
				'fields' => array(
					array(
						'name'  => 'import_info',
						'label' => esc_html_x('Import Theme Demos', 'Admin', 'contentberg'),
						'type'  => 'content',
						'text'  => '',
					)
						
				),
			), // section
		
		) // sections
		
	), // panel

	array(
		'sections' => array(
			array(
				'id' => 'performance',
				'title'  => esc_html_x('Performance', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name'  => 'lazyload_enabled',
						'label' => esc_html_x('LazyLoad Images', 'Admin', 'contentberg'),
						'value' => 0,
						'desc'  => '',
						'type'  => 'checkbox',
					),
						
					array(
						'name'  => 'lazyload_type',
						'label' => esc_html_x('Lazy Loader Type', 'Admin', 'contentberg'),
						'value' => 'normal',
						'desc'  => '',
						'type'  => 'radio',
						'options' => array(
							'normal' => esc_html_x('Normal - Load Images on scroll', 'Admin', 'contentberg'),
							'smart' => esc_html_x('Smart - Preload Images on Desktops', 'Admin', 'contentberg')
						)
					),
						
					array(
						'name'  => 'lazyload_aggressive',
						'label' => esc_html_x('Aggressive Lazy Load', 'Admin', 'contentberg'),
						'value' => 0,
						'desc'  => esc_html_x('By default, only featured images are preloaded. Aggressive enables lazyloading on all sidebar widgets and footer as well.', 'Admin', 'contentberg'),
						'type'  => 'checkbox',
					),
					
				) // fields
			), // section
		
		) // sections
		
	), // panel

	array(
		'sections' => array(
			array(
				'id' => 'eu-gdpr',
				'title'  => esc_html_x('EU GDPR & Privacy', 'Admin', 'contentberg'),
				'fields' => array(
			
					array(
						'name' => '',
						'type' => 'content',
						'text' => $privacy_info
					),
				) // fields
				
			) // section
			
		) // sections
		
	), // pseudo panel
	
		
	array(
		'sections' => array(
			array(
				'id' => 'reset-customizer',
				'title'  => esc_html_x('Reset Settings', 'Admin', 'contentberg'),
				'fields' => array(
					array(
						'name' => 'reset_customizer',
						'value' => esc_html_x('Reset All Settings', 'Admin', 'contentberg'),
						'desc'  => esc_html_x('Clicking the Reset button will revert all settings in the customizer except for menus, widgets and site identity.', 'Admin', 'contentberg'),
						'type'  => 'button',
						'input_attrs' => array(
							'class' => 'button reset-customizer',
						),
					)
					
				) // fields
			), // section
		
		) // sections
		
	), // panel
	
));