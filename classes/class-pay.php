<?php

/*
 * Pay API.
 */

namespace Pay;

class Pay {

	/*
	 * Client ID.
	 */
	private $client_id = get_option('payu_client_id');

	/*
	 * Client Secret.
	 */
	private $client_secret = get_option('payu_client_secret');

	/**
	 * MID.
	 */
	private $mid = get_option('payu_mid');

	/*
	 * Const.
	 */
	public function __construct() {
		

	}
}
