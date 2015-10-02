<?php

/**
 * Title: Orbis Openprovider admin
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0.0
 */
class Orbis_Openprovider_Admin {
	/**
	 * Plugin
	 *
	 * @var Orbis_InfiniteWP_Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an Orbis core admin
	 *
	 * @param Orbis_Plugin $plugin
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin initalize
	 */
	public function admin_init() {
		add_settings_section(
			'orbis_openprovider',
			__( 'Openprovider', 'orbis_openprovider' ),
			'__return_false',
			'orbis'
		);

		// Username
		register_setting( 'orbis', 'orbis_openprovider_username' );

		add_settings_field(
			'orbis_openprovider_username',
			__( 'Username', 'orbis_openprovider' ),
			array( $this, 'input_text' ),
			'orbis',
			'orbis_openprovider',
			array( 'label_for' => 'orbis_openprovider_username' )
		);

		// Password
		register_setting( 'orbis', 'orbis_openprovider_password' );

		add_settings_field(
			'orbis_openprovider_password',
			__( 'Password', 'orbis_openprovider' ),
			array( $this, 'input_text' ),
			'orbis',
			'orbis_openprovider',
			array( 'label_for' => 'orbis_openprovider_password' )
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Admin menu
	 */
	public function admin_menu() {
		add_submenu_page(
			'edit.php?post_type=orbis_subscription',
			__( 'Orbis Openprovider', 'orbis_openprovider' ),
			__( 'Openprovider', 'orbis_openprovider' ),
			'manage_options',
			'orbis_openprovider',
			array( $this, 'page_orbis_openprovider' )
		);
	}

	/**
	 * Page Orbis InfiniteWP
	 */
	public function page_orbis_openprovider() {
		include plugin_dir_path( $this->plugin->file ) . 'admin/page-orbis-openprovider.php';
	}

	//////////////////////////////////////////////////

	/**
	 * Input text
	 *
	 * @param array $args
	 */
	public function input_text( $args = array() ) {
		printf(
			'<input name="%s" id="%s" type="text" value="%s" class="%s" />',
			esc_attr( $args['label_for'] ),
			esc_attr( $args['label_for'] ),
			esc_attr( get_option( $args['label_for'] ) ),
			'regular-text code'
		);

		if ( isset( $args['description'] ) ) {
			printf(
				'<p class="description">%s</p>',
				$args['description']
			);
		}
	}
}
