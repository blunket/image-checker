var app = new Vue({
	el: "#app",
	data: {
		images: [],
		loading: false,
		dragging: false,
		fileAPI: window.File && window.FileList && window.FileReader
	},
	methods: {
		dragover: function(e) {
			e.preventDefault();
			this.dragging = true;
		},
		dragend: function(e) {
			e.preventDefault();
			this.dragging = false;
		},
		drop: function(e) {
			e.preventDefault();
			this.dragging = false;

			var files = e.dataTransfer.files;
			if (files.length > 0) {
				fillTable(files);
			}
		}
	}
})

function fillTable(files) {
	var dpi = 300;
	var fd = new FormData();

	app.loading = true;

	$.each(files, function(i, file) {
		fd.append('images[]', file);
		fd.append('blobs[]', window.URL.createObjectURL(file));
	});

	$.ajax({
		url:  "process.php",
		data: fd,
		type: "POST",
		contentType: false,
		processData: false,
		dataType: "json",
		cache: false,
		success: function(data) {
			app.images = app.images.concat(data);
		}
	}).done(function() {
		app.loading = false;
	});

}

$(function() {

	$.ajaxSetup({
		headers : {
			'CsrfToken': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$(document).on('click', '#results .table-info', function(e) {
		$(this).toggleClass("expand");
	});

});