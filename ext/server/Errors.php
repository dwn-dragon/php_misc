<?php

	class Errors
	{
		const NONE			= -1;

		const BAD_METHOD	= 10;
		const BAD_INPUT		= 11;

		const INVALID_AUTH	= 20;

		const BAD_MYSQL		= 100;
	};

/*
	begin
	end
	append

	fatal
	http code
*/

	$Errors = [
		Errors::NONE => [
			//	methods
			function ($args) { return null; },
			function ($err) { return null; },
			function ($err, $args) { return null; },
			//	modifiers
			false,
			201,
		],

		Errors::BAD_METHOD => [
			//	methods
			function ($args) { return $args[0] ?? 'unknown'; },
			function ($err) { return $err; },
			function ($err, $args) { return $err; },
			//	modifiers
			true,
			405,
		],
		Errors::BAD_INPUT => [
			//	methods
			function ($args) { return []; },
			function ($err) { return $err; },
			function ($err, $args) {
				foreach ($args as $arg)
					$err = $arg;
				return $err;
			},
			//	modifiers
			true,
			400,
		],
		Errors::INVALID_AUTH => [
			//	methods
			function ($args) { 
				return 'invalid credentials';
			},
			function ($err) { 
				return $err; 
			},
			function ($err, $args) { 
				return $err; 
			},
			//	modifiers
			true,
			401,
		],

		Errors::BAD_MYSQL => [
			//	methods
			function ($args) { 
				return $args[0];
			},
			function ($err) { 
				return $err; 
			},
			function ($err, $args) { 
				return $err; 
			},
			//	modifiers
			true,
			500,
		]
	];

?>