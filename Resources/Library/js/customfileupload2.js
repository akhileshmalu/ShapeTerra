/**
 * Created by akhi on 4/4/17.
 *
 * Sole purpose is to duplicate a file upload functionality temporarily form custom file upload master JS
 */
$(document).ready(function () {

    var item = $('.custom-file-upload2');
    var parentDiv = item.parent();

    var previewButton1 = $('<input id="previewBtn1" type="button" value="Preview"  data-toggle="modal" ' +
        'data-target="#previewFileModal1" class="btn btn-info col-xs-1"/>');
    var changeButton1 = $('<input id="changeBtn1" type="button" type="button" value="Change" ' +
        ' class="btn btn-info col-xs-1" />');

    var clearButton1 = $('<input id="clearBtn1" type="button" value="Remove" ' +
        'class="btn btn-info col-xs-1">');

    var modalContainer = $('<div class="modal fade" id="previewFileModal1" tabindex="-1" role="dialog"' +
        ' aria-labelledby="myModalLabel">' + '<div class="modal-dialog" role="dialog">' + ' <div class="modal-content">' +
        '<div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span></button> <h4 class="modal-title" id="myModalLabel">Preview File' +
        '</h4></div> <div class="modal-body"> <div class="form-group"><div style="clear:both"> ' +
        '<iframe id="viewer1" frameborder="0" scrolling="no" width="550" height="500"></iframe> </div> </div> </div>' +
        ' <div class="modal-footer"> </div> </div> </div> </div>');
    $('body').append(modalContainer);


// Existing File Available Setup ; PHP push file path from DB in defaultvalue attribute
    var oldFile = item.attr("defaultValue");
    if (oldFile != '') {
        var result = oldFile.split("/");
        item.attr("type", "text").attr("readonly", "readonly").attr("value", result[result.length - 1]).css("width", "80%");
        $('#viewer1').attr('src', '..' + oldFile.substr(46));
        parentDiv.append(previewButton1);
        parentDiv.append(changeButton1);
    }

    $('#changeBtn').on("click", function () {
        if (confirm("You will loose attached File. Are you sure you want to continue?")) {
            $('#changeBtn1').remove();
            $('#previewBtn1').remove();
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
                parentDiv.append(previewButton1);
                $('#previewBtn1').on("click", function () {
                    pdffile = document.getElementById(item.attr("id")).files[0];
                    pdffile_url = URL.createObjectURL(pdffile);
                    $('#viewer1').attr('src', pdffile_url);
                });

                //Adding Remove Button
                parentDiv.append(clearButton1);
                $('#clearBtn1').on("click", function () {
                    item.val('');
                    item.css("color", "#555").css("width", "100%");
                    $('#previewBtn1').remove();
                    $('#clearBtn1').remove();
                });


            } else {
                alert('Invalid file Format. Only ' + allowedext.join(', ') + ' are allowed.');
                $(this).val('');
            }
        }
    });

});