/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var inputFileCounter = 0;
$(document).ready(function () {
    /**************************************************************************/
    /**
     * Get queue list from the torque via ajax. 
     */
    var requestURL = BASE_URL + '/index.php/queue/getQueueList';
    $.post(requestURL, {}, function (response) {
        if (response.status == 100) {
            $('#TaskForm_queue').html('');
            var html = "<option value=\"\">-- Select --</option>";
            $.each(response.response, function (k, v) {
                html += "<option value=\"" + v + "\">" + v + "</option>";
            });
            $('#TaskForm_queue').html(html);
        } else {
            alert(response.message);
        }
    }, 'json');
    /**************************************************************************/
    /**
     * Removing file attribute from the input file stack
     */
    $('body').on('click', '.remove-file', function () {
        $(this).closest('tr').remove();
        return false;
    });
    /**************************************************************************/
    /**
     * Adding file attribute to the input file stack
     */
    $('body').on('click', '.add-file', function () {

        var html = '<tr>' +
                '<td>' +
                '<input id="TaskForm_inputFile' + inputFileCounter + '" type="text" size="40" maxlength="256" name="TaskForm[inputFile][]" readonly="raedonly">' +
                '</td>' +
                '<td>' +
                '<button type="button" name="browse" id="browse' + inputFileCounter + '" class="btn btn-info browse" role="button" title="Open directory browser"><span class="font-icon icon-fa-folder-open"></span></button>' +
                '</td>' +
                '<td>' +
                '<button class="btn btn-danger remove-file" title="Remove"><i class="font-icon icon-fa-minus-sign"></i></button>' +
                '</td>' +
                '</tr>';
        $('#file-item-container').append(html);
        inputFileCounter++;
        return false;
    });
    $('.add-file').trigger('click');
    /**************************************************************************/
    var closestID = "";

    var loadBrowser = function () {
        $('#filebrowser').fileTree({'script': ['/torque/index.php/browser/getlist?dironly=0&remote=1'], 'root': '/', 'folderEvent': 'click', 'expandSpeed': 200, 'collapseSpeed': 200, 'multiFolder': false, 'loadMessage': 'Loading File Browser'}, function (f) {
            $("#filedirpath").val(f);
            $("#btnselect").show();
        }, function (d) {
            $("#filedirpath").val(d);
            $("#btnselect").hide();
        });
    }
    $('body').on('click', '.browse', function () {
        closestID = $(this).closest('tr').find('td:eq(0)').find('input').attr('id');
        loadBrowser();
        $("#filedirpath").val('/');
        $("#filedirdialog").dialog("open");
    });
    $('#filedirdialog').dialog({
        'title': 'Select File',
        'autoOpen': false,
        'modal': true,
        'minWidth': 500,
        'minHeight': 400,
        'buttons': {
            'Select': {
                'text': 'Select',
                'id': 'btnselect',
                'style': 'display:none;',
                'click': function () {
                    $('#' + closestID).val($("#filedirpath").val());
                    $('#' + closestID).attr('title', $("#filedirpath").val());
                    $(this).dialog("close");
                }
            },
            'Cancel': {'text': 'Cancel', 'id': 'btncancel', 'click': function () {
                    $(this).dialog("close");
                }
            }
        },
        'create': function () {
            loadBrowser();
            $("#btnselect").button({icons: {primary: "ui-icon-circle-check"}});
            $("#btncancel").button({icons: {primary: "ui-icon-circle-close"}});
        }
    });

});
