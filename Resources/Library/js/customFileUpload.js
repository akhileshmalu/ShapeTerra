$(document).each(function () {

    $.each($('.custom-file-upload'),function (key, value) {

        // On load
        fileUploadOnLoad(key,value);

        // In case of switch of files on Change
        $(value).on("change", function () {
            fileuploadOnChange(key,value)
        });

    });


    // Modal for viewing Src file
    var modalContainer = $('<div class="modal fade" id="previewFileModal" tabindex="-1" role="dialog"' +
        ' aria-labelledby="myModalLabel">' + '<div class="modal-dialog" role="dialog">' + ' <div class="modal-content">' +
        '<div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span></button> <h4 class="modal-title" id="myModalLabel">Preview File' +
        '</h4></div> <div class="modal-body"> <div class="form-group"><div style="clear:both"> ' +
        '<iframe id="viewer" frameborder="0" scrolling="no" width="550" height="500"></iframe> </div> </div> </div>' +
        ' <div class="modal-footer"> </div> </div> </div> </div>');
    $('body').append(modalContainer);

// Existing File Available Setup ; PHP push file path from DB in defaultvalue attribute
    function fileUploadOnLoad(key,value) {

        var parentDiv = $(value).parent();

        // capture path of src file from defaultValue attribute create in input div
        var oldFile = $(value).attr("defaultValue");

        // If path does have filename i.e. there must be '.'pdf extension
        if (oldFile != '' && oldFile.search('.') == -1) {
            // alert(oldFile);
            var result = oldFile.split("/");

            // convert existing file type input to text type & put value of file in it
            $(value).attr("type", "text").attr("readonly", "readonly").attr("value", result[result.length - 1])
                .css("width", "80%");

            // sourcing file path into modal Iframe object
            $('#viewer').attr('src', '..' + oldFile.substr(46));

            var previewButton = $('<input id="previewBtn'+key+'" type="button" value="Preview"  data-toggle="modal" ' +
                'data-target="#previewFileModal" class="btn btn-info col-xs-1"/>');
            parentDiv.append(previewButton);


            var changeButton = $('<input id="changeBtn'+key+'" type="button" type="button" value="Change" ' +
                ' class="btn btn-info col-xs-1" />');
            parentDiv.append(changeButton);

            $('#changeBtn'+key).on("click", function () {
                if (confirm("You will loose attached File. Are you sure you want to continue?")) {
                    $('#changeBtn'+key).remove();
                    $('#previewBtn'+key).remove();
                    $(value).attr("type", "file").removeAttr("readonly").css("width", "100%");
                }
            });
        }
    }


    function fileuploadOnChange(key,value) {

        var previewButton = $('<input id="previewBtn'+key+'" type="button" value="Preview"  data-toggle="modal" ' +
            'data-target="#previewFileModal" class="btn btn-info col-xs-1"/>');
        var changeButton = $('<input id="changeBtn'+key+'" type="button" type="button" value="Change" ' +
            ' class="btn btn-info col-xs-1" />');
        var clearButton = $('<input id="clearBtn'+key+'" type="button" value="Remove" ' +
            'class="btn btn-info col-xs-1">');

        var doc, image;
        var filename = $(value).val();
        var extention = $(value).val().substr(filename.lastIndexOf('.') + 1).toLowerCase();
        var allowedext = [$(value).attr("filetype")];

        if (filename.length > 0) {
            if (allowedext.indexOf(extention) !== -1) {
                alert(filename.substr(12) + " is selected.");
                $(value).css("width", "80%");

                // Adding Preview Button
                $(value).parent().append(previewButton);
                $('#previewBtn'+key).on("click", function () {
                    pdffile = document.getElementById($(value).attr("id")).files[0];
                    pdffile_url = URL.createObjectURL(pdffile);
                    $('#viewer').attr('src', pdffile_url);
                });

                //Adding Remove Button
                $(value).parent().append(clearButton);
                $('#clearBtn'+key).on("click", function () {
                    $(value).val('');
                    $(value).css("color", "#555").css("width", "100%");
                    $('#previewBtn'+key).remove();
                    $('#clearBtn'+key).remove();
                });


            } else {
                alert('Invalid file Format. Only ' + allowedext.join(', ') + ' are allowed.');
                $(value).val('');
            }
        }
    }

});

