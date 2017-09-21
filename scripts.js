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

var filepicker = document.getElementById("filepicker");
filepicker.addEventListener('change', function(e){
	var files = filepicker.files;
	if (files.length > 0) {
		fillTable(files);
		filepicker.value = "";
	}
})

function fillTable(files) {
	var dpi = 300;
	var fd = new FormData();

	app.loading = true;

	for (var i = 0; i < files.length; i++) {
		fd.append('images[]', files[i]);
		fd.append('blobs[]', window.URL.createObjectURL(files[i]));
	}



	var xhr  = new XMLHttpRequest();
	var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

	xhr.open('POST', 'process.php');
	xhr.setRequestHeader('CsrfToken', csrf);
	xhr.onload = function() {
		app.loading = false;
		if (xhr.status === 200) {
			var data = JSON.parse(xhr.responseText);
			app.images = app.images.concat(data)
		}
	}
	xhr.send(fd);

}

$(function() {

	$(document).on('click', '#results .table-info', function(e) {
		$(this).toggleClass("expand");
	});

});