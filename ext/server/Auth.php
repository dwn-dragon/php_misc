<?php

	class Auth
	{
		public function __construct() {
			$path = $_SERVER['CONFIG_DIR'] . '/auth.ini';
			$config = parse_ini_file($path);

			$this->_algHalf	= $config['alg_half'];
			$this->_algFull	= $config['alg_full'];

			$this->_cost	= $config['cost'];
			$this->_salt	= $config['salt'];
		}

		public function preHash($passwd) {
			return hash_hmac($this->_algHalf, $passwd, $this->_salt);
		}
		public function fullHash($passwd) {
			return password_hash($this->preHash($passwd), $this->_algFull, [ "cost" => $this->_cost ]);
		}
		public function verify($fullHash, $preHash) {
			return password_verify($fullHash, $preHash);
		}

		private string	$_algHalf;
		private string	$_algFull;

		private string	$_salt;
		private int		$_cost;
	};

	$Auth = new Auth();

?>
