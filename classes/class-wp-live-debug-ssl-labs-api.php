<?php
/**
 * PHP-SSLLabs-API
 *
 * This PHP library provides basic access to the SSL Labs API
 * and is build upon the official API documentation at
 * https://github.com/ssllabs/ssllabs-scan/blob/master/ssllabs-api-docs.md
 *
 * @author Björn Roland <https://github.com/bjoernr-de>
 * @license GNU GENERAL PUBLIC LICENSE v3
 */

// Check that the file is not accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * WP_Live_Debug_SSL_Labs_API Class.
 */
if ( ! class_exists( 'WP_Live_Debug_SSL_Labs_API' ) ) {
	class WP_Live_Debug_SSL_Labs_API {
		const WPLD_SSL_LABS_API = 'https://api.ssllabs.com/api/v3';

		private $return_json_objects;

		/**
		 * sslLabsApi::__construct()
		 */
		public function __construct( $return_json_objects = false ) {
			$this->return_json_objects = (boolean) $return_json_objects;
		}

		/**
		 * sslLabsApi::fetch_api_info()
		 *
		 * API Call: info
		 * @see https://github.com/ssllabs/ssllabs-scan/blob/master/ssllabs-api-docs.md
		 */
		public function fetch_api_info() {
			return ( $this->sendapi_request( 'info' ) );
		}

		/**
		 * sslLabsApi::fetch_host_information()
		 *
		 * API Call: analyze
		 * @see https://github.com/ssllabs/ssllabs-scan/blob/master/ssllabs-api-docs.md
		 *
		 * @param string $host Hostname to analyze
		 * @param boolean $publish
		 * @param boolean $start_new
		 * @param boolean $from_cache
		 * @param int $max_age
		 * @param string $all
		 * @param boolean $ignore_mismatch
		 */
		public function fetch_host_information( $host, $publish = false, $start_new = false, $from_cache = false, $max_age = null, $all = null, $ignore_mismatch = false ) {
			$api_request = $this->sendapi_request( 'analyze', array(
				'host'            => $host,
				'publish'         => $publish,
				'start_new'       => $start_new,
				'from_cache'      => $from_cache,
				'max_age'         => $max_age,
				'all'             => $all,
				'ignore_mismatch' => $ignore_mismatch,
			) );

			return ( $api_request );
		}

		/**
		 * sslLabsApi::fetch_host_information_cached()
		 *
		 * API Call: analyze
		 * Same as fetch_host_information() but prefer to receive cached information
		 *
		 * @param string $host
		 * @param int $max_age
		 * @param string $publish
		 * @param string $ignore_mismatch
		 */
		public function fetch_host_information_cached( $host, $max_age, $publish = false, $ignore_mismatch = false ) {
			return( $this->fetch_host_information( $host, $publish, false, true, $max_age, 'done', $ignore_mismatch ) );
		}

		/**
		 * sslLabsApi::fetch_endpoint_data()
		 *
		 * API Call: getEndpointData
		 * @see https://github.com/ssllabs/ssllabs-scan/blob/master/ssllabs-api-docs.md
		 *
		 * @param string $host
		 * @param string $s
		 * @param string $from_cache
		 * @return string
		 */
		public function fetch_endpoint_data( $host, $ip, $from_cache = false ) {
			$api_request = $this->sendapi_request( 'getEndpointData', array(
				'host'       => $host,
				's'          => $ip,
				'from_cache' => $from_cache,
			) );

			return ( $api_request );
		}

		/**
		 * sslLabsApi::fetch_status_codes()
		 *
		 * API Call: getStatusCodes
		 */
		public function fetch_status_codes() {
			return ( $this->sendapi_request( 'getStatusCodes' ) );
		}

		/**
		 * sslLabsApi::sendapi_request()
		 *
		 * Send API request
		 *
		 * @param string $api_call
		 * @param array $parameters
		 * @return string JSON from API
		 */
		public function sendapi_request( $api_call, $parameters = array() ) {
			//we also want content from failed api responses
			$context = stream_context_create( array(
				'http' => array(
					'ignore_errors' => true,
				),
			) );

			$api_response = file_get_contents( self::WPLD_SSL_LABS_API . '/' . $api_call . $this->build_get_parameter_string( $parameters ), false, $context );

			if ( $this->return_json_objects ) {
				return ( json_decode( $api_response ) );
			}

			return ( $api_response );
		}

		/**
		 * sslLabsApi::setreturn_json_objects()
		 *
		 * Setter for return_json_objects
		 * Set true to return all API responses as JSON object, false returns it as simple JSON strings (default)
		 *
		 * @param boolean $return_json_objects
		 */
		public function setreturn_json_objects( $return_json_objects ) {
			$this->return_json_objects = (boolean) $return_json_objects;
		}

		/**
		 * sslLabsApi::getreturn_json_objects()
		 *
		 * Getter for return_json_objects
		 *
		 * @return boolean true returns all API responses as JSON object, false returns it as simple JSON string
		 */
		public function getreturn_json_objects() {
			return ( $this->return_json_objects );
		}

		/**
		 * sslLabsApi::build_get_parameter_string()
		 *
		 * Helper function to build get parameter string for URL
		 *
		 * @param array $parameters
		 * @return string
		 */
		private function build_get_parameter_string( $parameters ) {
			$string = '';

			$counter = 0;
			foreach ( $parameters as $name => $value ) {
				if ( ! is_string( $name ) || ( ! is_string( $value ) && ! is_bool( $value ) && ! is_int( $value ) ) ) {
					continue;
				}

				if ( is_bool( $value ) ) {
					$value = ( $value ) ? 'on' : 'off';
				}

				$string .= ( 0 == $counter ) ? '?' : '&';
				$string .= urlencode( $name ) . '=' . urlencode( $value );

				$counter++;
			}

			return ( $string );
		}
	}
}
