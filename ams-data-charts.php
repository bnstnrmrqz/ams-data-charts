<?php

/**
 * @link              https://https://github.com/bnstnrmrqz
 * @since             1.0.0
 * @package           Ams_Data_Charts
 *
 * @wordpress-plugin
 * Plugin Name:       AMS Data Charts
 * Plugin URI:        https://https://github.com/bnstnrmrqz/ams-data-charts
 * Description:       The AMS Data Charts plugin allows users to generate interactive charts via a shortcode on their WordPress website. Utilizing Google's Charts API, the plugin visualizes data in a clear and customizable format, making it easy to display analytical insights, trends, and real-time readings from sources like the Aqua Metrology Systems (AMS) API.
 * Version:           1.0.1
 * Author:            Ben Steiner Marquez
 * Author URI:        https://https://github.com/bnstnrmrqz
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ams-data-charts
 * Domain Path:       /languages
 */

 // If this file is called directly, abort.
 if(!defined('WPINC'))
 {
     die;
 }

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('AMS_DATA_CHARTS_VERSION', '1.0.1');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ams-data-charts-activator.php
 */
function activate_ams_data_charts()
{
	require_once plugin_dir_path(__FILE__ ).'includes/class-ams-data-charts-activator.php';
	Ams_Data_Charts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ams-data-charts-deactivator.php
 */
function deactivate_ams_data_charts()
{
	require_once plugin_dir_path(__FILE__).'includes/class-ams-data-charts-deactivator.php';
	Ams_Data_Charts_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_ams_data_charts');
register_deactivation_hook(__FILE__, 'deactivate_ams_data_charts');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__).'includes/class-ams-data-charts.php';

/**
 * Include the shortcodes functionality file.
 */
include_once plugin_dir_path(__FILE__).'includes/ams-data-charts-shortcode.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ams_data_charts()
{
	$plugin = new Ams_Data_Charts();
	$plugin->run();
}
run_ams_data_charts();
