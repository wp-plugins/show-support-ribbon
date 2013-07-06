<?php 
/*
Plugin Name: Show Support Ribbon
Plugin URI: http://perishablepress.com/show-support-ribbon/
Description: Simple plugin to display a custom support ribbon on your site.
Author: Jeff Starr
Author URI: http://monzilla.biz/
Donate link: http://m0n.co/donate
Version: 20130705
License: GPL v2
Usage: Visit the plugin's settings page for shortcodes, template tags, and more information.
Tags: ribbon, banner, button, badge, link, custom, support, charity, politics, organization, event, rally, fundraiser
*/

// NO EDITING REQUIRED - PLEASE SET PREFERENCES IN THE WP ADMIN!

$ssr_plugin  = __('Show Support Ribbon', 'ssr');
$ssr_options = get_option('ssr_options');
$ssr_path    = plugin_basename(__FILE__); // 'show-support-ribbon/show-support-ribbon.php';
$ssr_homeurl = 'http://perishablepress.com/show-support-ribbon/';
$ssr_version = '20130705';

// require minimum version of WordPress
add_action('admin_init', 'ssr_require_wp_version');
function ssr_require_wp_version() {
	global $wp_version, $ssr_path, $ssr_plugin;
	if (version_compare($wp_version, '3.4', '<')) {
		if (is_plugin_active($ssr_path)) {
			deactivate_plugins($ssr_path);
			$msg =  '<strong>' . $ssr_plugin . '</strong> ' . __('requires WordPress 3.4 or higher, and has been deactivated!', 'ssr') . '<br />';
			$msg .= __('Please return to the', 'ssr') . ' <a href="' . admin_url() . '">' . __('WordPress Admin area', 'ssr') . '</a> ' . __('to upgrade WordPress and try again.', 'ssr');
			wp_die($msg);
		}
	}
}

// display the ribbon
function ssr_display_ribbon() { 
	global $ssr_options;

	$style  = $ssr_options['ssr_style'];
	$enable = $ssr_options['ssr_enable'];
	$href   = $ssr_options['ssr_href'];
	$title  = $ssr_options['ssr_title'];
	$link   = $ssr_options['ssr_link'];
	$blank  = $ssr_options['ssr_blank'];

	if ($enable == true) {
		if ($style == 'ssr_badge') {
			$css_div  = 'position:fixed;right:5px;top:5px;z-index:999999;';
			$css_link = 'display:block;width:88px;height:88px;color:#fff;background:rgba(102,153,204,0.7);font-size:11px;text-align:center;line-height:88px;text-decoration:none;font-weight:bold;border:2px solid #efefef;-webkit-border-radius:99px;-moz-border-radius:99px;border-radius:99px;-webkit-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);-moz-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);';

		} elseif ($style == 'ssr_banner') {
			$css_div  = 'position:fixed;right:-2px;top:5px;z-index:999999;';
			$css_link = 'display:block;width:120px;height:40px;color:rgba(51,102,153,0.9);background:rgba(255,255,255,0.7);font-size:12px; text-align:center; line-height:40px; text-decoration:none; font-weight:bold;border:1px solid rgba(102,153,204,0.7);-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);-moz-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);';

		} elseif ($style == 'ssr_ribbon') {
			$css_div = 'position:fixed;right:-60px;top:20px;z-index:999999;';
			$css_link = 'display:block;width:200px;height:30px;color:#fff;background:rgba(102,153,204,0.9);font-size:12px;line-height:30px;text-align:center;text-decoration:none; border:1px solid rgba(255,255,255,0.7);-webkit-transform:rotate(40deg);-moz-transform:rotate(40deg);-ms-transform:rotate(40deg);-o-transform:rotate(40deg);-webkit-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);-moz-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);';

		} elseif ($style == 'ssr_link') {
			$css_div = 'position:fixed; right:5px; top:5px; z-index:999999;';
			$css_link = 'font-size:12px;';

		} elseif ($style == 'ssr_custom') {
			$css_div  = $ssr_options['ssr_outer'];
			$css_link = $ssr_options['ssr_inner'];
		}
		if ($blank == true) {
			return "\n" . '<div id="show-support-ribbon" style="' . $css_div . '"><a href="' . $href . '" title="' . $title . '" style="' . $css_link . '" target="_blank">' . $link . '</a><div>' . "\n\n";
		} else {
			return "\n" . '<div id="show-support-ribbon" style="' . $css_div . '"><a href="' . $href . '" title="' . $title . '" style="' . $css_link . '">' . $link . '</a><div>' . "\n\n";
		}
	}
}

