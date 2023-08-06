<?php

	function SQL() {
		$path = $_SERVER['CONFIG_DIR'] . '/db.ini';
		$arr = parse_ini_file( $path );

		return new mysqli(
			$arr['host'],
			$arr['user'],
			$arr['passwd'],
			$arr['database'],
			$arr['port']
		);
	}

	//	creates connection
	$sql = SQL();
	//	checks connection
	if ($sql->connect_errno)
		die('Database error: ' . $sql->connect_error);

?>
