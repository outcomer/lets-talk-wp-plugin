<?php
/*
Plugin Name: Lets Talk Button
Description: Integrates a floating contact button and opens an modal with your favorite contact form.
Version: 1.0
Author: David Evdoshchenko
Text Domain: lets-talk
Domain Path: /languages
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lets Talk Contact Button is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Lets Talk Contact Button is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Lets Talk Contact Button. If not, see License URI: https://www.gnu.org/licenses/gpl-2.0.html.
*/

defined('ABSPATH') or die('Are you ok?');

class LetsTalk
{
	const OPTIONS = 'letstalk_options';

	public function __construct()
	{
		$this->hooks();
	}

	private function hooks()
	{
		register_activation_hook(__FILE__, [$this, 'lets_talk_set_default_options']);

		add_action('wp_enqueue_scripts', [$this, 'styles_method']);

		add_action('plugins_loaded', [$this, 'load_textdomain']);
		add_action('admin_init', [$this, 'settings_init']);

		add_action('admin_menu', [$this, 'options_page']);
		add_action('wp_footer', [$this, 'body_code']);
	}

	public function lets_talk_set_default_options()
	{
		if (false !== get_option(self::OPTIONS, false)) {
			return;
		}

		$default_options = [
			'field_bgcolor'   => 'grey',
			'field_rotate'    => '0',
			'field_shortcode' => null,
			'field_mode'      => 'css',
		];

		add_option(self::OPTIONS, $default_options);
	}

	public function load_textdomain()
	{
		load_plugin_textdomain('lets-talk', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}

	public function settings_init()
	{
		register_setting('lets-talk', self::OPTIONS);
		add_settings_section('section_developers', __('Plugin Settings', 'lets-talk'), [$this, 'section_developers_cb'], 'lets-talk');

		add_settings_field(
			'lets-talk_field_shortcode',
			__('Insert here your shortcode:', 'lets-talk'),
			[$this, 'field_shortcode_cb'],
			'lets-talk',
			'section_developers',
			[
				'label_for'            => 'field_shortcode',
				'class'                => 'letstalk_row',
				'letstalk_custom_data' => 'custom',
			]
		);

		add_settings_field(
			'lets-talk_field_bgcolor',
			__('Button color (optional):', 'lets-talk'),
			[$this, 'field_bgcolor_cb'],
			'lets-talk',
			'section_developers',
			[
				'label_for'            => 'field_bgcolor',
				'class'                => 'letstalk_row',
				'letstalk_custom_data' => 'custom',
			]
		);

		add_settings_field(
			'lets-talk_field_rotate',
			__('Disable rotation:', 'lets-talk'),
			[$this, 'field_rotate_cb'],
			'lets-talk',
			'section_developers',
			[
				'label_for'            => 'field_rotate',
				'class'                => 'letstalk_row',
				'letstalk_custom_data' => 'custom',
			]
		);

		add_settings_field(
			'lets-talk_field_mode',
			__('Choose the integration mode:', 'lets-talk'),
			[$this, 'field_mode_cb'],
			'lets-talk',
			'section_developers',
			[
				'label_for'            => 'field_mode',
				'class'                => 'letstalk_row',
				'letstalk_custom_data' => 'custom',
			]
		);
	}

	public function styles_method()
	{
		wp_enqueue_style('dashicons');
		wp_enqueue_style('letstalk-style', plugin_dir_url(__FILE__) . 'assets/css/style.css', [], 1.0, 'screen');

		$options = get_option(self::OPTIONS);

		if (! empty($options['field_rotate'])) {
			$rotate = "rotate(90deg)";
		} else {
			$rotate = "none";
		}

		$color = get_option(self::OPTIONS)['field_bgcolor'];
		$custom_css = "
			i.letstalk-icons {
				background: {$color} !important;
				border-color: {$color} !important;
			}
			i.letstalk-icons:hover {
				color: {$color} !important;
			}
			i.letstalk-icons:after {
				border-color: {$color} !important;	
			}
			i.letstalk-icons:hover {
				transform: {$rotate} !important;
			}
			#letstalk-popup input[type=\"submit\"]:hover {
				border: 1px solid {$color} !important;
				background-color: {$color} !important;
			}";
		wp_add_inline_style('letstalk-style', $custom_css);
	}

