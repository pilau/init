<?php

/**
 * Pilau init
 *
 * For initialising a Pilau-flavoured WordPress installation
 *
 * @version		0.1
 */
global $pi_replace_values;


/*=======================================================================================
* Initialise
========================================================================================*/


// Basics
$pi_themes_dir = getcwd() . '/wp-content/themes';
$pi_replace_values = array();

// Check for stash file - indicates incomplete process
if ( file_exists( 'wp-content/.pi-stash' ) ) {
	$pi_stash = unserialize( file_get_contents( 'wp-content/.pi-stash' ) );
	$pi_replace_values = $pi_stash['replace_values'];
}

// Step - query param overrides stashed value
$pi_step = 1;
if ( isset( $_GET['pi-step'] ) ) {
	$pi_step = (int) $_GET['pi-step'];
} else if ( isset( $pi_stash['step'] ) ) {
	$pi_step = (int) $pi_stash['step'];
}

// Is WP present and installed?
$pi_wp_present = false;
$pi_wp_installed = false;
$pi_plugins_config = null;
$pi_plugin_infos = null;
if ( file_exists( 'wp-load.php' ) ) {
	$pi_wp_present = true;
	if ( $pi_step > 2 ) {
		$pi_wp_installed = true;
		require_once( 'wp-load.php' );
		$pi_plugins_config = get_option( 'pi_plugins_config' );
		$pi_plugin_infos = get_option( 'pi_plugin_infos' );
	}
}

// Get info on installed plugins?
$pi_installed_plugins = null;
$pi_activated_plugins = null;
if ( $pi_step > 3 ) {
	require_once( 'wp-admin/includes/plugin.php' ); // wp-load.php doesn't seem to include this
	$pi_installed_plugins_data = get_plugins();
	$pi_activated_plugins = wp_get_active_and_valid_plugins();
	// For clarity, reduce plugin refs to directory slugs
	$pi_installed_plugins = array();
	foreach ( array_keys( $pi_installed_plugins_data ) as $pi_installed_plugin ) {
		$pi_installed_plugin_parts = explode( '/', trim( $pi_installed_plugin, '/' ) );
		$pi_installed_plugins[] = $pi_installed_plugin_parts[0];
	}
	foreach ( $pi_activated_plugins as &$pi_activated_plugin ) {
		$pi_activated_plugin_parts = explode( '/', trim( $pi_activated_plugin, '/' ) );
		$pi_activated_plugin = $pi_activated_plugin_parts[ count( $pi_activated_plugin_parts ) - 2 ];
	}
}

/**
 * Default plugin infos for TMG
 * @link	http://tgmpluginactivation.com/
 *
 * @since	0.1
 * @var		array
 */
$pi_plugin_infos_defaults = array(
	array(
		'name'              => 'Gravity Forms',
		'slug'              => 'gravity-forms',
		'source'            => 'https://github.com/gravityforms/gravityforms/archive/develop.zip',
		'required'          => false,
		'is_automatic'		=> true,
		'force_activation'	=> false,
		'external_url'      => 'http://www.gravityforms.com/',
	),
	array(
		'name'				=> 'GitHub Updater',
		'slug'				=> 'github-updater',
		'source'            => 'https://github.com/afragen/github-updater/archive/develop.zip',
		'required'			=> false,
		'force_activation'	=> false,
		'is_automatic'		=> false,
		'external_url'      => 'https://github.com/afragen/github-updater',
	),
	array(
		'name'				=> 'Developer\'s Custom Fields',
		'slug'				=> 'developers-custom-fields',
		'required'			=> true,
		'force_activation'	=> true,
		'is_automatic'		=> true,
	),
	array(
		'name'				=> 'Lock Pages',
		'slug'				=> 'lock-pages',
		'required'			=> true,
		'force_activation'	=> true,
		'is_automatic'		=> true,
	),
	array(
		'name'				=> 'Better WordPress Minify',
		'slug'				=> 'bwp-minify',
		'required'			=> true,
		'force_activation'	=> false,
		'is_automatic'		=> false,
	),
	array(
		'name'				=> 'WP Super Cache',
		'slug'				=> 'wp-super-cache',
		'required'			=> true,
		'force_activation'	=> false,
		'is_automatic'		=> false,
	),
	array(
		'name'				=> 'Members',
		'slug'				=> 'members',
		'required'			=> true,
		'force_activation'	=> true,
		'is_automatic'		=> true,
	),
	array(
		'name'				=> 'Simple Page Ordering',
		'slug'				=> 'simple-page-ordering',
		'required'			=> true,
		'force_activation'	=> true,
		'is_automatic'		=> true,
	),
	array(
		'name'				=> 'Use Google Libraries',
		'slug'				=> 'use-google-libraries',
		'required'			=> false,
		'force_activation'	=> false,
		'is_automatic'		=> false,
	),
	array(
		'name'				=> 'WordPress SEO',
		'slug'				=> 'wordpress-seo',
		'required'			=> true,
		'force_activation'	=> true,
		'is_automatic'		=> true,
	),
	array(
		'name'				=> 'BackUpWordPress',
		'slug'				=> 'backupwordpress',
		'required'			=> true,
		'force_activation'	=> false,
		'is_automatic'		=> false,
	),
	array(
		'name'				=> 'Simple Events',
		'slug'				=> 'simple-events',
		'required'			=> false,
		'force_activation'	=> false,
		'is_automatic'		=> true,
	),
	array(
		'name'				=> 'Dynamic Widgets',
		'slug'				=> 'dynamic-widgets',
		'required'			=> false,
		'force_activation'	=> false,
		'is_automatic'		=> true,
	),
	array(
		'name'				=> 'Google Analytics for WordPress',
		'slug'				=> 'google-analytics-for-wordpress',
		'required'			=> false,
		'force_activation'	=> false,
		'is_automatic'		=> false,
	),
	array(
		'name'				=> 'Pilau Google Analytics Measurement Protocol',
		'slug'				=> 'ga-measurement-protocol',
		'source'			=> 'https://github.com/pilau/ga-measurement-protocol/archive/master.zip',
		'required'			=> false,
		'force_activation'	=> false,
		'external_url'		=> 'https://github.com/pilau/ga-measurement-protocol',
		'is_automatic'		=> false,
	),
	array(
		'name'				=> 'Pilau Slideshow',
		'slug'				=> 'slideshow',
		'source'			=> 'https://github.com/pilau/slideshow/archive/master.zip',
		'required'			=> false,
		'force_activation'	=> false,
		'external_url'		=> 'https://github.com/pilau/slideshow',
		'is_automatic'		=> true,
	),
	array(
		'name'				=> 'User Photo',
		'slug'				=> 'user-photo',
		'required'			=> false,
		'force_activation'	=> false,
		'is_automatic'		=> true,
	),
	array(
		'name'				=> 'Codepress Admin Columns',
		'slug'				=> 'codepress-admin-columns',
		'required'			=> true,
		'force_activation'	=> true,
		'is_automatic'		=> true,
	),
	array(
		'name'				=> 'InfiniteWP Client',
		'slug'				=> 'iwp-client',
		'required'			=> true,
		'force_activation'	=> false,
		'is_automatic'		=> false,
	),
	array(
		'name'				=> 'MailChimp for WordPress',
		'slug'				=> 'mailchimp-for-wp',
		'required'			=> false,
		'force_activation'	=> false,
		'is_automatic'		=> true,
	),
	array(
		'name'				=> 'oAuth Twitter Feed for Developers',
		'slug'				=> 'oauth-twitter-feed-for-developers',
		'required'			=> false,
		'force_activation'	=> false,
		'is_automatic'		=> true,
	),
	array(
		'name'				=> 'Wordfence',
		'slug'				=> 'wordfence',
		'required'			=> true,
		'force_activation'	=> false,
		'is_automatic'		=> false,
	),
	array(
		'name'				=> 'User Switching',
		'slug'				=> 'user-switching',
		'required'			=> true,
		'force_activation'	=> true,
		'is_automatic'		=> true,
	),
	array(
		'name'				=> 'Advanced Custom Fields',
		'slug'				=> 'advanced-custom-fields',
		'required'			=> false,
		'force_activation'	=> false,
		'is_automatic'		=> true,
	),
);
// Sort it out!
function pi_compare_status( $a, $b ) {
	return strnatcmp( $a['name'], $b['name'] );
}
usort( $pi_plugin_infos_defaults, 'pi_compare_status' );


