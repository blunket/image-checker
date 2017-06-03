
function handleFiles(files, callback) {
    var imgs = [];
    for (i = 0; i < files.length; i++) {
        let file = files[i];
        let img = new Image();
        img.src = window.URL.createObjectURL(file);
        img.onload = function() {
            this.origFileName = file.name;
            imgs.push(this);
            window.URL.revokeObjectURL(this.src);
            if (imgs.length == files.length) {
                callback(imgs);
                return;
            }
        }
    }
}

$(function() {

    if (window.File && window.FileList && window.FileReader) {
        $("#filedrag")
            .show()
            .on('dragover', function(e) {
                e.preventDefault();
                $(this).addClass("dragging");
                // $(this).removeClass("loading");
            })
            .on('dragend dragleave', function(e) {
                e.preventDefault();
                $(this).removeClass("dragging");
            })
            .on('drop', function(e) {
                e.preventDefault();
                let files = e.originalEvent.dataTransfer.files;
                $(this).removeClass("dragging");
                // $(this).addClass("loading");
                let dpi = 300;
                handleFiles(files, function(imgs) {
                    // $("#filedrag").removeClass("loading");
                    $.each(imgs, function(i, img) {
                        let fn  = img.origFileName,
                            mwi = (img.width / dpi).toFixed(2),
                            mhi = (img.height / dpi).toFixed(2),
                            mwc = (mwi * 2.54).toFixed(1),
                            mhc = (mhi * 2.54).toFixed(1);

                        $("<tr>")
                            .append( $("<td>").html(img) )
                            .append("<td>" + fn + "</td>")
                            .append("<td>" + img.width + " x " + img.height + "</td>")
                            .append("<td>" + mwi + "in <em>("+ mwc +"cm)</em></td>")
                            .append("<td>" + mhi + "in <em>("+ mhc +"cm)</em></td>")
                            .appendTo("#results");


                        $("#results").show();
                    })
                });
            });
    }


});