	public function section_developers_cb($args)
	{
		?>
		<p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('1. Install your favorite contact form plugin. Tested with Contact Form 7, Ninja Forms & Caldera Forms.', 'lets-talk'); ?></p>
		<p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('2. Generate a form and entering the shortcode into the field. This form will shown in the modal, when the Lets Talk Button is clicked.', 'lets-talk'); ?></p>
		<p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('3. Change the main color of the button.', 'lets-talk'); ?></p>
		<p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('4. Please change the integration mode if there are problems with the theme you are using.', 'lets-talk'); ?></p>
		<?php
	}

	public function field_shortcode_cb($args)
	{
		$options = get_option(self::OPTIONS);
		?>
		<input type="text" name="letstalk_options[field_shortcode]" value="<?php echo str_replace('"', '&quot;', $options['field_shortcode']); ?>" style="width: 450px;">
		<?php
	}

	public function field_bgcolor_cb($args)
	{
		$options = get_option(self::OPTIONS);
		?>
		<input type="text" name="letstalk_options[field_bgcolor]" value="<?php echo str_replace('"', '&quot;', $options['field_bgcolor']); ?>" style="width: 80px;">
		<?php
	}

	public function field_rotate_cb()
	{
		$options = get_option(self::OPTIONS);
		?>
		<input type="hidden" name="letstalk_options[field_rotate]" value="0">
		<input type="checkbox" name="letstalk_options[field_rotate]" value="1" <?php checked('1', $options['field_rotate'], true); ?>>
		<?php
	}

	public function field_mode_cb()
	{
		$options = get_option(self::OPTIONS);
		?>
		<input type="radio" id="cssmode" name="letstalk_options[field_mode]" value="css" <?php checked('css', $options['field_mode'], true);?>>
		<label for="cssmode">CSS</label>
		<input type="radio" style="margin-left:15px;" id="jquerymode" name="letstalk_options[field_mode]" value="jquery" <?php checked('jquery', $options['field_mode'], true); ?>>
		<label for="jquerymode">jQuery</label>
		<?php
	}

	public function options_page()
	{
		add_menu_page('Lets Talk Button', 'Lets Talk', 'manage_options', 'lets-talk', [$this, 'options_page_html'], 'dashicons-format-chat', 99);
	}

	public function options_page_html()
	{
		if (! current_user_can('manage_options')) {
			return;
		}

		if (isset($_GET['settings-updated'])) {
			add_settings_error('messages', 'message', __('Settings Saved', 'lets-talk'), 'updated');
		}

		settings_errors('messages');
		?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields('lets-talk');
				do_settings_sections('lets-talk');
				submit_button(__('Save Settings', 'lets-talk'));
				?>
			</form>
		</div>
		<?php
	}

	public function body_code()
	{
		$shortcode      = get_option(self::OPTIONS)['field_shortcode'];
		$shortcode_sani = sanitize_text_field($shortcode);
		$mode           = get_option(self::OPTIONS)['field_mode'];

		if ('css' === $mode) {
			?>
			<a href="#letstalk-popup-container" class="link-button">
				<i class="letstalk-icons">
					<span class="dashicons dashicons-format-chat"></span>
				</i>
			</a>
			<div id="letstalk-popup-container" onkeydown="event.stopPropagation();">
				<div id="letstalk-popup">
					<a href="#" class="close-letstalk-popup">×</a>
					<div class="letstalk-content-wrapper">
						<div class="letstalk-popup-content">
							<?= do_shortcode($shortcode_sani); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		if ('jquery' === $mode) {

			?>
			<script>
			(function () {
				document.addEventListener('DOMContentLoaded', function () {
					const buttons = document.querySelectorAll('.letstalk-link-button');

					buttons.forEach(function (button) {
						button.addEventListener('click', function () {
							alert('Integration mode without jQuery is not implemented yet.');
						});
					});
				});
			})();
			</script>
			<a href="#" class="letstalk-link-button">
				<i class="letstalk-icons">
					<span class="dashicons dashicons-format-chat"></span>
				</i>
			</a>
			<div id="letstalk-popup-container">
				<div id="letstalk-popup">
					<a href="#" class="close-letstalk-popup">×</a>
					</a>
					<div class="letstalk-content-wrapper">
						<div class="letstalk-popup-content">
							<?= do_shortcode($shortcode_sani); ?></p>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
}

new LetsTalk();