/*=======================================================================================
 * Processing
 ========================================================================================*/


// Action?
if ( isset( $_POST['action'] ) ) {

	// Append posted values
	$pi_replace_values = array_merge( $pi_replace_values, $_POST );

	// Which step?
	switch ( $pi_step ) {

		// Installing Pilau
		case 1: {

			// Download and install Pilau Base theme
			$pi_pb_theme_zip = $pi_themes_dir . 'pilau-base.zip';
			pi_download_file( 'https://github.com/pilau/base/archive/master.zip', $pi_pb_theme_zip );
			pi_unzip_archive( $pi_pb_theme_zip, $pi_themes_dir );
			rename( $pi_themes_dir . '/base-master', $pi_themes_dir . '/pilau-base' );

			// Download and install Pilau Starter theme
			$pi_ps_theme_zip = $pi_themes_dir . '/pilau-starter.zip';
			pi_download_file( 'https://github.com/pilau/starter/archive/master.zip', $pi_ps_theme_zip );
			pi_unzip_archive( $pi_ps_theme_zip, $pi_themes_dir, true );
			// Move theme
			rename( $pi_themes_dir . '/starter-master/wp-content/themes/pilau-starter', $pi_themes_dir . '/pilau-starter' );
			// Remove root files not needed
			unlink( $pi_themes_dir . '/starter-master/README.md' );
			// Move the rest
			pi_move_files( $pi_themes_dir . '/starter-master', getcwd() );
			// Delete the dirs
			rmdir( $pi_themes_dir . '/starter-master/wp-content/themes' );
			rmdir( $pi_themes_dir . '/starter-master/wp-content' );
			rmdir( $pi_themes_dir . '/starter-master' );

			// Get wp-config-local.php gist
			$pi_local_config = file_get_contents( 'https://gist.githubusercontent.com/gyrus/3131308/raw/local-config.php' );
			file_put_contents( getcwd() . '/wp-config-local.php', $pi_local_config );

			// Remove trailing slashes
			foreach ( $pi_replace_values as $pi_replace_key => $pi_replace_value ) {
				if ( in_array( $pi_replace_key, array( 'local-domain', 'staging-domain', 'staging-path', 'production-domain', 'production-path' ) ) ) {
					$pi_replace_values[ $pi_replace_key ] = rtrim( $pi_replace_value, '/' );
				}
			}

			// Auto-generate values
			pi_auto_generate_values();

			// Sort out www redirection values
			if ( $pi_replace_values['production-domain'] && isset( $_REQUEST['htaccess-force-www'] ) ) {
				$pi_replace_values['production-domain-to-be-redirected'] = substr( $pi_replace_values['production-domain'], 0, 4 ) == 'www.' ? substr( $pi_replace_values['production-domain'], 4 ) : 'www.' . $pi_replace_values['production-domain'];
			}

			// Apache password for staging, encrypted
			if ( $pi_replace_values['staging-apache-password'] ) {
				$pi_replace_values['staging-apache-password-encrypted'] = crypt( $pi_replace_values['staging-apache-password'], base64_encode( $pi_replace_values['staging-apache-password'] ) );
			}

			// PHPDoc package name
			$pi_replace_values['theme-phpdoc-name'] = preg_replace( '/[^A-Za-z_]/', '', str_replace( ' ', '_', ucwords( strtolower( $pi_replace_values['site-title'] ) ) ) );

			// Theme slug
			$pi_replace_values['theme-slug'] = strtolower( preg_replace( '/[^A-Za-z\-]/', '', str_replace( ' ', '-', $pi_replace_values['site-title'] ) ) );
			$pi_starter_theme_dir = $pi_themes_dir . '/' . $pi_replace_values['theme-slug'];
			rename( $pi_themes_dir . '/pilau-starter', $pi_starter_theme_dir );

			// Add in escaped values where necessary
			foreach ( array( 'holding-page-ip', 'production-domain-to-be-redirected' ) as $pi_replace_value ) {
				$pi_replace_values[ $pi_replace_value . '-escaped' ] = str_replace( array( '.', '-' ), array( '\.', '\-' ), $pi_replace_values[ $pi_replace_value ] );
			}

			// Any other special values?
			// Ones prefixed with // are for PHP blocks
			$pi_replace_values['//config-keys-salts'] = file_get_contents( 'https://api.wordpress.org/secret-key/1.1/salt/' );

			// Remove empty values
			foreach ( $pi_replace_values as $pi_replace_key => $pi_replace_value ) {
				if ( empty( $pi_replace_value ) ) {
					unset( $pi_replace_values[ $pi_replace_key ] );
				}
			}

			//echo '<pre>'; print_r( $pi_replace_values ); echo '</pre>'; exit;

			// Do replacements in relevant root files
			foreach ( array( '.htaccess', '.htpasswd', '503.php', 'robots.txt', 'wp-config.php', 'wp-config-local.php' ) as $root_file ) {
				pi_replace_in_file( $root_file );
			}

			// Replace the theme name in .gitignore
			$pi_gitignore = file_get_contents( '.gitignore' );
			$pi_gitignore = str_replace( 'pilau-starter', $pi_replace_values['theme-slug'], $pi_gitignore );
			file_put_contents( '.gitignore', $pi_gitignore );

			// Go through theme files
			pi_recursive_replace_in_dir( $pi_starter_theme_dir );

			// .htaccess
			$pi_contents = file_get_contents( getcwd() . '/.htaccess' );
			if ( ! empty( $pi_replace_values['staging-domain'] ) && ! empty( $pi_replace_values['staging-path'] ) ) {
				$pi_contents = pi_uncomment_htaccess( $pi_contents, 'staging-password' );
			}
			if ( ! empty( $pi_replace_values['holding-page-ip-escaped'] ) ) {
				$pi_contents = pi_uncomment_htaccess( $pi_contents, 'holding-page' );
			}
			if ( ! empty( $pi_replace_values['production-domain-to-be-redirected-escaped'] ) && ! empty ( $pi_replace_values['production-domain'] ) ) {
				$pi_contents = pi_uncomment_htaccess( $pi_contents, 'force-www' );
			}
			file_put_contents( getcwd() . '/.htaccess', $pi_contents );

			// Replace constants in functions.php
			$pi_contents = file_get_contents( $pi_starter_theme_dir . '/functions.php' );
			foreach ( array( 'use-comments', 'use-categories', 'use-tags', 'ignore-updates-for-inactive-plugins', 'use-cookie-notice', 'use-picturefill', 'rename-posts-news' ) as $pi_constant ) {
				$pi_constant_parts = explode( '-', $pi_constant );
				$pi_constant_name = 'PILAU_' . strtoupper( implode( '_', $pi_constant_parts ) );
				$pi_contents = preg_replace( "/define\( " . $pi_constant_name . ", [a-z]+ \);/", "define\( " . $pi_constant_name . ", " . isset( $pi_replace_values[ 'theme-' . $pi_constant ] ) ? 'true' : 'false' . " );", $pi_contents );
			}
			foreach ( array( 'twitter-screen-name' ) as $pi_constant ) {
				$pi_constant_parts = explode( '-', $pi_constant );
				$pi_constant_name = 'PILAU_' . strtoupper( implode( '_', $pi_constant_parts ) );
				$pi_contents = preg_replace( "/define\( " . $pi_constant_name . ", '[^']*' \);/", "define\( " . $pi_constant_name . ", '" . $pi_replace_values[ 'theme-' . $pi_constant ] . "' );", $pi_contents );
			}
			file_put_contents( $pi_starter_theme_dir . '/functions.php', $pi_contents );

			// Stash
			$pi_stash = array(
				'step'				=> 2,
				'replace_values'	=> $pi_replace_values
			);
			file_put_contents( 'wp-content/.pi-stash', serialize( $pi_stash ) );

			/*
			 * BY THIS POINT:
			 * - The pilau-base parent theme will be installed
			 * - The pilau-start child theme will be installed, and renamed
			 * - wp-config-local.php will be installed and configured
			 * - Placeholders will have been replaced in pilau-starter root and theme files
			 * - .htaccess will be configured
			 * - The child theme's functions.php will be configured
			 */

			// Next step
			header( 'Location: pilau-init.php?pi-step=2' );
			exit;

			break;
		}

		// WP installation
		case 2: {

			// Auto-generate values
			pi_auto_generate_values();

			// Mimic WP install
			define( 'WP_INSTALLING', true );
			require_once( 'wp-load.php' );
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
			require_once( ABSPATH . WPINC . '/wp-db.php' );
			$pi_install = wp_install(
				$pi_replace_values['site-title'],
				$pi_replace_values['wp-username'],
				$pi_replace_values['wp-email'],
				1,
				'',
				wp_slash( $pi_replace_values['wp-password'] ),
				$pi_replace_values['wp-language']
			);
			$pi_replace_values = array_merge( $pi_replace_values, $pi_install );

			// Basic settings
			update_option( 'blogdescription', $pi_replace_values['wp-blogdescription'] );
			update_option( 'timezone_string', $pi_replace_values['wp-timezone_string'] );
			update_option( 'date_format', $pi_replace_values['wp-date_format'] );
			update_option( 'time_format', $pi_replace_values['wp-time_format'] );
			update_option( 'show_on_front', $pi_replace_values['wp-show_on_front'] );
			update_option( 'default_pingback_flag', ( isset( $pi_replace_values['wp-default_pingback_flag'] ) ? '1' : '' ) );
			update_option( 'default_ping_status', ( isset( $pi_replace_values['wp-default_ping_status'] ) ? 'open' : 'closed' ) );
			update_option( 'default_comment_status', ( isset( $pi_replace_values['wp-default_comment_status'] ) ? 'open' : 'closed' ) );
			update_option( 'uploads_use_yearmonth_folders', ( isset( $pi_replace_values['wp-uploads_use_yearmonth_folders'] ) ? '1' : '' ) );
			update_option( 'permalink_structure', $pi_replace_values['wp-permalink_structure'] );

			// Update the name for the admin user
			$pi_name_parts = explode( ' ', $pi_replace_values['theme-author'] );
			wp_update_user( array(
				'ID'			=> 1,
				'display_name'	=> $pi_replace_values['theme-author'],
				'first_name'	=> $pi_name_parts[0],
				'last_name'		=> ( count( $pi_name_parts ) > 1 ) ? $pi_name_parts[1] : '',
			));

			// Home / blog pages
			$pi_blog_page_title = $pi_replace_values['theme-rename-posts-news'] ? 'News' : 'Blog';
			if ( $pi_replace_values['wp-show_on_front'] == 'page' ) {

				// Default page is home
				wp_update_post( array(
					'ID'				=> 2,
					'post_content'		=> '',
					'post_title'		=> 'Home',
					'post_name'			=> 'home',
					'comment_status'	=> 'closed',
					'ping_status'		=> 'closed',
				));

				// Create blog page
				$pi_new_post_id = wp_insert_post( array(
					'post_title'	=> $pi_blog_page_title,
					'post_name'		=> strtolower( $pi_blog_page_title ),
					'post_status'	=> 'publish',
					'post_author'	=> 1,
					'post_type'		=> 'page'
				));
				update_option( 'page_for_posts', $pi_new_post_id );

			} else {

				// Default page is blog
				wp_update_post( array(
					'ID'				=> 2,
					'post_content'		=> '',
					'post_title'		=> $pi_blog_page_title,
					'post_name'			=> strtolower( $pi_blog_page_title ),
					'comment_status'	=> 'closed',
					'ping_status'		=> 'closed',
				));
				update_option( 'page_on_front', 0 );
				update_option( 'page_for_posts', 0 );

			}

			// Activate starter theme
			switch_theme( $pi_replace_values['theme-slug'] );

			// Stash
			$pi_stash = array(
				'step'				=> 3,
				'replace_values'	=> $pi_replace_values
			);
			file_put_contents( 'wp-content/.pi-stash', serialize( $pi_stash ) );

			/*
			 * BY THIS POINT:
			 * - The WP database should be installed
			 * - Basic WP settings should be set
			 * - The home / blog pages should be ready
			 * - The starter theme is activated
			 */

			// Next step
			header( 'Location: pilau-init.php?pi-step=3' );
			exit;

			break;
		}

		// Plugin configuration
		/*
		 * Plugins are 'configured' before installation, because we set up a hook to trigger when
		 * plugins are activated first, and to apply configuration then.
		 *
		 * In order to process config options that are set here, you need to extend the code in
		 * Pilau Starter: inc/config.php
		 *
		 */
		case 3: {

			// Store config options in database ready for hook in child theme
			$pi_plugins_config = $_POST;
			unset( $pi_plugins_config[ 'action' ] );
			update_option( 'pi_plugins_config', $pi_plugins_config );

			/*
			 * BY THIS POINT:
			 * - Plugin configuration is set and ready
			 */

			// Next step
			header( 'Location: pilau-init.php?pi-step=4' );
			exit;

			break;
		}

		// Plugin installation
		case 4: {
			//echo '<pre>'; print_r( $_POST ); echo '</pre>'; exit;

			// Gather plugin infos
			$pi_plugin_infos = array();
			foreach ( $pi_plugin_infos_defaults as $pi_plugin_infos_default ) {

				// Add to TGM list if set to install
				if ( in_array( $pi_plugin_infos_default['slug'], array_keys( $_POST['install'] ) ) ) {

					// Update defaults according to input
					$pi_plugin_infos_default['force_activation'] = $pi_plugin_infos_default['required'] = in_array( $pi_plugin_infos_default['slug'], array_keys( $_POST['required'] ) );
					$pi_plugin_infos_default['is_automatic'] = in_array( $pi_plugin_infos_default['slug'], array_keys( $_POST['activate'] ) );

					// Pass through
					$pi_plugin_infos[] = $pi_plugin_infos_default;

				}

			}

			// Store in database
			update_option( 'pi_plugin_infos', $pi_plugin_infos );

			/*
			 * BY THIS POINT:
			 * - The pi_plugin_infos database option will have been created, containing the details for the TMG plugin script
			 */

			// Next step
			header( 'Location: pilau-init.php?pi-step=5' );
			exit;

			break;
		}

		// Nothing to do for 5...

		// Further theme initialisation
		case 6: {

			// Initialise pages to be locked with Home and maybe News
			$pi_lock_pages = array( 2 );
			if ( $pi_pages_for_posts = get_option( 'page_for_posts' ) ) {
				$pi_lock_pages[] = $pi_pages_for_posts;
			}

			// Go through all submitted values
			foreach ( $_POST as $pi_key => $pi_value ) {
				$pi_key_parts = explode( '-', $pi_key );

				if ( $pi_key_parts[0] == 'page' ) {

					// Other pages
					if ( $pi_key_parts[1] == 'others' ) {

						// Go through list
						foreach ( explode( ',', $pi_value ) as $pi_other_page_title ) {

							// Hacky way of using WPSEO stopwords removal
							$pi_post_name = trim( $pi_other_page_title );
							if ( class_exists( 'WPSEO_Admin' ) ) {
								$_POST['post_title'] = trim( $pi_other_page_title );
								$pi_wpseo_admin = new WPSEO_Admin;
								$pi_post_name = $pi_wpseo_admin->remove_stopwords_from_slug( null );
							}

							$pi_new_post_id = wp_insert_post( array(
								'post_title'		=> trim( $pi_other_page_title ),
								'post_name'			=> $pi_post_name,
								'post_status'		=> 'publish',
								'post_type'			=> 'page',
							));
							$pi_lock_pages[] = $pi_new_post_id;
						}

					} else {

						// Standard pages
						$pi_new_post_id = wp_insert_post( array(
							'post_title'	=> ucfirst( $pi_key_parts[1] ),
							'post_name'		=> $pi_key_parts[1],
							'post_status'	=> 'publish',
							'post_type'		=> 'page',
						));
						$pi_lock_pages[] = $pi_new_post_id;

					}

				}

			}

			// Pages to be locked?
			if ( $pi_lock_pages ) {
				$pi_current_locked_pages = get_option( 'SLT_LockPages_options' );
				$pi_current_locked_pages['slt_lockpages_locked_pages'] = $pi_current_locked_pages['slt_lockpages_locked_pages'] . ',' . implode( ',', $pi_lock_pages );
				update_option( 'SLT_LockPages_options', $pi_current_locked_pages );
			}

			/*
			 * BY THIS POINT:
			 * - All done!
			 */

			// Clean up
			unlink( 'wp-content/.pi-stash' );

			// Final screen
			header( 'Location: pilau-init.php?pi-step=100' );
			exit;

			break;
		}

	}

}


