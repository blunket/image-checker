
function handleFiles(files) {
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
                fillTable(imgs);
                return;
            }
        }
    }
}

function fillTable(imgs) {
    $.each(imgs, function(i, img) {
        let dpi = 300;

        let fn  = img.origFileName,
            mwi = (img.width / dpi).toFixed(2),
            mhi = (img.height / dpi).toFixed(2),
            mwc = (mwi * 2.54).toFixed(1),
            mhc = (mhi * 2.54).toFixed(1);

        let row = $("#template_row").clone().removeAttr("id");
        row.find(".image").html(img);
        row.find(".file").text(fn);
        row.find(".display").text(img.width + " x " + img.height);
        row.find(".print").text(mwi + " x " + mhi);

        let more = $("#template_more").clone().removeAttr("id");

        row.appendTo("#results #rows");
        more.appendTo("#results #rows");

    });

    $("#results").show();
}


$(function() {

    if (window.File && window.FileList && window.FileReader) {
        $("#filedrag")
            .show()
            .on('dragover', function(e) {
                e.preventDefault();
                $(this).addClass("dragging");
            })
            .on('dragend dragleave', function(e) {
                e.preventDefault();
                $(this).removeClass("dragging");
            })
            .on('drop', function(e) {
                e.preventDefault();
                let files = e.originalEvent.dataTransfer.files;
                $(this).removeClass("dragging");
                handleFiles(files);
            });
    }


    $(document).on('click', '#results .row', function(e) {
        $(this).toggleClass("expand");
    });

});