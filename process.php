<?php

header('Content-Type: application/json');

require 'functions.php';
set_csrf_token();

if (!isset($_SERVER['HTTP_CSRFTOKEN'])) {
	$response = [
		"success" => false,
		"reason"  => "CSRF token missing.",
	];
	echo json_encode($response);
	die(1);
}

if ($_SERVER['HTTP_CSRFTOKEN'] !== $_SESSION['csrf_token']) {
	$response = [
		"success" => false,
		"reason"  => "Invalid CSRF token.",
	];
	echo json_encode($response);
	die(1);
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
	$imagesize  = getimagesize($image);

	if (!is_array($imagesize) ||
		!in_array(image_type_to_extension($imagesize[2]), [".jpg", ".jpeg", ".gif", ".png"])) {
		continue; // invalid image
	}

	$filesize     = $images['size'][$index];
	$image_width  = $imagesize[0];
	$image_height = $imagesize[1];

	$data = [
		'file' => [
			'name'  => $image_name,
			'size'  => human_filesize($filesize),
			'color' => ($imagesize['channels'] == 4) ? 'CMYK' : 'RGB',
			'type'  => $mime_type,
		],
		'image' => [
			'blob'   => $_REQUEST['blobs'][$index],
			'width'  => $image_width,
			'height' => $image_height,
		],
		'expanded' => false,
	];

	array_push($output, $data);

}

if (empty($output)) {
	$response = [
		"success" => false,
		"reason"  => "No images processed.",
	];
	echo json_encode($response);
	die(1);
}

$response = [
	"success" => true,
	"data"    => $output,
];
echo json_encode($response);
exit();
