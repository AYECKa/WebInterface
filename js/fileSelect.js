/**
 * Created by assaf on 3/29/15.
 */

var typingTimer;                //timer identifier
var doneTypingInterval = 500;  //time in ms, 5 second for example

$(document).ready(function () {

    $('#host').keyup(function(){
        clearTimeout(typingTimer);
        if ($('#host').val) {
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        }
    });
    $('#fileSelect').change(function () {
        updatedSelectedMib();
    });

    $('#manual-mib').change(function () {
        if($(this).is(":checked")) {

            $('#fileSelect').removeAttr('disabled');
        }
        else {
            $('#fileSelect').attr('disabled', 'true');
        }
    });
});


function updatedSelectedMib()
{
    var selectobject=document.getElementById("fileSelect");
    var selectedString = selectobject.options[selectobject.selectedIndex].value;
    $('#mib-input').attr('value', selectedString);
    console.log('Selected mib is now ' + selectedString);

}
function checkIsIPV4(entry) {
    var blocks = entry.split(".");
    if(blocks.length === 4) {
        return blocks.every(function(block) {
            return parseInt(block,10) >=0 && parseInt(block,10) <= 255;
        });
    }
    return false;
}


function doneTyping () {
    if(checkIsIPV4($('#host').val()))
    {
        $.toaster({settings:{timeout:2000}})
        var query = "ip=" + $('#host').val() + "&community-read=" + $('#community-read').val();
        $.get('ajax/getmibbyip.php?' + query , function (response) {
            console.log(response);
            if(response.didFound)
            {
                var selectobject=document.getElementById("fileSelect");
                for (var i=0; i<selectobject.length; i++){
                    if(selectobject.options[i].text === response.FileName)
                    {
                        selectobject.selectedIndex = i;
                        updatedSelectedMib();
                    }
                }

                $.toaster({ priority : 'success', title : 'Mib Found', message : 'And is now selected'});

            }
            else
            {
                $.toaster({ priority : 'info', title : 'Mib Not found', message : response.reason});
            }


        });
    }

}