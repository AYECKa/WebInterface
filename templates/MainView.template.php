<html>

<head>
    <title>Ayecka Web Interface</title>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon" />
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>

</head>
<body>


<div class="well well-sm" style="margin-bottom: 0;">
    <div class="container">
        <div class="col-lg-1"><img src="../images/ayeckaLogo.png" class="pull-left"></div>
        <div class="col-lg-10 text-center">
            <br><h4>AYECKa Web Interface</h4>
        </div>
        <div class="col-lg-1"><img src="../images/slogen.png" class="pull-right"></div>
    </div>
</div>

<div class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">

        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="active"> <a href="index.php">Status</a></li>
                <li> | </li>
                <li> <a href="tx.php">TX Configuration</a></li>
                <li> | </li>
                <li> <a href="modulator.php">Modulator Configuration</a></li>
                <li> | </li>
                <li> <a href="encapsulator.php">IP Encapsulator</a></li>
                <li> | </li>
                <li> <a href="buc.php">BUC Control</a></li>
                <li> | </li>
                <li> <a href="egress.php">Egress Configuration</a></li>
                <li> | </li>
                <li> <a href="network.php">Network</a></li>
                <li> | </li>
                <li> <a href="vrrp.php">VRRP</a></li>
                <li> | </li>
                <li> <a href="system.php">System</a></li>
                <li> | </li>
                <li> <a href="images.php">Images</a></li>
                <li> | </li>
                <li> <a href="http://www.ayecka.com/Files/ST1_UserManual.pdf" target="_blank">User Manual</a></li>
            </ul>
        </div>
    </div>
</div>
<!--PageBody-->
<!--end Page Body-->
<form method="post" class="form-inline">
    <div class="well well-sm text-center">

        <div class="form-group">
            <input type="text" class="form-control input-sm" value="" name="device_IP" placeholder="Device IP Address">
        </div>
        <button type="submit" class="btn btn-success btn-sm" name="read">Read From Device</button>
        <button type="submit" class="btn btn-success btn-sm" name="write">Write To Device</button>
    </div>
    <div class="well well-sm text-center">
        <div class="form-group">FPGA<input type="text" class="form-control input-sm" value="" name="fpgaVersion" readonly></div>
        <div class="form-group">SW<input type="text" class="form-control input-sm" value="" name="softwareVersion" readonly></div>
        <div class="form-group">HW<input type="text" class="form-control input-sm" value="" name="hardwareVersion" readonly></div>
        <div class="form-group">SN<input type="text" class="form-control input-sm" value="" name="serialNumber" readonly></div>
        <div style="margin-top: 10px;">
            <div class="form-group">System Up Time<input type="text" class="form-control input-sm" value="" readonly></div>
            <div class="form-group">System Contact<input type="text" class="form-control input-sm" value="" readonly></div>
            <div class="form-group">System Descr<input type="text" class="form-control input-sm" value="" readonly></div>
            <div class="form-group">System Name<input type="text" class="form-control input-sm" value="" readonly></div>
            <div class="form-group">System Location<input type="text" class="form-control input-sm" value="" readonly></div>
            <div class="form-group">System Object ID<input type="text" class="form-control input-sm" value="" readonly></div>
            <div class="form-group">System Services<input type="text" class="form-control input-sm" value="" readonly></div>
        </div>

    </div>
<hr>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6 text-left">
        <table class="table table-hover table-condensed">
            <tbody>
            <tr>
                <td>Rx Switching Mode</td>
                <td>
                    <label>
                    <input type="radio" value="0" name="rxSwitchMode" checked/>
                        Auto
                    </label>
                </td>
                <td>
                    <label>
                        <input type="radio" value="1" name="rxSwitchMode"  checked />
                        Manual
                    </label>
                </td>
                <td>
                    Switch Period
                    <div class="form-group">
                        <input class="form-control input-sm" type="number" name="rxSwitchPeriod" max="120" min="0" step="1" value="">
                    </div>sec
                </td>
            </tr>
            <tr>
                <td>Active Rx Channel</td>
                <td>
                    <label>
                        <input type="radio" value="1" name="rxActive" >
                        Rx1
                    </label>
                </td>
                <td>
                    <label>
                        <input type="radio" value="2" name="rxActive">
                        Rx2
                    </label>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Rx Channel Operation Mode</td>
                <td>
                    <label>
                        <input type="radio" value="0" name="rxChannelOpMode">
                        Single
                    </label>
                </td>
                <td>
                    <label>
                        <input type="radio" value="1" name="rxChannelOpMode">
                        Dual
                    </label>
                </td>
                <td></td>
            </tr>


            </tbody>
        </table>
    </div>

</form>
<div class="col-md-3"></div>
</div>
<div class="container text-left">
    <hr>
    <footer>
        <span class="pull-right">Version Number: 1</span><a href="http://www.ayecka.com">Ayecka </a>Comunnication System
    </footer>
</div>
</form>
<!-- /container -->
</body>

</html>