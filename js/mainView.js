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
    $('.oid-table th').each(function () {
        var oid = this.getAttribute('oid');
        tableCols.push(oid);
    });
    var dataTable = $('#oid-table').DataTable();

    if(tableCols.length > 0)
    {
        fetchTable(dataTable, tableCols);
    }

}

var currentRow = 0;
function fetchTable(dataTable, tableCols)
{
    currentRow++;
    fetchTableRow(dataTable, tableCols,currentRow, fetchTable, tableCols,513);
}

function fetchTableRow(dataTable,tableCols, rowIndex ,finishCallback, callbackParam, rowIndexLimit)
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
        //template+="<tr>";
        var row = [];
        for(var obj in tableData)
        {
            if(tableData[obj].indexOf("Error") !== -1) return;
            var editable = "<a class=\"editable-link editable editable-click\" data-type=\"text\" href=\"#\" data-pk=\"1\" data-url=\"ajax/snmpset.php\" oid=\"" + obj + "\" data-name=\"" + obj + "\">" + tableData[obj] + "</a>";
            row.push(editable);
            //template += "<td>" + editable + "</td>";
        }

        //template+="</tr>";
        //$(template).appendTo('.oid-table tbody');
        dataTable.row.add(row).draw();

        $('.editable').each(function () {
            $(this).editable();
        });
        if(finishCallback)
            finishCallback(dataTable, callbackParam);
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
            controlType:'TEXT',
            oid: oid,
            control: textBox,
            loader: loader
        };
        controlQueue[oid]=dataHolder;
    });
    $('.editable-options').each(function ()
    {
        var oid = this.attributes['oid'].value;

        var loaderId = this.attributes['id'].value + '-loader';
        var textId = this.attributes['id'].value;
        var textBox = $('#' + textId);
        var loader = $('#' + loaderId);
        var options = JSON.parse(this.attributes['data-options'].value);
        var dataHolder = {
            controlType:'OPTIONS',
            oid: oid,
            control: textBox,
            loader: loader,
            options: options
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
            var responseData = response.data[key];

            if(controlQueue[key].controlType === 'TEXT')
            {
                controlQueue[key].control.html(responseData);
                controlQueue[key].control.editable();
            }
            else if(controlQueue[key].controlType == 'OPTIONS')
            {
                controlQueue[key].control.html('');
                var options = controlQueue[key].options;
                controlQueue[key].control.editable({
                    value: responseData,
                    source: options
                });
            }

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