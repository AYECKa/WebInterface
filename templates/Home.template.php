<center><h1>Welcome!</h1></center>
<center><h4>Gauges:</h4></center>
<div id="gaugeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Gauge Configurations</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="search">Parameter Name:</label>
                    <input type="email" class="form-control" id="GaugeOidSearch" placeholder="Search">
                </div>
                <div class="form-group">
                    <label for="search">Minimum Value</label>
                    <input type="email" class="form-control" id="GaugeMin" placeholder="Minimum Value">
                </div>
                <div class="form-group">
                    <label for="search">Maximum Value</label>
                    <input type="email" class="form-control" id="GaugeMax" placeholder="Maximum Value">
                </div>
                <input type="hidden" id="gaugeId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveGauge">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div id="gauges">
    <div class="row">
        <div class="col-sm-4">
            <div oid="" id="gaugeContainer1" class="gaugeContainer"></div>
            <div id="gaugeValue1"     class="gaugeValue" >0</div>
        </div>
        <div class="col-sm-4">
            <div oid="" id="gaugeContainer2" class="gaugeContainer"></div>
            <div id="gaugeValue2"     class="gaugeValue" >0</div>
        </div>
        <div class="col-sm-4">
            <div oid="" id="gaugeContainer3" class="gaugeContainer"></div>
            <div id="gaugeValue3"     class="gaugeValue" >0</div>
        </div>
    </div>
</div>
<center><h4>Favorites:</h4></center>
<?php
$mibPageRender = new MibPageRender($fav->getFavorites());
echo $mibPageRender->render();