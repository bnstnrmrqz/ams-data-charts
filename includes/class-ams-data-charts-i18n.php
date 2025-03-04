<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://https://github.com/bnstnrmrqz/
 * @since      1.0.0
 *
 * @package    Ams_Data_Charts
 * @subpackage Ams_Data_Charts/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ams_Data_Charts
 * @subpackage Ams_Data_Charts/includes
 * @author     Ben Steiner Marquez <bnstnrmrqz@gmail.com>
 */
class Ams_Data_Charts_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ams-data-charts',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
