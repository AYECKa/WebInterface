<?php
    require_once('NavbarHelper.php');
    $navbar = new NavBar($mib);
?>
<html>
<head>
    <title>Ayecka Web Interface</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="css/bootstrap.css">
    
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
    <script>
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
    </script>
    <style>
         .dropdown-menu>li
        {   position:relative;
            -webkit-user-select: none; /* Chrome/Safari */        
            -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* IE10+ */
            /* Rules below not implemented in browsers yet */
            -o-user-select: none;
            user-select: none;
            cursor:pointer;
        }
        .dropdown-menu .sub-menu {
            left: 100%;
            position: absolute;
            top: 0;
            display:none;
            margin-top: -1px;
            border-top-left-radius:0;
            border-bottom-left-radius:0;
            border-left-color:#fff;
            box-shadow:none;
        }
        .right-caret:after
         {  content:"";
            border-bottom: 4px solid transparent;
            border-top: 4px solid transparent;
            border-left: 4px solid orange;
            display: inline-block;
            height: 0;
            opacity: 0.8;
            vertical-align: middle;
            width: 0;
            margin-left:5px;
        }
        .left-caret:after
        {   content:"";
            border-bottom: 4px solid transparent;
            border-top: 4px solid transparent;
            border-right: 4px solid orange;
            display: inline-block;
            height: 0;
            opacity: 0.8;
            vertical-align: middle;
            width: 0;
            margin-left:5px;
        }
    </style>

</head>
<body>


<div class="well well-sm" style="margin-bottom: 0;">
    <div class="container">
        <div class="col-lg-1"><img src="img/ayeckaLogo.png" class="pull-left"></div>
        <div class="col-lg-10 text-center">
            <br><h4>AYECKa Web Interface</h4>
        </div>
        <div class="col-lg-1"><img src="img/slogen.png" class="pull-right"></div>
    </div>
</div>

<?php
echo $navbar->render();
?>
<!--PageBody-->
<!--end Page Body-->
<form method="post" class="form-inline">

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