/*=======================================================================================
 * Output
 ========================================================================================*/


?><!doctype html>

<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Pilau init</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700' rel='stylesheet' type='text/css'>
	<style>
		* {
			box-sizing: border-box;
		}
		html {
			text-align: center;
			font: 1em/1.5 'Open Sans', sans-serif;
			background: #f1f1f1;
			color: #444;
		}
		body {
			padding: 2em;
			margin: 0 auto;
			max-width: 50em;
			text-align: left;
		}
		a {
			color: #0074a2;
			text-decoration: none;
		}
		a:hover, a:focus {
			color: #2ea2cc;
		}
		form ol {
			list-style: none;
			margin: 0;
			padding: 0;
		}
		label, p.label {
			display: block;
			margin-bottom: .4rem;
		}
		h1 {
		}
		h2 {
			margin: 3em 0 2em;
			padding-bottom: .3em;
			border-bottom: 1px solid #ccc;
		}
		table {
			width: 100%;
			border-collapse: collapse;
		}
		td, th {
			padding: .6rem 1rem;
			font-size: .8rem;
			line-height: 1.3;
		}
		th {
			background-color: #666;
			color: #fff;
		}
		th dfn {
			font-style: normal;
			border-bottom: 1px dotted #fff;
			cursor: help;
		}
		tr.alt td {
			background-color: #ddd;
		}
		td.checkbox {
			text-align: center;
		}
		.main-title {
			padding: .5em 1em;
			margin-bottom: 2em;
			background-color: #666;
			color: #fff;
			font-size: .9rem;
			font-weight: bold;
		}
		.input {
			width: 100%;
			padding: .4em .6em;
			border: 1px solid #ddd;
			box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
			background-color: #fff;
			color: #333;
			outline: 0;
			transition: .05s border-color ease-in-out;
			font-size: 1.2rem;
		}
		.field {
			position: relative;
			margin: 0 0 1.5rem;
		}
		.field.required:after {
			position: absolute;
			right: -1.2em;
			top: 1.6em;
			display: block;
			content: '*';
			color: #c30;
		}
		.field.radio label {
			display: inline-block;
			margin-right: 1rem;
			font-size: .8rem;
		}
		.note {
			font-size: .8rem;
			font-style: italic;
			color: #999;
		}
		div.checkbox {
			margin: .4rem 0;
			font-size: .8rem;
		}
		.checkbox label {
			display: inline;
		}
		.buttons {
			margin: 5rem 0;
		}
		.button {
			display: inline-block;
			padding: .4rem 1rem;
			background: #2ea2cc;
			box-shadow: inset 0 1px 0 rgba(120,200,230,.5),0 1px 0 rgba(0,0,0,.15);
			color: #fff;
			cursor: pointer;
			border: 1px solid #0074a2;
			border-radius: 3px;
			white-space: nowrap;
			text-decoration: none;
			font-size: .9rem;
		}
		.button:hover, .button:focus {
			background: #1e8cbe;
			color: #fff;
			border-color: #0074a2;
		}
	</style>
