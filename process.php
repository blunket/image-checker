<?php

// csrf token check: https://stackoverflow.com/questions/37912937/how-to-send-secure-ajax-requests-with-php-and-jquery
session_start();
if (empty($_SESSION['csrf_token'])) {
	if (function_exists('random_bytes')) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	} else {
		$_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
	}
}

if (isset($_SERVER['HTTP_CSRFTOKEN'])) {
	if ($_SERVER['HTTP_CSRFTOKEN'] !== $_SESSION['csrf_token']) {
		die('Wrong CSRF token.');
	}
} else {
	die('No CSRF token.');
}

// source: http://php.net/manual/en/function.filesize.php#106569
function human_filesize($bytes, $decimals = 2) {
	$sz = 'BKMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

$images = $_FILES['images'];
$output = [];

foreach ($images['type'] as $index => $mime_type) {
	if (!in_array($mime_type, ["image/jpeg", "image/png", "image/gif"])) {
		continue;
	}
	if (!empty($images['error'][$index])) {
		continue;
	}

	$image_name = $images['name'][$index];
	$image      = $images['tmp_name'][$index];

	if(!is_array(getimagesize($image))){
		continue; // invalid image
	}

	$imagesize    = getimagesize($image);
	$image_width  = $imagesize[0];
	$image_height = $imagesize[1];

	$data = [
		'file' => [
			'name'  => $image_name,
			'size'  => human_filesize(filesize($image)),
			'color' => ($imagesize['channels'] == 4) ? 'CMYK' : 'RGB',
		],
		'image' => [
			'blob'   => $_REQUEST['blobs'][$index],
			'width'  => $image_width,
			'height' => $image_height,
		],
		'print' => [
			'width'  => round($image_width / 300, 2),
			'height' => round($image_height / 300, 2),
		],
	];

	array_push($output, $data);

}

echo json_encode($output);
