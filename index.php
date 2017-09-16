<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
	if (function_exists('random_bytes')) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	} else {
		$_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="Find out if your images are actually usable!">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
	<title>Image Upload Checker</title>

	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
		rel="stylesheet"
		integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
		crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
	<div class="container">
		<div class="page-header">
			<h1>Image Upload Checker</h1>
			<p>Here you can find out if your images are useable.</p>
		</div>
		<main class="panel panel-default">
			<div class="panel-body">
				<p>This tool can provide quick, useful and detailed information about many images at once.</p>
				<p>How to use:</p>
				<ol>
					<li>Drag and drop your images into the box below, or use the button to choose them.</li>
					<li>Information about the images will appear in a table below.</li>
					<li>Click on an image in the table for extra details.</li>
				</ol>
			</div>
		</main>

		<div class="panel panel-default" id="filedrag">
			<div class="panel-body">
				<span id="spinner"></span>
				drag and drop your images here!
			</div>
		</div>

		<div id="app">
			<div class="panel panel-default" id="results" v-if="images.length > 0">
				<table class="table">
					<thead>
						<th>image</th>
						<th>file</th>
						<th>dimensions (pixels)</th>
						<th>max print dimensions (inches)</th>
					</thead>
					<tbody id="table_datarows">
						<template v-for="image in images">
							<tr class="table-info">
								<td><img v-bind:src="image.image.blob" /></td>
								<td>{{ image.file.name }}</td>
								<td>{{ image.image.width }} x {{ image.image.height }}</td>
								<td>{{ image.print.width }} x {{ image.print.height }}</td>
							</tr>
							<tr class="table-details">
								<td colspan="100%">
									<div class="well">
										<table>
											<tr>
												<td class="key">file name</td>
												<td class="val">{{ image.file.name }}</td>
											</tr>
											<tr>
												<td class="key">file size</td>
												<td class="val">{{ image.file.size }}</td>
											</tr>
											<tr>
												<td class="key">color type</td>
												<td class="val">{{ image.file.color }}</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</template>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div id="copyright">
		<small>© 2017 <a href="https://andrewsiegman.com/" target="_blank">andrew siegman</a> &amp; <a href="http://www.nickdrakedesign.com" target="_blank">nick drake</a></small>
	</div>

	<script
		src="https://code.jquery.com/jquery-3.2.1.min.js"
		integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
		crossorigin="anonymous"></script>

	<script src="https://unpkg.com/vue"></script>
	<script src="scripts.js"></script>
</body>
</html>