// enable display ribbon in footer
add_action('wp_footer', 'ssr_enable_display_ribbon');
function ssr_enable_display_ribbon() {
	echo ssr_display_ribbon();
}

// shortcode to display ribbon: [show_support_ribbon]
add_shortcode('show_support_ribbon','ssr_shortcode');
function ssr_shortcode() {
	return ssr_display_ribbon();
}

// template tag to display ribbon: show_support_ribbon()
function show_support_ribbon() {
	echo ssr_display_ribbon();
}

// display settings link on plugin page
add_filter ('plugin_action_links', 'ssr_plugin_action_links', 10, 2);
function ssr_plugin_action_links($links, $file) {
	global $ssr_path;
	if ($file == $ssr_path) {
		$ssr_links = '<a href="' . get_admin_url() . 'options-general.php?page=' . $ssr_path . '">' . __('Settings', 'ssr') .'</a>';
		array_unshift($links, $ssr_links);
	}
	return $links;
}

// delete plugin settings
function ssr_delete_plugin_options() {
	delete_option('ssr_options');
}
if ($ssr_options['default_options'] == 1) {
	register_uninstall_hook (__FILE__, 'ssr_delete_plugin_options');
}

// define default settings
register_activation_hook (__FILE__, 'ssr_add_defaults');
function ssr_add_defaults() {
	$tmp = get_option('ssr_options');
	if(($tmp['default_options'] == '1') || (!is_array($tmp))) {
		$arr = array(
			'default_options' => 0,
			'ssr_enable'      => 0,
			'ssr_style'       => 'ssr_ribbon',
			'ssr_outer'       => 'position:fixed;right:-60px;top:20px;z-index:9999;',
			'ssr_inner'       => 'display:block;width:200px;height:30px;color:#fff;background:rgba(102,153,204,0.9);font-size:12px;line-height:30px;text-align:center;text-decoration:none; border:1px solid rgba(255,255,255,0.7);-webkit-transform:rotate(40deg);-moz-transform:rotate(40deg);-ms-transform:rotate(40deg);-o-transform:rotate(40deg);-webkit-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);-moz-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);',
			'ssr_href'        => 'http://example.com/',
			'ssr_title'       => 'Your event here!',
			'ssr_link'        => 'Show Support!',
			'ssr_blank'       => 1,
		);
		update_option('ssr_options', $arr);
	}
}

// define style options
$ssr_display_styles = array(
	'ssr_badge'  => array(
		'value' => 'ssr_badge',
		'label' => 'Badge'
	),
	'ssr_banner' => array(
		'value' => 'ssr_banner',
		'label' => 'Banner'
	),
	'ssr_ribbon' => array(
		'value' => 'ssr_ribbon',
		'label' => 'Ribbon'
	),
	'ssr_link'   => array(
		'value' => 'ssr_link',
		'label' => 'Link'
	),
	'ssr_custom' => array(
		'value' => 'ssr_custom',
		'label' => 'Custom'
	)
);

// whitelist settings
add_action ('admin_init', 'ssr_init');
function ssr_init() {
	register_setting('ssr_plugin_options', 'ssr_options', 'ssr_validate_options');
}

