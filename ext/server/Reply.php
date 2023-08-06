<?php

	include $_SERVER['EXTERNAL_DIR'] . '/server/Errors.php';

	function jsonSerialize($elem): mixed {
		if (is_object($elem) && isset(class_implements($elem)['JsonSerializable']))
			return $elem->jsonSerialize();
		return $elem;
	}
	
	class Reply
		implements \JsonSerializable
	{

		public mixed $data;

		public function __destruct() {
			if (!$this->_complete)
				$this->commit();
		}
		public function __construct() {
			$this->_complete	= false;

			$this->_errno		= Errors::NONE;

			$this->data			= null;
			$this->_error		= null;
		}

		public function commit($http_code = -1, $force_json = true): void {
			if ($this->_complete)
				return;

			$this->_end($this->_error);
			if ($http_code < 0)
				$http_code = $this->_getHTTPCode();

			http_response_code($http_code);
			header('Content-Type: application/json', $force_json);

			echo json_encode($this);

			$this->_complete = true;
			exit(0);
		}

		public function setErrno($errno, ...$args): Reply {
			$this->_errno = $errno;
			$this->_begin($args);

			return $this;
		}
		public function appendToError(...$args): Reply {
			$this->_append($this->_error, $args);
			return $this;
		}

		public function jsonSerialize(): mixed {
			$res = new stdClass();
			$res->errno = $this->_errno;
			if ($res->errno != Errors::NONE)
				$res->error = jsonSerialize($this->_error);
			if (!$this->_isFatal())
				$res->data = jsonSerialize($this->data);
			return $res;
		}

		private function _begin($args): void {
			global $Errors;
			$this->_error = $Errors[$this->_errno][Reply::BEGIN]($args);
		}
		private function _end($error): void {
			global $Errors;
			$this->_error = $Errors[$this->_errno][Reply::END]($error);
		}
		private function _append($error, $args): void {
			global $Errors;
			$this->_error = $Errors[$this->_errno][Reply::APPEND]($error, $args);
		}

		private function _isFatal(): bool {
			global $Errors;
			return $Errors[$this->_errno][Reply::FATAL];
		}
		private function _getHTTPCode(): int {
			global $Errors;
			return $Errors[$this->_errno][Reply::HTTP_CODE];
		}

		private int		$_errno;
		private mixed	$_error;

		private bool 	$_complete;

		private const 	BEGIN		= 0;
		private const 	END			= 1;
		private const 	APPEND		= 2;

		private const 	FATAL		= 3;
		private const 	HTTP_CODE	= 4;

	};

	$Reply = new Reply();

?>