</head>

<body>


<p class="main-title">
	Pilau init
	<?php if ( $pi_step > 1 && ! isset( $_GET['pi-step'] ) ) { ?>
		(continued from interrupted session...)
	<?php } ?>
</p>


<?php /* Do basic file checks */ ?>
<?php if ( ! $pi_wp_present || ! is_writable( 'wp-config-sample.php' ) || ! is_writable( 'wp-content' ) ) { ?>


	<p class="alert">OK! First thing, make sure a fresh copy of <a href="https://wordpress.org/download/">the latest WordPress</a> is present in this directory, and that you've got an empty MySQL database ready.</p>

	<?php if ( ! is_writable( 'wp-config-sample.php' ) || ! is_writable( 'wp-content' ) ) { ?>
		<p>Also, the file <code>wp-config-sample.php</code> and the directory <code>wp-content</code> need to be writeable by the web server's user.</p>
	<?php } ?>

	<p>Refresh this page when ready...</p>


<?php } else { ?>


	<?php if ( $pi_step > 2 && $pi_replace_values['wp-username'] ) { ?>

		<p>You can now <a href="wp-login.php" target="_blank">log into WordPress</a>:</p>
		<ul>
			<li><b>Username:</b> <?php echo $pi_replace_values['wp-username']; ?></li>
			<li><b>Password:</b> <?php echo $pi_replace_values['wp-password']; ?></li>
		</ul>

	<?php } ?>


	<?php if ( $pi_step == 1 ) { ?>


		<h1>1. Installing Pilau</h1>

		<form action="?pi-step=1" method="post">

			<ol>
				<?php
				pi_form_field( 'site-title', 'Site title', 'text', true, '', '', false, null, 'This will be used for the child theme name and slug, and the PHPDoc package name.' );
				pi_form_field( 'theme-author', 'Theme author', 'text', true );
				//pi_form_field( 'pilau-versions', 'Use latest stable releases of Pilau Base and Starter (leave unchecked to use latest master)', 'checkbox', false, '', false );
				pi_form_field( 'db-prefix', 'Database table prefix', 'text', true, '', 'wp_', true, true );
				?>
			</ol>

			<h2>Local dev environment</h2>
			<ol>
				<?php
				pi_form_field( 'local-db-name', 'Local database name', 'text', true );
				pi_form_field( 'local-db-user', 'Local database user', 'text', true, '', 'root' );
				pi_form_field( 'local-db-password', 'Local database password', 'text', true );
				pi_form_field( 'local-domain', 'Local domain', 'text', true, '', $_SERVER['SERVER_NAME'] );
				?>
			</ol>

			<h2>Staging environment</h2>
			<ol>
				<?php
				pi_form_field( 'staging-db-name', 'Staging database name', 'text' );
				pi_form_field( 'staging-db-user', 'Staging database user', 'text' );
				pi_form_field( 'staging-db-password', 'Staging database password', 'text', false, '', '', true, true );
				pi_form_field( 'staging-domain', 'Staging domain', 'text' );
				pi_form_field( 'staging-path', 'Staging path to web root', 'text' );
				pi_form_field( 'staging-apache-user', 'Staging Apache user', 'text', false, '', 'pass' );
				pi_form_field( 'staging-apache-password', 'Staging Apache password', 'text', false, '', 'word' );
				pi_form_field( 'staging-ftp-host', 'Staging FTP host', 'text' );
				pi_form_field( 'staging-ftp-user', 'Staging FTP user', 'text' );
				pi_form_field( 'staging-ftp-password', 'Staging FTP password', 'text' );
				?>
			</ol>

			<h2>Production environment</h2>
			<ol>
				<?php
				pi_form_field( 'production-db-name', 'Production database name', 'text' );
				pi_form_field( 'production-db-user', 'Production database user', 'text' );
				pi_form_field( 'production-db-password', 'Production database password', 'text', false, '', '', true, true );
				pi_form_field( 'production-domain', 'Production domain', 'text', true, '', '' );
				pi_form_field( 'htaccess-force-www', 'Force \'www\' / \'no-www\' according to above domain', 'checkbox', false, '', true );
				pi_form_field( 'production-path', 'Production path to web root', 'text' );
				pi_form_field( 'production-ftp-host', 'Production FTP host', 'text' );
				pi_form_field( 'production-ftp-user', 'Production FTP user', 'text' );
				pi_form_field( 'production-ftp-password', 'Production FTP password', 'text' );
				pi_form_field( 'holding-page-ip', 'IP to be allowed to see the holding page', 'text', false, 'xxx.xxx.xxx.xxx' );
				?>
			</ol>

			<h2>Some basic theme settings</h2>
			<ol>
				<?php
				pi_form_field( 'theme-use-comments', 'Use comments?', 'checkbox', false, '', false );
				pi_form_field( 'theme-use-categories', 'Use categories?', 'checkbox', false, '', false );
				pi_form_field( 'theme-use-tags', 'Use tags?', 'checkbox', false, '', true );
				pi_form_field( 'theme-ignore-updates-for-inactive-plugins', 'Ignore updates for inactive plugins?', 'checkbox', false, '', true );
				pi_form_field( 'theme-use-cookie-notice', 'Use cookie notice?', 'checkbox', false, '', true );
				pi_form_field( 'theme-use-picturefill', 'Use Picturefill?', 'checkbox', false, '', false );
				pi_form_field( 'theme-twitter-screen-name', 'Twitter screen name', 'text', false );
				pi_form_field( 'theme-rename-posts-news', 'Rename Posts to News?', 'checkbox', false, '', true );
				?>
			</ol>

			<div class="buttons">
				<input type="submit" value="Submit" class="button">
				<input type="hidden" name="action" value="1">
			</div>

		</form>


	<?php } else if ( $pi_step == 2 ) { ?>


		<h1>2. Installing WordPress</h1>

		<form action="?pi-step=2" method="post">

			<h2>Basic settings</h2>
			<ol>
				<?php
				pi_form_field( 'wp-language', 'Language', 'text', true, 'e.g. en_GB', 'en_GB' );
				pi_form_field( 'wp-username', 'Username', 'text', true, 'e.g. freddy (don\'t use admin!)' );
				pi_form_field( 'wp-password', 'Password', 'text', false, '', '', true, true, 'Make sure it\'s strong - use <a target="_blank" href="https://www.cygnius.net/snippets/passtest.html">this tool</a> to test' );
				pi_form_field( 'wp-email', 'Email address', 'text', true );
				pi_form_field( 'wp-blogdescription', 'Site tagline', 'text', false, '', '' );
				pi_form_field( 'wp-timezone_string', 'Timezone', 'text', true, 'e.g. Europe/London', 'Europe/London' );
				pi_form_field( 'wp-date_format', 'Date format', 'text', true, 'e.g. j F Y', 'j F Y' );
				pi_form_field( 'wp-time_format', 'Time format', 'text', true, 'e.g. H:i', 'H:i' );
				pi_form_field( 'wp-show_on_front', 'Front page', 'radio', true, '', 'page', false, false, 'If static page, the default standard page will be used as Home, and a page titled \'' . ( isset( $pi_replace_values['theme-rename-posts-news'] ) ? 'News' : 'Blog' ) . '\' will be created for posts', array( 'posts' => 'Latest posts', 'page' => 'Static page' ) );
				pi_form_field( 'wp-default_pingback_flag', 'Attempt to notify any blogs linked to from the article', 'checkbox', false, '', false );
				pi_form_field( 'wp-default_ping_status', 'Allow link notifications from other blogs (pingbacks and trackbacks)', 'checkbox', false, '', false );
				pi_form_field( 'wp-default_comment_status', 'Allow people to post comments on new articles', 'checkbox', false, '', false );
				pi_form_field( 'wp-uploads_use_yearmonth_folders', 'Organise my uploads into month- and year-based folders', 'checkbox', false, '', false );
				pi_form_field( 'wp-permalink_structure', 'Permalink structure', 'text', true, 'e.g. /%year%/%postname%/', '/post/%year%/%postname%/' );
				?>
			</ol>

			<div class="buttons">
				<input type="submit" value="Submit" class="button">
				<input type="hidden" name="action" value="1">
			</div>

		</form>


	<?php } else if ( $pi_step == 3 ) { ?>


		<h1>3. Configuring plugins</h1>

		<p>We set configuration options for plugins first because some plugins might not be activated straight away. So there's a hook in the child theme to check when plugins are activated for the first time. Then, if there's configuration options stored, they're applied.</p>

		<p>Just ignore any options for plugins you won't be using.</p>

		<form action="?pi-step=3" method="post">

			<h3>Members</h3>
			<ol>
				<?php
				pi_form_field( 'members-super-editor', 'Create Super Editor role?', 'checkbox', false, '', true );
				?>
			</ol>

			<div class="buttons">
				<input type="submit" value="Submit" class="button">
				<input type="hidden" name="action" value="1">
			</div>

		</form>


	<?php } else if ( $pi_step == 4 ) { ?>


		<h2>4. Installing plugins - preferences</h2>

		<p>The preferences set here will be stored in the database ready for the <a href="http://tgmpluginactivation.com/">TMG Plugin Activation</a> class included in the child theme to read.</p>

		<form action="?pi-step=4" method="post">

			<table>
				<thead>
				<tr>
					<th scope="col">Plugin</th>
					<th scope="col"><dfn title="Check to install">Install?</dfn></th>
					<th scope="col"><dfn title="Check to activate on installation">Activate?</dfn></th>
					<th scope="col"><dfn title="Check to force activation">Required?</dfn></th>
				</tr>
				</thead>
				<tbody>
				<?php $alt = 0; ?>
				<?php foreach ( $pi_plugin_infos_defaults as $pi_plugin_infos_default ) { ?>
					<tr class="<?php echo $alt ? 'alt' : ''; ?>">
						<td><b><label for="install-<?php echo $pi_plugin_infos_default['slug']; ?>"><?php echo $pi_plugin_infos_default['name']; ?></label></b></td>
						<td class="checkbox"><input type="checkbox" name="install[<?php echo $pi_plugin_infos_default['slug']; ?>]" id="install-<?php echo $pi_plugin_infos_default['slug']; ?>"<?php if ( ! empty( $pi_plugin_infos_default['required'] ) ) echo ' checked'; ?>></td>
						<td class="checkbox"><input type="checkbox" name="activate[<?php echo $pi_plugin_infos_default['slug']; ?>]"<?php if ( ! empty( $pi_plugin_infos_default['is_automatic'] ) ) echo ' checked'; ?>></td>
						<td class="checkbox"><input type="checkbox" name="required[<?php echo $pi_plugin_infos_default['slug']; ?>]"<?php if ( ! empty( $pi_plugin_infos_default['force_activation'] ) ) echo ' checked'; ?>></td>
					</tr>
					<?php $alt = 1 - $alt; ?>
				<?php } ?>
				</tbody>
			</table>

			<div class="buttons">
				<input type="submit" value="Submit" class="button">
				<input type="hidden" name="action" value="1">
			</div>

		</form>


	<?php } else if ( $pi_step == 5 ) { ?>


		<h2>5. Installing plugins - installation</h2>

		<ol>
			<li>Now, go to <a href="wp-admin/themes.php?page=tgmpa-install-plugins" target="_blank">the TMG Plugin Activation page</a>.</li>
			<li>Install all the plugins listed there.</li>
			<li>You'll get a notice asking you to activate any plugins you installed but didn't select <em>Activate</em> for on the previous screen. Dismiss that if you want.</li>
		</ol>

		<p>Now we can proceed...</p>

		<p><a href="?pi-step=6" class="button">Proceed</a></p>


	<?php } else if ( $pi_step == 6 ) { ?>


		<h2>6. Further theme initialisation</h2>

		<form action="?pi-step=6" method="post">

			<h3>Standard pages</h3>

			<?php if ( in_array( 'lock-pages', $pi_activated_plugins ) ) { ?>
				<p>These pages will be locked using the Lock Pages plugin.</p>
			<?php } ?>

			<ol>
				<?php
				pi_form_field( 'page-events', 'Events', 'checkbox', false, '', in_array( 'simple-events', $pi_installed_plugins ) );
				pi_form_field( 'page-about', 'About', 'checkbox', false, '', true );
				pi_form_field( 'page-contact', 'Contact', 'checkbox', false, '', true );
				pi_form_field( 'page-privacy', 'Privacy', 'checkbox', false, '', false );
				pi_form_field( 'page-others', 'Other top-level pages', 'text', false, 'Enter a comma-separated list' );
				?>
			</ol>

			<div class="buttons">
				<input type="submit" value="Submit" class="button">
				<input type="hidden" name="action" value="1">
			</div>

		</form>


	<?php } else if ( $pi_step == 100 ) { ?>


		<h2>Finished!</h2>

		<p>If you've lost your password, do the lost password thing.</p>


	<?php } ?>

<?php } ?>


