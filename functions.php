<?php

function set_csrf_token() {
	session_start();
	if (empty($_SESSION['csrf_token'])) {
		if (function_exists('random_bytes')) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		} else {
			$_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
		}
	}
}

// source: http://php.net/manual/en/function.filesize.php#106569
function human_filesize($bytes, $decimals = 2) {
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}