// sanitize and validate input
function ssr_validate_options($input) {
	global $ssr_display_styles;

	if (!isset($input['default_options'])) $input['default_options'] = null;
	$input['default_options'] = ($input['default_options'] == 1 ? 1 : 0);

	if (!isset($input['ssr_enable'])) $input['ssr_enable'] = null;
	$input['ssr_enable'] = ($input['ssr_enable'] == '1' ? '1' : '0');

	if (!isset($input['ssr_blank'])) $input['ssr_blank'] = null;
	$input['ssr_blank'] = ($input['ssr_blank'] == '1' ? '1' : '0');

	if (!isset($input['ssr_style'])) $input['ssr_style'] = null;
	if (!array_key_exists($input['ssr_style'], $ssr_display_styles)) $input['ssr_style'] = null;

	$input['ssr_outer'] = wp_filter_post_kses($input['ssr_outer']);
	$input['ssr_inner'] = wp_filter_post_kses($input['ssr_inner']);
	$input['ssr_href']  = wp_filter_post_kses($input['ssr_href']);
	$input['ssr_title'] = wp_filter_post_kses($input['ssr_title']);
	$input['ssr_link']  = wp_filter_post_kses($input['ssr_link']);

	return $input;
}

// add the options page
add_action ('admin_menu', 'ssr_add_options_page');
function ssr_add_options_page() {
	global $ssr_plugin;
	add_options_page($ssr_plugin, $ssr_plugin, 'manage_options', __FILE__, 'ssr_render_form');
}