</body>
</html><?php


/*=======================================================================================
 * Helper functions
 ========================================================================================*/



/**
 * Output a form field
 *
 * @since	0.1
 * @param	string		$name
 * @param	string		$label
 * @param	string		$type
 * @param	bool		$required
 * @param	string		$placeholder
 * @param	mixed		$default
 * @param	bool		$auto_generate_option
 * @param	bool		$auto_generate_default
 * @param	string		$note
 * @param	array		$options
 * @return	void
 */
function pi_form_field( $name, $label, $type = 'text', $required = false, $placeholder = '', $default = '', $auto_generate_option = false, $auto_generate_default = true, $note = '', $options = array() ) {

	$classes = array( 'field', $type );
	if ( $required ) {
		$classes[] = 'required';
	}

	?>

	<li class="<?php echo implode( ' ', $classes ); ?>">

		<?php if ( $type == 'radio' ) { ?>

			<p class="label"><?php echo $label; ?></p>
			<?php foreach ( $options as $option_value => $option_label ) { ?>
				<input type="radio" name="<?php echo $name; ?>" id="<?php echo $name . '-' . $option_value; ?>" value="<?php echo $option_value; ?>"<?php if ( isset( $_REQUEST['action'] ) ) { echo isset( $_POST[ $name ] ) && $_POST[ $name ] == $option_value ? ' checked' : ''; } else if ( $default == $option_value ) { echo ' checked'; } ?>> <label for="<?php echo $name . '-' . $option_value; ?>"><?php echo $option_label; ?></label>
			<?php } ?>

		<?php } else if ( $type == 'checkbox' ) { ?>

			<input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="1"<?php if ( isset( $_REQUEST['action'] ) ) { echo isset( $_POST[ $name ] ) ? ' checked' : ''; } else if ( $default ) { echo ' checked'; } ?>>
			<label for="<?php echo $name; ?>"><?php echo $label; ?></label>

		<?php } else { ?>

			<label for="<?php echo $name; ?>"><?php echo $label; ?></label>
			<input type="<?php echo $type; ?>" name="<?php echo $name; ?>" id="<?php echo $name; ?>" class="input" <?php if ( $placeholder ) echo ' placeholder="' . $placeholder . '"'; ?> value="<?php echo isset( $_POST[ $name ] ) ? $_POST[ $name ] : $default; ?>" <?php if ( $required ) echo ' required="required"'; ?>>

		<?php } ?>

		<?php if ( $auto_generate_option ) { ?>
			<div class="checkbox"><input type="checkbox" name="<?php echo $name; ?>-generate" id="<?php echo $name; ?>-generate" value="1"<?php if ( isset( $_REQUEST['action'] ) ) { echo isset( $_POST['db-prefix-generate'] ) ? ' checked' : ''; } else if ( $auto_generate_default ) { echo ' checked'; } ?>> <label for="<?php echo $name; ?>-generate">Auto-generate <?php if ( $required ) echo ' (overrides any value entered)'; ?></label></div>
		<?php } ?>

		<?php if ( $note ) { ?>
			<p class="note"><?php echo $note; ?></p>
		<?php } ?>

	</li>

<?php }


