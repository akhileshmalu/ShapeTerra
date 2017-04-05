$(document).ready(function () {

    var item = $('.custom-file-upload');
    var parentDiv = item.parent();

    var previewButton = $('<input id="previewBtn" type="button" value="Preview"  data-toggle="modal" ' +
        'data-target="#previewFileModal" class="btn btn-info col-xs-1"/>');
    var changeButton = $('<input id="changeBtn" type="button" type="button" value="Change" ' +
        ' class="btn btn-info col-xs-1" />');

    var clearButton = $('<input id="clearBtn" type="button" value="Remove" ' +
        'class="btn btn-info col-xs-1">');

    var modalContainer = $('<div class="modal fade" id="previewFileModal" tabindex="-1" role="dialog"' +
        ' aria-labelledby="myModalLabel">' + '<div class="modal-dialog" role="dialog">' + ' <div class="modal-content">' +
        '<div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span></button> <h4 class="modal-title" id="myModalLabel">Preview File' +
        '</h4></div> <div class="modal-body"> <div class="form-group"><div style="clear:both"> ' +
        '<iframe id="viewer" frameborder="0" scrolling="no" width="550" height="500"></iframe> </div> </div> </div>' +
        ' <div class="modal-footer"> </div> </div> </div> </div>');
    $('body').append(modalContainer);


// Existing File Available Setup ; PHP push file path from DB in defaultvalue attribute
    var oldFile = item.attr("defaultValue");
    if (oldFile != '') {
        var result = oldFile.split("/");
        item.attr("type", "text").attr("readonly", "readonly").attr("value", result[result.length - 1]).css("width", "80%");
        $('#viewer').attr('src', '..' + oldFile.substr(46));
        parentDiv.append(previewButton);
        parentDiv.append(changeButton);
    }

    $('#changeBtn').on("click", function () {
        if (confirm("You will loose attached File. Are you sure you want to continue?")) {
            $('#changeBtn').remove();
            $('#previewBtn').remove();
            item.attr("type", "file").removeAttr("readonly").css("width", "100%");
        }
    });


    item.on("change", function () {

        var doc, image;
        var filename = $(this).val();
        var extention = $(this).val().substr(filename.lastIndexOf('.') + 1).toLowerCase();
        var allowedext = [$(this).attr("filetype")];

        if (filename.length > 0) {
            if (allowedext.indexOf(extention) !== -1) {
                alert(filename.substr(12) + " is selected.");
                $(this).css("width", "80%");

                // Adding Preview Button
                parentDiv.append(previewButton);
                $('#previewBtn').on("click", function () {
                    pdffile = document.getElementById(item.attr("id")).files[0];
                    pdffile_url = URL.createObjectURL(pdffile);
                    $('#viewer').attr('src', pdffile_url);
                });

                //Adding Remove Button
                parentDiv.append(clearButton);
                $('#clearBtn').on("click", function () {
                    item.val('');
                    item.css("color", "#555").css("width", "100%");
                    $('#previewBtn').remove();
                    $('#clearBtn').remove();
                });


            } else {
                alert('Invalid file Format. Only ' + allowedext.join(', ') + ' are allowed.');
                $(this).val('');
            }
        }
    });

});