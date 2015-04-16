/**
 * Manages the CodeMirror Editor to write 
 * Submits the job into trque QUEUE
 * 
 * @author      Rajesh Mayara <rajesh.mayara@locuz.com>
 * @version     2.0
 * @since       2.0
 */
$(document).ready(function () {
    /**************************************************************************/
    /**********            Create CodeMirror Object Instace          **********/
    var codemirror = CodeMirror.fromTextArea(document.getElementById('codemirror-shell-editor'), {
        mode: 'shell',
        lineNumbers: true,
        matchBrackets: true,
        theme: 'rubyblue'
    });
    /**********     Submit the form to submit the job into QUEUE     **********/
    $('#task-submit-form').on('submit', function () {
        var lineCount = codemirror.lineCount();
        var text = "";
        var strLen = 0;
        $('#codemirror-text').find(':hidden').remove();
        for (var i = 0; i < lineCount; i++) {
            var content = codemirror.getLine(i);
            strLen += content.length;
            if (i == 1) {
                text = content.split(' ');
                text = text[text.length - 1];
            }
        }
        if (strLen > 0) {
            $('#codemirror-text').append('<input type="hidden" name="script-name" value="' + text + '"/>');
            text = codemirror.getValue();
            text = text.replace("\r", "");
            $('#codemirror-text').append('<input type="hidden" name="codemirror-text" value="' + codemirror.getValue() + '"/>');
            return true;
        }
        alert("Please write the script in editor.");
        return false;
    });
    /**************************************************************************/
});
/************           End of the editor.js                     **************/