/**
 * Generate random password
 *
 * @since	0.1
 * @param	int		$length
 * @param	string	$chars		'all' | 'alphanum'
 * @return	string
 */
function pi_generate_password( $length = 12, $chars = 'all' ) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	if ( $chars == 'all' ) {
		$chars .= "!@#$%^&*()_-=+;:,.?";
	}
	$password = substr( str_shuffle( $chars ), 0, $length );
	return $password;
}


/**
 * Replace values in a string
 *
 * @since	0.1
 * @uses	$pi_replace_values
 * @param	string		$string
 * @return	string
 */
function pi_replace_values( $string ) {
	global $pi_replace_values;

	// Do replacements
	foreach ( $pi_replace_values as $replace_key => $replace_value ) {
		$replace_key_prefix = '[[';
		$replace_key_suffix = ']]';
		if ( substr( $replace_key, 0, 2 ) == '//' ) {
			// These will be blocks in PHP
			$replace_key = ltrim( $replace_key, '/' );
			$replace_key_prefix = '//' . $replace_key_prefix;
		}
		$string = str_replace( $replace_key_prefix . $replace_key . $replace_key_suffix, $replace_value, $string );
	}

	return $string;
}


/**
 * Replace values in file's contents
 *
 * @since	0.1
 * @param	string		$filename
 * @return	void
 */
