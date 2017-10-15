<?php
require 'functions.php';
set_csrf_token();
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

		<div id="app">

			<div class="panel panel-default">
				<div class="panel-body">
					<table class="table">
						<caption class="text-left">Settings</caption>
						<tbody>
							<tr>
								<td>Print DPI</td>
								<td><input v-model="dpi" min="1" type="number"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<div class="panel panel-default" id="filedrag"
				v-if="fileAPI"
				v-on:dragover="dragover($event)"
				v-on:dragend="dragend($event)"
				v-on:dragleave="dragend($event)"
				v-on:drop="drop($event)"
				v-bind:class="{ 'loading': loading, 'dragging': dragging }">
				<div class="panel-body">
					<span id="spinner"></span>
					<span id="message">drag and drop your images here!</span>
				</div>
			</div>

			<p>... or choose them manually:</p>
			<label class="btn btn-primary btn-file">
				Choose Images
				<input type="file" id="filepicker" multiple="multiple"
					accept="image/*">
			</label>

			<div class="panel panel-default" id="results"
				v-if="images.length > 0">
				<table class="table">
					<thead>
						<th>image</th>
						<th>file</th>
						<th>dimensions (pixels)</th>
						<th>max print dimensions (inches)</th>
					</thead>
					<tbody id="table_datarows">
						<template v-for="image in images">
							<tr class="table-info"
								v-bind:class="{ 'expand': image.expanded }"
								v-on:click="image.expanded = !image.expanded">
								<td><img v-bind:src="image.image.blob" /></td>
								<td>{{ image.file.name }}</td>
								<td>{{ image.image.width }} x {{ image.image.height }}</td>
								<td>{{ (image.image.width / dpi).toFixed(2) }} x {{ (image.image.height / dpi).toFixed(2) }}</td>
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
											<tr>
												<td class="key">file type</td>
												<td class="val">{{ image.file.type }}</td>
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

	<?php if (getenv("PHP_ENV") === 'development') { ?>
		<script src="https://unpkg.com/vue"></script>
	<?php } else { ?>
		<script src="https://unpkg.com/vue/dist/vue.min.js"></script>
	<?php } ?>
	<script src="scripts.js"></script>
</body>
</html>