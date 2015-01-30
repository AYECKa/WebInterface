<?php
    require_once('NavbarHelper.php');
    require_once('MibObject.template.php');
    $navbar = new NavBar($mib);
    $mibPageRender = new MibPageRender($navbar->getCurrentMibLocation());
    //get the oid's of the system info
    $sysInfo = array();
    $sysInfo['FPGA'] = $mib->tree->root->getNodeByName('fpgaVersion')->getOid();
    $sysInfo['Software'] = $mib->tree->root->getNodeByName('softwareVersion')->getOid();
    $sysInfo['Firmware'] = $mib->tree->root->getNodeByName('hardwareVersion')->getOid();
    $sysInfo['Serial'] = $mib->tree->root->getNodeByName('serialNumber')->getOid();

?>
<html>
<head>
    <title>Ayecka Web Interface</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-editable.css">
    <script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-editable.js"></script>
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
        $(document).ready(function ()
        {   
            //$.fn.editable.defaults.mode = 'inline';
            loadAllPageData();
        });

        function loadAllPageData()
        {

            $('.editable-link').each(function ()
            {
                var oid = this.attributes['oid'].value;
                
                var loaderId = this.attributes['id'].value + '-loader';
                var textId = this.attributes['id'].value;
                var textBox = $('#' + textId);
                var loader = $('#' + loaderId);
                $.get('ajax/snmpget.php?oid=' + oid,function(data) {
                    textBox.html(data);
                    loader.hide();
                    textBox.editable();
                });

            });

            $('.systemInfo').each(function () {
                var textBox = this;
                var oid = this.attributes['oid'].value;
                $.get('ajax/snmpget.php?oid=' + oid,function(data) {
                    textBox.innerHTML = data;
                });
            });


        }
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
        .dl-horizontal dt {
            text-align: left;
        }
        .dl-horizontal dd {
            margin-right: 0;
        }
        .panel-primary {
            border-color: #222222;
            width: auto;
        }
        .panel-primary > .panel-heading {
            background-color: #222222;
            border-color: #222222;
            color: #FFFFFF;
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
<div class="text-center"><h2>System Information</h2></div>
<div class="row">

    <div class="col-md-3 text-center"></div>
        
        <div class="col-md-3">

            <div class="panel panel-default">
                <label>System Up Time:      <span class="systemInfo" oid="1.3.6.1.2.1.1.3"><img src="img/loading.gif"/></span></label></br>
                <label>System Contact:      <span class="systemInfo" oid="1.3.6.1.2.1.1.4"><img src="img/loading.gif"/></span></label></br>
                <label>System Description:  <span class="systemInfo" oid="1.3.6.1.2.1.1.1"><img src="img/loading.gif"/></span></label></br>
                <label>System Name:         <span class="systemInfo" oid="1.3.6.1.2.1.1.5"><img src="img/loading.gif"/></span></label></br>
                <label>System Location:     <span class="systemInfo" oid="1.3.6.1.2.1.1.6"><img src="img/loading.gif"/></span></label></br>
                <label>System Object ID:    <span class="systemInfo" oid="1.3.6.1.2.1.1.2"><img src="img/loading.gif"/></span></label></br>
                <label>System Services:     <span class="systemInfo" oid="1.3.6.1.2.1.1.7"><img src="img/loading.gif"/></span></label></br>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
            <label>FPGA:                <span class="systemInfo" oid="<?php echo $sysInfo['FPGA']; ?>"><img src="img/loading.gif"/></span></label></br>
            <label>Software:            <span class="systemInfo" oid="<?php echo $sysInfo['Software']; ?>"><img src="img/loading.gif"/></span></label></br>
            <label>Firmware:            <span class="systemInfo" oid="<?php echo $sysInfo['Firmware']; ?>"><img src="img/loading.gif"/></span></label></br>
            <label>Serial:              <span class="systemInfo" oid="<?php echo $sysInfo['Serial']; ?>"><img src="img/loading.gif"/></span></label></br>
            </div>

        </div>

    <div class="col-md-3"></div>
</div>
<hr>
<div class="text-center"><h1><?php echo $navbar->getCurrentCategoryName(); ?></h1></div>
<h1></h1>
<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">

       <?php
            if($mibPageRender->hasPage)
                echo $mibPageRender->render();
            else
                require_once('Home.template.php');
       ?>


    </div>

<div class="col-md-1"></div>
</div>
<div class="container text-left">
    <hr>
    <footer>
        <span class="pull-right">Version Number: 3.0 Beta</span><a href="http://www.ayecka.com">Ayecka </a>Comunnication System
    </footer>
</div>
<!-- /container -->
</body>

</html>