function pi_replace_in_file( $filename ) {

	$contents = file_get_contents( $filename );
	//echo '<pre>'; print_r( $contents ); echo '</pre>'; exit;

	$contents = pi_replace_values( $contents );
	//echo '<pre>'; print_r( $contents ); echo '</pre>'; exit;

	file_put_contents( $filename, $contents );

	/*
	 * Tried this code to avoid memory limit errors,
	 * but could only get it to append to file
	 *
	// Open and get contents
	$handle = fopen( $filename, 'w+' );
	$contents = fread( $handle, filesize( $filename ) );
	//echo '<pre>'; print_r( $contents ); echo '</pre>'; exit;

	// Do replacements
	$contents = pi_replace_values( $contents );
	//echo '<pre>'; print_r( $contents ); echo '</pre>'; exit;

	// Write back to file and close
	fwrite( $handle, $contents );
	fclose( $handle );
	*/

}


/**
 * Replace values in all files within a directory
 *
 * @since	0.1
 * @param	string		$dir
 * @return	void
 */
function pi_recursive_replace_in_dir( $dir ) {
	$dir_contents = scandir( $dir );
	//echo '<pre>'; print_r( $dir_contents ); echo '</pre>'; exit;

	foreach ( $dir_contents as $file ) {
		if ( ! in_array( $file, array( '.', '..' ) ) ) {

			if ( is_dir( $dir . '/' . $file ) ) {

				pi_recursive_replace_in_dir( $dir . '/' . $file );

			} else {

				// Check mime type for text
				$file_resource = finfo_open( FILEINFO_MIME_TYPE );
				$file_info = finfo_file( $file_resource, $dir . '/' . $file );
				if ( substr( $file_info, 0, 4 ) == 'text' ) {
					pi_replace_in_file( $dir . '/' . $file );
				}

			}

		}
	}

}


