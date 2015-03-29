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
        <link rel="stylesheet" href="css/custom-style.css">
        <link rel="stylesheet" href="css/dataTables.bootstrap.css">
        <link rel="stylesheet" href="css/dataTables.tableTools.css">
        <script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/bootstrap-editable.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/jquery.toaster.js"></script>
        <script type="text/javascript" src="js/dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="js/dataTables.tableTools.js"></script>
        <script type="text/javascript" src="js/bootstrap-typeahead.js"></script>
        <script type="text/javascript" src="js/jquery.highlight.js"></script>
        <script type="text/javascript" src="js/mainView.js"></script>
    </head>
    <body>


        <div class="well well-sm" style="margin-bottom: 0;">
            <div class="container">
                <div class="col-lg-1"><img src="img/ayeckaLogo.png" class="pull-left"></div>
                <div class="col-lg-10 text-center">
                    <br><h4>AYECKa Web Interface</h4>
                    <h5><?php echo $_SESSION['SYS_DESC'];?></h5>
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
                        <label>System Object ID:    <span class="systemInfo" oid="1.3.6.1.2.1.1.2"><img src="img/loading.gif"/></span></label></br>
                        <label>System Contact:      <span class="systemInfo" oid="1.3.6.1.2.1.1.4"><img src="img/loading.gif"/></span></label></br>
                        <label>System Description:  <span class="systemInfo" oid="1.3.6.1.2.1.1.1"><img src="img/loading.gif"/></span></label></br>
                        <label>System Name:         <span class="systemInfo" oid="1.3.6.1.2.1.1.5"><img src="img/loading.gif"/></span></label></br>
                        <label>System Location:     <span class="systemInfo" oid="1.3.6.1.2.1.1.6"><img src="img/loading.gif"/></span></label></br>
                        <label>System Services:     <span class="systemInfo" oid="1.3.6.1.2.1.1.7"><img src="img/loading.gif"/></span></label></br>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <label>FPGA:                <span class="systemInfo" oid="<?php echo $sysInfo['FPGA']; ?>"><img src="img/loading.gif"/></span></label></br>
                        <label>Software:            <span class="systemInfo" oid="<?php echo $sysInfo['Software']; ?>"><img src="img/loading.gif"/></span></label></br>
                        <label>Firmware:            <span class="systemInfo" oid="<?php echo $sysInfo['Firmware']; ?>"><img src="img/loading.gif"/></span></label></br>
                        <label>Serial:              <span class="systemInfo" oid="<?php echo $sysInfo['Serial']; ?>"><img src="img/loading.gif"/></span></label></br>

                        <label>Remote Device Address: <span class=""</span><?php echo $_SESSION['host']; ?></label></br>
                        <label>Loaded MIB:          <span class=""><?php echo $mib->getSelectedMibFileName(); ?></span></label></br>
                        <label>Loaded MIB Root:          <span class=""><?php echo $mib->tree->root->children[0]->getOid(); ?></span></label></br>

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