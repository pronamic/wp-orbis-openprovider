<?php

class Orbis_Openprovider_Plugin extends Orbis_Plugin {
	public function __construct( $file ) {
		parent::__construct( $file );

		$this->set_name( 'orbis_openprovider' );
		$this->set_db_version( '1.0.0' );

		// Admin
		if ( is_admin() ) {
			$this->admin = new Orbis_Openprovider_Admin( $this );
		}

		// Actions
		add_action( 'orbis_after_main_content', array( $this, 'orbis_after_main_content' ) );
	}

	public function loaded() {
		$this->load_textdomain( 'orbis_openprovider', '/languages/' );
	}

	public function install() {
		

		parent::install();
	}

	public function orbis_after_main_content() {
		if ( 'orbis_domain_name' !== get_post_type() ) {
			return;
		}

		include __DIR__ . '/../templates/domain-name-dns-records.php';
	}

	//////////////////////////////////////////////////

	/**
	 * Get Orbis subscriptions
	 */
	public function get_orbis_subscriptions() {
		global $wpdb;

		$subscriptions = array();

		// Query
		$sql = "
			SELECT
				subscription.name AS subscription_name
			FROM
				$wpdb->orbis_subscriptions AS subscription
					LEFT JOIN
				$wpdb->orbis_subscription_products AS product
						ON subscription.type_id = product.id
			WHERE
				subscription.cancel_date IS NULL
					AND
				product.name LIKE '%Domeinnaam%'
			;
		";

		$results = $wpdb->get_results( $sql );

		foreach ( $results as $result ) {
			if ( ! isset( $subscriptions[ $result->subscription_name ] ) ) {
				$subscriptions[ $result->subscription_name ] = array();
			}

			$subscriptions[ $result->subscription_name ][] = $result;
		}

		return $subscriptions;
	}

	//////////////////////////////////////////////////

	private function get_xml_base() {
		$xml = new SimpleXMLElement( '<openXML />' );

		$credentials = $xml->addChild( 'credentials' );
		$credentials->addChild( 'username', get_option( 'orbis_openprovider_username' ) );
		$credentials->addChild( 'password', get_option( 'orbis_openprovider_password' ) );

		return $xml;
	}

	private function request( $xml ) {
		$url = 'https://api.openprovider.eu/';

		$response = wp_remote_post( $url, array(
			'body'    => $xml->asXML(),
			'timeout' => 60,
		) );

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			throw new \Exception( 'Unexpected response from Openprovider' );
		}

		$body = wp_remote_retrieve_body( $response );

		$xml = simplexml_load_string( $body );

		if ( false === $xml ) {
			throw new \Exception( 'Unexpected response from Openprovider' );
		}

		return $xml;
	}

	public function get_openprovider_dns_records( $name ) {
		$xml = $this->get_xml_base();

		$request = $xml->addChild( 'searchZoneRecordDnsRequest' );
		$request->addChild( 'name', $name );

		$xml = $this->request( $xml );

		return $xml;
	}

	/**
	 * Get Openprovider domains
	 */
	public function get_openprovider_domains() {
		$domains = array();

		$xml = $this->get_xml_base();

		$request = $xml->addChild( 'searchDomainRequest' );
		$request->addChild( 'limit', 1000 );

		$xml = $this->request( $xml );

		$items = $xml->reply->data->results->array->item;

		foreach ( $items as $item ) {
			$domain_name      = (string) $item->domain->name;
			$domain_extension = (string) $item->domain->extension;

			$name = $domain_name . '.' . $domain_extension;

			$domains[ $name ] = $item;
		}

		return $domains;
	}
}
