$(function(){
    $(".navbarmenu-items > li > a.trigger").on("click",function(e){
        var current=$(this).next();
        var grandparent=$(this).parent().parent();
        if($(this).hasClass('left-caret')||$(this).hasClass('right-caret'))
            $(this).toggleClass('right-caret left-caret');
        grandparent.find('.left-caret').not(this).toggleClass('right-caret left-caret');
        grandparent.find(".sub-menu:visible").not(current).hide();
        current.toggle();
        current.toggleClass('dropdown-active-now')
        e.stopPropagation();
    });
    $(".navbarmenu-items > li > a:not(.trigger)").on("click",function(){
        var root=$('.dropdown-active-now');
        root.hide();
    });

});
$(document).ready(function ()
{   
    //$.fn.editable.defaults.mode = 'inline';
    loadAllPageData(30);
    loadTable();
    loadSystemInfo();
});

function loadTable()
{
    var tableCols = [];
    $('.oid-table td').each(function () {
        var oid = this.getAttribute('oid');
        tableCols.push(oid);
    });

    fetchTable(tableCols);
}

var currentRow = 0;
function fetchTable(tableCols)
{
    currentRow++;
    fetchTableRow(tableCols,currentRow, fetchTable, tableCols,10);
}

function fetchTableRow(tableCols, rowIndex ,finishCallback, callbackParam, rowIndexLimit)
{
    if(rowIndex >= rowIndexLimit) return;
    var request = "";
    for(var i=0;i<tableCols.length;i++)
    {
        request += tableCols[i] + "." + rowIndex + ",";
    }
    query = request.slice(0, -1); //removes the comma in the end of the query
    $.get('ajax/snmpget.php?oids=' + query, function (response) {
        var tableData = response.data;
        var template = "";
        template+="<tr>";
        for(var obj in tableData)
        {
            if(tableData[obj].indexOf("Error") !== -1) return;
            var editable = "<a class=\"editable-link editable editable-click\" href=\"#\" oid=\"" + obj + "\">" + tableData[obj] + "</a>";
            template += "<td>" + editable + "</td>";
        }
        template+="</tr>";
        $('.oid-table tr:last').after(template)
        $('.oid-table .editable').each(function () {
            $(this).editable();
        });
        if(finishCallback)
            finishCallback(callbackParam);
    });
}

function loadAllPageData(chunkSize)
{
    var controlQueue = [];

    $('.editable-link').each(function ()
    {
        var oid = this.attributes['oid'].value;
        
        var loaderId = this.attributes['id'].value + '-loader';
        var textId = this.attributes['id'].value;
        var textBox = $('#' + textId);
        var loader = $('#' + loaderId);

        var dataHolder = {
            oid: oid,
            control: textBox,
            loader: loader
        };
        controlQueue[oid]=dataHolder;
    });

    var i=1;
    var query = ""
    for(var key in controlQueue)
    {
        query += controlQueue[key].oid + ",";
        if(i !== 0 && i%chunkSize == 0)
        {
            
            executeQuery(controlQueue, query);
            query = "";
        }
        i++;
    }
    if(query!=="") executeQuery(controlQueue, query);
}

function executeQuery(controlQueue,query)
{ 
    query = query.slice(0, -1); //removes the comma in the end of the query
   $.get('ajax/snmpget.php?oids=' + query, function (response) {
        for(var key in response.data)
        {
            if(controlQueue[key] === undefined || controlQueue[key].control === undefined)
            {
                console.log('Unexpected index ' + key);
                continue;
            }
            controlQueue[key].control.html(response.data[key])
            controlQueue[key].control.editable();
            controlQueue[key].loader.hide();
        }
    });
}

function loadSystemInfo()
{
    var controlQueue = [];
    query = "";
    $('.systemInfo').each(function () {
        var textBox = this;
        var oid = this.attributes['oid'].value;
        controlQueue[oid] = textBox;
        query += oid + ",";
    });
    query = query.slice(0, -1);
    $.get('ajax/snmpget.php?oids=' + query, function (response) {
        for(var key in response.data)
        {
            controlQueue[key].innerHTML = response.data[key];
        }
    });
}