// create the options page
function ssr_render_form() {
	global $ssr_plugin, $ssr_options, $ssr_path, $ssr_homeurl, $ssr_version, $ssr_display_styles; ?>

	<style type="text/css">
		.mm-panel-overview { padding-left: 175px; background: url(<?php echo plugins_url(); ?>/show-support-ribbon/ssr-logo.png) no-repeat 15px 0; }

		#mm-plugin-options h2 small { font-size: 60%; }
		#mm-plugin-options h3 { cursor: pointer; }
		#mm-plugin-options h4, 
		#mm-plugin-options p { margin: 15px; line-height: 18px; }
		#mm-plugin-options ul { margin: 15px 15px 25px 40px; line-height: 18px; }
		#mm-plugin-options li { margin: 5px 0; list-style-type: disc; }
		#mm-plugin-options abbr { cursor: help; border-bottom: 1px dotted #dfdfdf; }

		.mm-table-wrap { margin: 15px; }
		.mm-table-wrap td { padding: 5px 10px; vertical-align: middle; }
		.mm-table-wrap .widefat th { width: 20%; padding: 10px 15px; vertical-align: middle; }
		.mm-table-wrap .widefat td { width: 80%; padding: 10px; vertical-align: middle; }

		.mm-item-caption { margin: 3px 0 0 3px; font-size: 80%; color: #777; }
		.mm-item-caption code { background-color: #f4f4f4; }
		.mm-code-example { margin: 10px 0 20px 0; }
		.mm-code-example div { margin: 0 0 15px 15px; }
		.mm-code-example code { padding: 3px; background-color: #fafae0; color: #555; font-size: 14px; line-height: 18px; }
		.mm-radio-inputs { margin: 5px 0; }

		#setting-error-settings_updated { margin: 10px 0; }
		#setting-error-settings_updated p { margin: 5px; }
		#mm-plugin-options .button-primary { margin: 0 0 15px 15px; }

		#mm-panel-tertiary pre { margin: 10px 0 20px 30px; line-height: 18px; }
		#mm-panel-tertiary hr { width: 97%; margin-bottom: 25px; }
		.mm-example-heading { margin-left: 15px; }
		.mm-example-heading code { font-size: 13px; }

		.mm-custom-style { background-color: #eaeaea; }
		.mm-custom-style pre { margin: 10px 0 0 0; }
		.mm-custom-style label code { font-size: 13px; }
		#mm-panel-toggle { margin: 5px 0; }
		#mm-credit-info { margin-top: -5px; }
		#mm-iframe-wrap { width: 100%; height: 250px; overflow: hidden; }
		#mm-iframe-wrap iframe { width: 100%; height: 100%; overflow: hidden; margin: 0; padding: 0; }
	</style>

	<div id="mm-plugin-options" class="wrap">
		<?php screen_icon(); ?>

		<h2><?php echo $ssr_plugin; ?> <small><?php echo 'v' . $ssr_version; ?></small></h2>
		<div id="mm-panel-toggle"><a href="<?php get_admin_url() . 'options-general.php?page=' . $ssr_path; ?>"><?php _e('Toggle all panels', 'ssr'); ?></a></div>

		<form method="post" action="options.php">
			<?php $ssr_options = get_option('ssr_options'); settings_fields('ssr_plugin_options'); ?>

			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					<div id="mm-panel-overview" class="postbox">
						<h3><?php _e('Overview', 'ssr'); ?></h3>
						<div class="toggle">
							<div class="mm-panel-overview">
								<p>
									<strong><?php echo $ssr_plugin; ?></strong> <?php _e('(SSR) displays a custom support ribbon on your site. SSR includes four built-in display styles: badge, banner, ribbon, or link.', 'ssr'); ?>
									<?php _e('SSR also includes the option to use your own custom CSS to style the ribbon and position it anywhere on the page. See the "Customization Tips" panel for more information.', 'ssr'); ?>
									<?php _e('Optionally you may use the shortcode to display the ribbon on any post or page, or use the template tag to display the ribbon anywhere in your theme template.', 'ssr'); ?>
								</p>
								<ul>
									<li><?php _e('To configure SSR, visit the', 'ssr'); ?> <a id="mm-panel-primary-link" href="#mm-panel-primary"><?php _e('Options panel', 'ssr'); ?></a>.</li>
									<li><?php _e('For shortcodes and template tags, visit', 'ssr'); ?> <a id="mm-panel-secondary-link" href="#mm-panel-secondary"><?php _e('Shortcodes &amp; Template Tags', 'ssr'); ?></a>.</li>
									<li><?php _e('For some alternate CSS styles, visit', 'ssr'); ?> <a id="mm-panel-tertiary-link" href="#mm-panel-tertiary"><?php _e('Customization Tips', 'ssr'); ?></a>.</li>
									<li><?php _e('For more information check the <code>readme.txt</code> and', 'ssr'); ?> <a href="<?php echo $ssr_homeurl; ?>"><?php _e('SSR Homepage', 'ssr'); ?></a>.</li>
								</ul>
							</div>
						</div>
					</div>
					<div id="mm-panel-primary" class="postbox">
						<h3><?php _e('Options', 'ssr'); ?></h3>
						<div class="toggle<?php if (!isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<p><?php _e('Choose your options..', 'ssr'); ?></p>
							<div class="mm-table-wrap">
								<table class="widefat mm-table">
									<tr>
										<th scope="row"><label class="description" for="ssr_options[ssr_enable]"><?php _e('Display the ribbon?', 'ssr'); ?></label></th>
										<td><input type="checkbox" name="ssr_options[ssr_enable]" value="1" <?php if (isset($ssr_options['ssr_enable'])) { checked('1', $ssr_options['ssr_enable']); } ?> /> 
										<?php _e('Check the box to display the ribbon on your site.', 'ssr'); ?></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="ssr_options[ssr_blank]"><?php _e('Open ribbon link in new tab?', 'ssr'); ?></label></th>
										<td><input type="checkbox" name="ssr_options[ssr_blank]" value="1" <?php if (isset($ssr_options['ssr_blank'])) { checked('1', $ssr_options['ssr_blank']); } ?> /> 
										<?php _e('Check the box to open the ribbon link in a new tab. Or, leave unchecked to open in same tab.', 'ssr'); ?></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="ssr_options[ssr_style]"><?php _e('Select your style', 'ssr'); ?></label></th>
										<td>
											<?php if (!isset($checked)) $checked = '';
												foreach ($ssr_display_styles as $ssr_display_style) {
													$radio_setting = $ssr_options['ssr_style'];
													if ('' != $radio_setting) {
														if ($ssr_options['ssr_style'] == $ssr_display_style['value']) {
															$checked = "checked=\"checked\"";
														} else {
															$checked = '';
														}
													} ?>
													<div class="mm-radio-inputs">
														<input type="radio" name="ssr_options[ssr_style]" value="<?php esc_attr_e($ssr_display_style['value']); ?>" <?php echo $checked; ?> /> 
														<?php echo $ssr_display_style['label']; ?>
													</div>
											<?php } ?>
										</td>
									</tr>
									<tr class="mm-custom-style">
										<th scope="row"><label class="description"><?php _e('HTML', 'ssr'); ?></label></th>
										<td>
											<div><?php _e('<abbr title="For Your Information">FYI</abbr>: here is the markup that is used to create the ribbon:', 'ssr'); ?></div>
<pre>&lt;div id="show-support-ribbon" style="{CSS for &lt;div&gt;}"&gt;
	&lt;a href="{URL}" title="{title}" style="{CSS for &lt;a&gt;}" target="_blank"&gt;{link}&lt;/a&gt;
&lt;/div&gt;</pre>
										</td>
									</tr>
									<tr class="mm-custom-style">
										<th scope="row"><label class="description" for="ssr_options[ssr_outer]"><?php _e('<code>&lt;div&gt;</code> styles', 'ssr'); ?></label></th>
										<td><textarea class="textarea" rows="7" cols="55" name="ssr_options[ssr_outer]"><?php echo esc_textarea($ssr_options['ssr_outer']); ?></textarea>
										<div class="mm-item-caption"><?php _e('Enter some CSS for the <code>&lt;div&gt;</code> tag.', 'ssr'); ?></div></td>
									</tr>
									<tr class="mm-custom-style">
										<th scope="row"><label class="description" for="ssr_options[ssr_inner]"><?php _e('<code>&lt;a&gt;</code> styles', 'ssr'); ?></label></th>
										<td><textarea class="textarea" rows="7" cols="55" name="ssr_options[ssr_inner]"><?php echo esc_textarea($ssr_options['ssr_inner']); ?></textarea>
										<div class="mm-item-caption"><?php _e('Enter some CSS for the <code>&lt;a&gt;</code> tag.', 'ssr'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="ssr_options[ssr_href]"><?php _e('Link URL', 'ssr'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="ssr_options[ssr_href]" value="<?php echo $ssr_options['ssr_href']; ?>" />
										<div class="mm-item-caption"><?php _e('Enter the URL that should be used for the ribbon link.', 'ssr'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="ssr_options[ssr_title]"><?php _e('Link title', 'ssr'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="ssr_options[ssr_title]" value="<?php echo $ssr_options['ssr_title']; ?>" />
										<div class="mm-item-caption"><?php _e('Enter the title that should be used for the ribbon link.', 'ssr'); ?></div></td>
									</tr>
									<tr>
										<th scope="row"><label class="description" for="ssr_options[ssr_link]"><?php _e('Link text', 'ssr'); ?></label></th>
										<td><input type="text" size="50" maxlength="200" name="ssr_options[ssr_link]" value="<?php echo $ssr_options['ssr_link']; ?>" />
										<div class="mm-item-caption"><?php _e('Enter the anchor text that should be used for the ribbon link.', 'ssr'); ?></div></td>
									</tr>
								</table>
							</div>
							<input type="submit" class="button-primary" value="<?php _e('Save Settings', 'ssr'); ?>" />
						</div>
					</div>
					<div id="mm-panel-secondary" class="postbox">
						<h3><?php _e('Shortcode &amp; Template Tag', 'ssr'); ?></h3>
						<div class="toggle<?php if (!isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<div class="mm-code-example">
								<h4><?php _e('Shortcode', 'ssr'); ?></h4>
								<div><?php _e('Display the ribbon on any post or page:', 'ssr'); ?></div>
								<div><code>[show_support_ribbon]</code></div>

								<h4><?php _e('Template tag', 'ssr'); ?></h4>
								<div><?php _e('Display the ribbon anywhere in your theme:', 'ssr'); ?></div>
								<div><code>&lt;?php if (function_exists('show_support_ribbon')) show_support_ribbon(); ?&gt;</code></div>
							</div>
						</div>
					</div>
					<div id="mm-panel-tertiary" class="postbox">
						<h3><?php _e('Customization Tips', 'ssr'); ?></h3>
						<div class="toggle<?php if (!isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<p><?php _e('For those new to styling HTML with CSS, here are some recipes and tips.', 'ssr'); ?></p>
							<ul>
								<li><strong>Location</strong> &mdash; For the <code>&lt;div&gt;</code>, use <code>position:fixed;</code> and then specify the location with something like <code>right:0; bottom:5px;</code>.</li>
								<li><strong>Structural styles</strong> &mdash; Apply structural styles such as <code>margin</code>, <code>width</code>, and <code>height</code> to the <em>block-level</em> element, <code>&lt;div&gt;</code>.</li>
								<li><strong>Aesthetic styles</strong> &mdash; Apply aesthetic styles such as <code>padding</code>, <code>color</code>, and <code>font-family</code> to the <em>inline-level</em> element, <code>&lt;a&gt;</code>.</li>
								<li><strong>Recipes</strong> &mdash; Using the recipes below as Custom CSS, you can change the position, size, and color of the ribbon. And much more if you get creative ;)</li>
							</ul>
							<hr />
							<div class="mm-example-heading"><strong>Badge</strong> <code>&lt;div&gt;</code></div>
							<ul>
								<li>Display top left: <code>position:fixed; left:5px; top:5px; z-index:9999;</code></li>
								<li>Display top right: <code>position:fixed; right:5px; top:5px; z-index:9999;</code></li>
								<li>Display bottom left: <code>position:fixed; left:5px; bottom:5px; z-index:9999;</code></li>
								<li>Display bottom right: <code>position:fixed; right:5px; bottom:5px; z-index:9999;</code></li>
							</ul>
							<div class="mm-example-heading"><strong>Badge</strong> <code>&lt;a&gt;</code></div>
<pre>display:block; width:88px; height:88px; color:#fff; background:rgba(102,153,204,0.7); 
font-size:11px; text-align:center; line-height:88px; text-decoration:none; font-weight:bold; 
border:2px solid #efefef;
-webkit-border-radius:99px; 
-moz-border-radius:99px; 
border-radius:99px;
-webkit-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3); 
-moz-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3); 
box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);</pre>
							<hr />
							<div class="mm-example-heading"><strong>Banner</strong> <code>&lt;div&gt;</code></div>
							<ul>
								<li>Display top left: <code>position:fixed; left:-2px; top:5px; z-index:9999;</code></li>
								<li>Display top right: <code>position:fixed; right:-2px; top:5px; z-index:9999;</code></li>
								<li>Display bottom left: <code>position:fixed; left:-2px; bottom:5px; z-index:9999;</code></li>
								<li>Display bottom right: <code>position:fixed; right:-2px; bottom:5px; z-index:9999;</code></li>
							</ul>
							<div class="mm-example-heading"><strong>Banner</strong> <code>&lt;a&gt;</code></div>
<pre>display:block; width:120px; height:40px; color:rgba(51,102,153,0.9); background:rgba(255,255,255,0.7); 
font-size:12px; text-align:center; line-height:40px; text-decoration:none; font-weight:bold;
border:1px solid rgba(102,153,204,0.7); 
-webkit-border-radius:3px; 
-moz-border-radius:3px; 
border-radius:3px;
-webkit-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3); 
-moz-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3); 
box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);</pre>
							<hr />
							<div class="mm-example-heading"><strong>Ribbon</strong> <code>&lt;div&gt;</code></div>
							<ul>
								<li>Display top left: <code>position:fixed; left:-60px; top:20px; z-index:9999;</code></li>
								<li>Display top right: <code>position:fixed; right:-60px; top:20px; z-index:9999;</code></li>
								<li>Display bottom left: <code>position:fixed; left:-60px; bottom:20px; z-index:9999;</code></li>
								<li>Display bottom right: <code>position:fixed; right:-60px; bottom:20px; z-index:9999;</code></li>
							</ul>
							<div class="mm-example-heading"><strong>Ribbon</strong> <code>&lt;a&gt;</code> (top left)</div>
<pre>display:block; width:200px; height:30px; color:#fff; background:rgba(102,153,204,0.9); 
font-size:12px; line-height:30px; text-align:center; text-decoration:none; 
border:1px solid rgba(255,255,255,0.7);
-webkit-transform:rotate(-40deg); 
-moz-transform:rotate(-40deg); 
-ms-transform:rotate(-40deg); 
-o-transform:rotate(-40deg);</pre>
							<div class="mm-example-heading"><strong>Ribbon</strong> <code>&lt;a&gt;</code> (top right)</div>
<pre>display:block; width:200px; height:30px; color:#fff; background:rgba(102,153,204,0.9); 
font-size:12px; line-height:30px; text-align:center; text-decoration:none; 
border:1px solid rgba(255,255,255,0.7);
-webkit-transform:rotate(40deg); 
-moz-transform:rotate(40deg); 
-ms-transform:rotate(40deg); 
-o-transform:rotate(40deg);</pre>
							<div class="mm-example-heading"><strong>Ribbon</strong> <code>&lt;a&gt;</code> (bottom left)</div>
<pre>display:block; width:200px; height:30px; color:#fff; background:rgba(102,153,204,0.9); 
font-size:12px; line-height:30px; text-align:center; text-decoration:none; 
border:1px solid rgba(255,255,255,0.7);
-webkit-transform:rotate(40deg); 
-moz-transform:rotate(40deg); 
-ms-transform:rotate(40deg); 
-o-transform:rotate(40deg);</pre>
							<div class="mm-example-heading"><strong>Ribbon</strong> <code>&lt;a&gt;</code> (bottom right)</div>
<pre>display:block; width:200px; height:30px; color:#fff; background:rgba(102,153,204,0.9); 
font-size:12px; line-height:30px; text-align:center; text-decoration:none; 
border:1px solid rgba(255,255,255,0.7);
-webkit-transform:rotate(-40deg); 
-moz-transform:rotate(-40deg); 
-ms-transform:rotate(-40deg); 
-o-transform:rotate(-40deg);</pre>
							<div class="mm-example-heading">Note: to add a drop shadow, add the following CSS to the <code>&lt;a&gt;</code> tag</div>
<pre>-webkit-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3); 
-moz-box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3); 
box-shadow:1px 1px 3px 0 rgba(0,0,0,0.3);</pre>
							<hr />
							<div class="mm-example-heading"><strong>Link</strong> <code>&lt;div&gt;</code></div>
							<ul>
								<li>Display top left: <code>position:fixed; left:5px; top:5px; z-index:9999;</code></li>
								<li>Display top right: <code>position:fixed; right:5px; top:5px; z-index:9999;</code></li>
								<li>Display bottom left: <code>position:fixed; left:5px; bottom:5px; z-index:9999;</code></li>
								<li>Display bottom right: <code>position:fixed; right:5px; bottom:5px; z-index:9999;</code></li>
							</ul>
							<div class="mm-example-heading"><strong>Link</strong> <code>&lt;a&gt;</code></div>
							<pre>font-size:12px;</pre>
						</div>
					</div>
					<div id="mm-restore-settings" class="postbox">
						<h3><?php _e('Restore Default Options', 'ssr'); ?></h3>
						<div class="toggle<?php if (!isset($_GET["settings-updated"])) { echo ' default-hidden'; } ?>">
							<p>
								<input name="ssr_options[default_options]" type="checkbox" value="1" id="mm_restore_defaults" <?php if (isset($ssr_options['default_options'])) { checked('1', $ssr_options['default_options']); } ?> /> 
								<label class="description" for="ssr_options[default_options]"><?php _e('Restore default options upon plugin deactivation/reactivation.', 'ssr'); ?></label>
							</p>
							<p>
								<small>
									<?php _e('<strong>Tip:</strong> leave this option unchecked to remember your settings. Or, to go ahead and restore all default options, check the box, save your settings, and then deactivate/reactivate the plugin.', 'ssr'); ?>
								</small>
							</p>
							<input type="submit" class="button-primary" value="<?php _e('Save Settings', 'ssr'); ?>" />
						</div>
					</div>
					<div id="mm-panel-current" class="postbox">
						<h3><?php _e('Updates &amp; Info', 'ssr'); ?></h3>
						<div class="toggle">
							<div id="mm-iframe-wrap">
								<iframe src="http://perishablepress.com/current/index-ssr.html"></iframe>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="mm-credit-info">
				<a target="_blank" href="<?php echo $ssr_homeurl; ?>" title="<?php echo $ssr_plugin; ?> Homepage"><?php echo $ssr_plugin; ?></a> by 
				<a target="_blank" href="http://twitter.com/perishable" title="Jeff Starr on Twitter">Jeff Starr</a> @ 
				<a target="_blank" href="http://monzilla.biz/" title="Obsessive Web Design &amp; Development">Monzilla Media</a>
			</div>
		</form>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			// toggle panels
			jQuery('.default-hidden').hide();
			jQuery('#mm-panel-toggle a').click(function(){
				jQuery('.toggle').slideToggle(300);
				return false;
			});
			jQuery('h3').click(function(){
				jQuery(this).next().slideToggle(300);
			});
			jQuery('#mm-panel-primary-link').click(function(){
				jQuery('.toggle').hide();
				jQuery('#mm-panel-primary .toggle').slideToggle(300);
				return true;
			});
			jQuery('#mm-panel-secondary-link').click(function(){
				jQuery('.toggle').hide();
				jQuery('#mm-panel-secondary .toggle').slideToggle(300);
				return true;
			});
			jQuery('#mm-panel-tertiary-link').click(function(){
				jQuery('.toggle').hide();
				jQuery('#mm-panel-tertiary .toggle').slideToggle(300);
				return true;
			});
			// prevent accidents
			if(!jQuery("#mm_restore_defaults").is(":checked")){
				jQuery('#mm_restore_defaults').click(function(event){
					var r = confirm("<?php _e('Are you sure you want to restore all default options? (this action cannot be undone)', 'ssr'); ?>");
					if (r == true){  
						jQuery("#mm_restore_defaults").attr('checked', true);
					} else {
						jQuery("#mm_restore_defaults").attr('checked', false);
					}
				});
			}
			// radio select toggle
			if(jQuery('form input[type=radio]:checked').val() == 'ssr_custom'){
				jQuery('.mm-custom-style').show();
			} else {
				jQuery('.mm-custom-style').hide();
			}
			jQuery('form input:radio').change(function(){
				if (jQuery(this).val() == 'ssr_custom') {
					jQuery('.mm-custom-style').slideDown('fast');
				} else {
					jQuery('.mm-custom-style').slideUp('fast');
				}
			});
		});
	</script>

<?php } ?>