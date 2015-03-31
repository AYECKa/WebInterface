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
    $.toaster({settings:{timeout:2000}})
    loadAllPageData(30);
    loadTable();
    loadSystemInfo();
    $.get('ajax/getmibindex.php', function (response) {
        $('#search-input').typeahead({
            source: response,
            onSelect: function(item)
            {
                console.log(item);
                window.location.href = item.value + "&highlight=" + item.text;

            }
        });
        $('#GaugeOidSearch').typeahead({
            source: response,
            onSelect: function(item)
            {
                for(key in response)
                {
                    if(response[key].id === item.value)
                    {
                        var oid = response[key].oid;
                        setTimeout(function () {
                            $('#GaugeOidSearch').val(oid);
                        }, 500);


                    }
                }

            }
        });
    });

    if(location.search.split('highlight=').length == 2)
    {
        var text= decodeURI(location.search.split('highlight=')[1]);
        $("body").highlight(text);
        var element = $("*:contains('" + text+  "'):last");
        $(window).scrollTop(element.offset().top);
    }

    $(document).keydown(function (e) {
        if(e.ctrlKey && e.keyCode == 'F'.charCodeAt(0)){
            e.preventDefault();
            $('#search-input').focus();

        }
    });

    handleFaves();
    handleGauges();

});


function handleGauges()
{
    var gauges = $('.gaugeContainer').jqxGauge({
        min: 0,
        max: 220,
        ticksMinor: { interval: 5, size: '5%' },
        ticksMajor: { interval: 10, size: '9%' },
        value: 0,
        colorScheme: 'scheme03',
        animationDuration: 1200
    });

    $('.gaugeContainer').on('click', function (){
        var val = $(this).attr('id').replace('gaugeContainer' , '');
        var gauge = gauges[val-1];
        var min = $(gauge).jqxGauge('min');
        var max = $(gauge).jqxGauge('max');
        var oid = $(gauge).attr('oid');
        $('#GaugeOidSearch').val(oid);
        $('#GaugeMin').val(min);
        $('#GaugeMax').val(max);
        $('#gaugeId').val(val);
       $('#gaugeModal').modal();
    });

    $('#saveGauge').on('click', function () {
        var id = $('#gaugeId').val();
        var gauge = $('#gaugeContainer' + id);
        var oid = $('#GaugeOidSearch').val();
        var min = $('#GaugeMin').val();
        var max = $('#GaugeMax').val();

        $(gauge).jqxGauge({min: min, max: max});
        $(gauge).attr('oid',oid);
        $('#gaugeModal').modal('hide');

    });

    setInterval(function () {
        var query=$('#gaugeContainer1').attr('oid') + "," + $('#gaugeContainer2').attr('oid') + "," + $('#gaugeContainer3').attr('oid');

            $.get('ajax/snmpget.php?oids=' + query, function (response) {
            $('.gaugeContainer').each(function(){
                var oid = $(this).attr('oid');
                var value = response.data[oid];
                $(this).jqxGauge({value: value});

            });

        });
    }, 1000);







}

function handleFaves()
{
    $(".favorite").click(function() {
        var oid = $(this).attr('oid');
        var status = false;
       if($(this).hasClass('glyphicon-star'))
       {
           $(this).removeClass('glyphicon-star');
           $(this).addClass('glyphicon-star-empty');
           status = false;
       }
       else
       {
           $(this).removeClass('glyphicon-star-empty');
           $(this).addClass('glyphicon-star');
           status = true;
       }
        setFave(oid, status);
    });
}

function setFave(oid, status)
{
    $.get('ajax/setfave.php?oid=' + oid + "&status=" + status, function () {
        console.log("fave is set");
    });
}

function loadTable()
{

    var clipboard = [];


    var tableCols = [];
    $('.oid-table th').each(function () {
        var oid = this.getAttribute('oid');
        tableCols.push(oid);
    });
    var dataTable = $('#oid-table').DataTable({
        "dom": 'T<"clear">lfrtip',
        "tableTools": {
            "aButtons": [
                {
                    "sExtends":    "text",
                    "sButtonText": "Copy record",
                    "fnClick": function () {
                        if(!dataTable.$('tr').hasClass('selected'))
                        {
                            $.toaster({ priority : 'info', title : 'Could not copy record', message : 'Please select a record'});
                            return;
                        }
                        clipboard = [];
                        dataTable.$('tr.selected').children().each(function () {
                          clipboard.push($(this));
                        });
                        $.toaster({ priority : 'success', title : 'Success', message : 'Record is now in clipboard'});
                    }
                },
                {
                    "sExtends":    "text",
                    "sButtonText": "Paste record",
                    "fnClick" : function () {
                        if(clipboard.length == 0)
                        {
                            $.toaster({ priority : 'info', title : 'Error', message : 'Please copy a record first'});
                            return;
                        }
                        if(!dataTable.$('tr').hasClass('selected'))
                        {
                            $.toaster({ priority : 'info', title : 'Could not paste record', message : 'Please select a record'});
                            return;
                        }
                        dataTable.$('tr.selected').find('.editable').each(function (index) {

                            var element = clipboard[index];
                            $(this).editable('setValue', element.text());
                            $(this).editable('submit');
                        });




                    }
                }
            ]
        }

    });

    $('#oid-table tbody').on('click','tr', function () {
       if( $(this).hasClass('selected'))
       {
           $(this).removeClass('selected');
       }
       else
       {
            dataTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
       }
    });

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