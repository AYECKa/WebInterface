var mibIndex = [];
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

function doHighlight()
{
    if(location.search.split('highlight=').length == 2)
    {
        var oid= decodeURI(location.search.split('highlight=')[1]);
        for(i in mibIndex)
        {
            if(mibIndex[i].oid == oid)
            {
                var text = mibIndex[i].name;
                $("body").highlight(text);
                var element = $("*:contains('" + text+  "'):last");
                $(window).scrollTop(element.offset().top);
            }
        }

    }
}
$(document).ready(function ()
{   
    //$.fn.editable.defaults.mode = 'inline';
    $.toaster({settings:{timeout:2000}})
    loadAllPageData(30);
    loadTable();
    loadSystemInfo();
    $.get('ajax/getmibindex.php', function (response) {
        mibIndex = response;
        doHighlight();
        $('#search-input').typeahead({
            source: response,
            onSelect: function(item)
            {
                console.log(item);
                for(i in mibIndex)
                {
                    if(mibIndex[i].name == item.text)
                    {
                        var oid = mibIndex[i].oid;
                        window.location.href = item.value + "&highlight=" + oid;
                    }

                }
                

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
    if($('.gaugeContainer').length == 0)
    {
        return;
    }
    var gauges = []
    $.get('ajax/getgauges.php', function (response) {
        $('.gaugeContainer').each(function () {

            var id = $(this).attr('id');
            var responseId = id.replace('gaugeContainer', '');
            var config = {};
            var oid = "";
            if(response[responseId] != undefined)
            {
                config = {
                    id: id,
                    min: response[responseId].min,
                    max: response[responseId].max,
                    value: 0,
                    title: response[responseId].label
                };
                oid = response[responseId].oid;
            }
            else
            {
                config = {
                    id: id,
                        min: 1,
                    max: 100,
                    value: 0,
                    title: 'Gauge'
                };
            }
            $("#" + id).attr('oid', oid);
            gauges.push(new JustGage(config));
        });
        startUpdateGauges(1000, gauges);

    });

    $('.gaugeContainer').on('click', function (){
        var val = $(this).attr('id').replace('gaugeContainer' , '');
        var gauge = gauges[val-1];
        console.log(gauge)
        var min = gauge.config.min;
        var max = gauge.config.max;
        var oid = $(this).attr('oid');
        var label = gauge.config.title;
        $('#GaugeOidSearch').val(oid);
        $('#GaugeMin').val(min);
        $('#GaugeMax').val(max);
        $('#gaugeId').val(val);
        $('#GaugeLabel').val(label);
       $('#gaugeModal').modal();
    });
    function getLocationByOid(oid)
    {
        for(i in mibIndex)
        {
            if(mibIndex[i].oid == oid)
                return mibIndex[i].id;
        }
    }
    $('#favRemove').on('click', function () {
        var oid = $('#favOid').val();
        $.get('ajax/setfave.php?oid=' + oid + "&status=0", function () {
        console.log("fave is set");
        location.reload();
        });

    });
    $('#saveFave').on('click', function () {
        var oid = $('#favOid').val();
        var name = $('#favLabel').val();
        $.get('ajax/updatefavlabel.php?oid=' + oid + "&label=" + name, function () {
            console.log("fave is updated");
            location.reload();
        });
    });
    $('.edit').on('click', function () {
        var title = $(this).next().attr('title');
        var editable = $(this).parent().next().children('a');
        var oid = editable.attr('oid');
        var oidName = editable.attr('id');
        var oidLocation = getLocationByOid(oid);
        var url = oidLocation + "&highlight=" + oid;



        $('#favLabel').val(title);
        $('#favGotoOid').attr('href', url);
        $('#favOid').val(oid);
        $('#favName').val(oidName);
        $('#faveEditModal').modal();

    });

    $('#saveGauge').on('click', function () {
        var id = $('#gaugeId').val();

        var gauge = gauges[id-1];
        var elementId = gauge.config.id;
        var jgauge = $('#' + elementId);
        var label = $('#GaugeLabel').val();
        var oid = $('#GaugeOidSearch').val();
        var min = $('#GaugeMin').val();
        var max = $('#GaugeMax').val();
        $('#' + elementId).html('');
        gauges[id - 1] = new JustGage({
            id: elementId,
            min:min,
            max:max,
            value:0,
            title: label
        });


        $(jgauge).attr('oid',oid);
        $('#gaugeModal').modal('hide');
        $.get('ajax/updategauge.php?id=' + id + "&label=" + label + "&oid=" + oid + "&min=" + min + "&max=" + max, function () {
            console.log('Gauge is now updated');
        });

    });

}


function startUpdateGauges(interval, gauges)
{
    setInterval(function () {
        var query=$('#gaugeContainer1').attr('oid') + "," + $('#gaugeContainer2').attr('oid') + "," + $('#gaugeContainer3').attr('oid');

        $.get('ajax/snmpget.php?oids=' + query, function (response) {
            for (key in gauges) {
                var gauge = gauges[key];
                var jgauge = $('#' + gauge.config.id);
                var oid = $(jgauge).attr('oid');
                if(oid !== "") {
                    var value = Number(response.data[oid]);
                    gauge.refresh(value);
                }
            }


        });
    }, interval);
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
        
        var row = [];
        for(var obj in tableData)
        {
            //get parent oid
            var parentOid = obj.split('.');
            parentOid.pop();
            parentOid = parentOid.join('.');


            var parentType = $("th[oid='" + parentOid +"']").attr('type');
            if(tableData[obj].indexOf("Error") !== -1) return;
            var editable = '';
            if(parentType == "LITERAL")
            {
                editable = "<a class=\"editable-link    editable editable-click\"  metaType=\"LITERAL\"      data-type=\"text\"   href=\"#\"  data-pk=\"1\" data-url=\"ajax/snmpset.php\" oid=\"" + obj + "\" data-name=\"" + obj + "\">" + tableData[obj] + "</a>";    
            }
            else
            {
                editable = "<a class=\"editable-options editable\" metaType=\"OPTIONS\" data-type=\"select\" href=\"#\"  data-pk=\"0\" data-url=\"ajax/snmpset.php\" oid=\"" + obj + "\" data-name=\"" + obj + "\" data-options='" + parentType + "' data-val=\"" + tableData[obj] + "\"'></a>"
                    
            }
            
            row.push(editable);
            //template += "<td>" + editable + "</td>";
        }

        //template+="</tr>";
        //$(template).appendTo('.oid-table tbody');
        dataTable.row.add(row).draw();

        $('.editable').each(function () {
            var metaType = $(this).attr('metaType');
            if(metaType == "LITERAL")
            {
                $(this).editable();    
            }
            else
            {
                var value = $(this).attr('data-val');
                var options = JSON.parse($(this).attr('data-options'));
                $(this).editable({
                    value: value,
                    source: options
                });
            }
            
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