<?php

	// csrf token check: https://stackoverflow.com/questions/37912937/how-to-send-secure-ajax-requests-with-php-and-jquery
	session_start();
	if (empty($_SESSION['csrf_token'])) {
	    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}

	$headers = apache_request_headers();
	if (isset($headers['CsrfToken'])) {
	    if ($headers['CsrfToken'] !== $_SESSION['csrf_token']) {
	        die('Wrong CSRF token.');
	    }
	} else {
	    die('No CSRF token.');
	}

	// each file will be processed one at a time, so $_FILES should only ever have 1 file
    $image = $_FILES['file']['tmp_name'];
    $image_name = $_FILES['file']['name'];

	$imageData = file_get_contents($image);
	$b64 = base64_encode($imageData);

	if(!is_array(getimagesize($image))){
	    die(); // not an image -- no output
	}

	$imagesize    = getimagesize($image);
	$image_width  = $imagesize[0];
	$image_height = $imagesize[1];

	// source: http://php.net/manual/en/function.filesize.php#106569
	function human_filesize($bytes, $decimals = 2) {
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
	}

?>
<tr class="table-info">
    <td class="image"><img src="data:image/png;base64,<?= $b64 ?>" /></td>
    <td class="file"><?= $image_name ?></td>
    <td class="display"><?= $image_width ?> x <?= $image_height ?></td>
    <td class="print"><?= round($image_width / 300, 2) ?> x <?= round($image_height / 300, 2) ?></td>
</tr>
<tr class="table-details">
    <td colspan="100%">
        <div class="well">
            <table>
                <tr>
                    <td class="key">file name</td>
                    <td class="val"><?= $image_name; ?></td>
                </tr>
                <tr>
                    <td class="key">file size</td>
                    <td class="val"><?= human_filesize(filesize($image)); ?></td>
                </tr>
                <tr>
                    <td class="key">color type</td>
                    <td class="val"><?= ($imagesize['channels']==4) ? 'CMYK' : 'RGB'; ?></td>
                </tr>
            </table>
        </div>
    </td>
</tr>