/**
 * Download a file from a URL
 *
 * @since	0.1
 * @param	string	$url
 * @param	string	$save_to
 * @return	void
 */
function pi_download_file( $url, $save_to ) {
	$ch = curl_init();
	$fp = fopen ( $save_to, 'w+' );
	$ch = curl_init( $url );
	curl_setopt_array( $ch, array(
		CURLOPT_TIMEOUT			=> 50,
		CURLOPT_FILE			=> $fp,
		CURLOPT_FOLLOWLOCATION	=> 1,
		CURLOPT_ENCODING		=> ""
	));
	curl_exec( $ch );
	curl_close( $ch );
	fclose( $fp );
}


/**
 * Unzip a ZIP archive
 *
 * @since	0.1
 * @param	string	$archive
 * @param	string	$unzip_to
 * @param	bool	$cleanup
 * @param	array	$remove_first
 * @return	void
 */
function pi_unzip_archive( $archive, $unzip_to, $cleanup = true, $remove_first = array() ) {
	$zip = new ZipArchive;
	$res = $zip->open( $archive );
	if ( $res === true ) {
		// Remove any files first?
		if ( $remove_first ) {
			foreach ( $remove_first as $remove_file ) {
				$zip->deleteName( $remove_file );
			}
		}
		// Extract
		$zip->extractTo( $unzip_to );
		$zip->close();
		// Cleanup?
		if ( $cleanup ) {
			unlink( $archive );
		}
	}
}


/**
 * Move all files in one directory to another
 *
 * @since	0.1
 * @param	string 	$source
 * @param	string	$destination
 * @return	void
 */
function pi_move_files( $source, $destination ) {
	$delete = array();
	// Get array of all source files
	$files = scandir( $source );
	// Cycle through all source files
	foreach ( $files as $file ) {
		if ( in_array( $file, array( ".", ".." ) ) || is_dir( $file ) ) {
			continue;
		}
		// If we copied this successfully, mark it for deletion
		if ( copy( $source . '/' . $file, $destination . '/' . $file ) ) {
			$delete[] = $source . '/' . $file;
		}
	}
	// Delete all successfully-copied files
	foreach ( $delete as $file ) {
		unlink( $file );
	}
}


/**
 * Uncomment a section of .htaccess string
 *
 * @since	0.1
 * @param	string 	$htaccess
 * @param	string	$section_name
 * @return	string
 */
function pi_uncomment_htaccess( $htaccess, $section_name ) {

	// Find start
	if ( ( $start_pos = strpos( $htaccess, '# ' . $section_name . '-start' ) ) !== false ) {

		// Find end
		if ( ( $end_pos = strpos( $htaccess, '# ' . $section_name . '-end' ) ) !== false ) {

			// Get the section
			$length = $end_pos - $start_pos;
			$section = substr( $htaccess, $start_pos, $length );
			//echo '<pre>'; print_r( $htaccess ); echo '</pre>';
			//echo '<pre>'; print_r( $start_pos ); echo '</pre>';
			//echo '<pre>'; print_r( $end_pos ); echo '</pre>';
			//echo '<pre>'; print_r( $length ); echo '</pre>';
			//echo '<pre>'; print_r( $section ); echo '</pre>'; exit;

			// Remove all doubled comment characters
			// Single ones are for real comments
			$section = str_replace( '##', '', $section );

			// Replace
			$htaccess = substr_replace( $htaccess, $section, $start_pos, $length );

		}

	}

	return $htaccess;
}


/**
 * Auto-generate values
 *
 * @since	0.1
 * @uses	$pi_replace_values
 * @return	void
 */
function pi_auto_generate_values() {
	global $pi_replace_values;

	foreach ( $pi_replace_values as $pi_replace_key => $pi_replace_value ) {
		$pi_replace_key_parts = explode( '-', $pi_replace_key );
		$pi_original_key = str_replace( '-generate', '', $pi_replace_key );

		// An exception for db-prefix, which is required, so the auto-generate flag should override if set
		if ( end( $pi_replace_key_parts ) == 'generate' && ( empty( $pi_replace_values[ $pi_original_key ] ) || $pi_original_key == 'db-prefix' ) ) {

			switch ( $pi_original_key ) {

				case 'db-prefix': {
					$pi_replace_values[ $pi_original_key ] = strtolower( pi_generate_password( 4, 'alphanum' ) ) . '_';
					break;
				}

				case 'wp-password':
				case 'staging-db-password':
				case 'production-db-password': {
					$pi_replace_values[ $pi_original_key ] = pi_generate_password();
					break;
				}

			}

		}

	}

}


/**
 * Update an option in a serialized option
 *
 * @since	0.1
 * @param	string	$option
 * @param	string	$key
 * @param	mixed	$value
 * @return	void
 */
function pi_update_seralized_option( $option, $key, $value ) {
	$pi_temp = get_option( $option );
	$pi_temp[ $key ] = $value;
	update_option( $option, $pi_temp );
}