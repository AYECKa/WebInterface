<?php
    require_once('NavbarHelper.php');
    require_once('MibObject.template.php');
    $navbar = new NavBar($mib);
    $mibPageRender = new MibPageRender($navbar->getCurrentMibLocation());

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


        <script type="text/javascript" src="js/raphael.2.1.0.min.js"></script>
        <script type="text/javascript" src="js/justgage.1.0.1.js"></script>
        <script type="text/javascript" src="js/mainView.js"></script>
    </head>
    <body>


        <div class="well well-sm" style="margin-bottom: 0;">
            <div class="container">
                <div class="col-lg-1"><a href="index.php"><img src="img/ayeckaLogo.png"></a></div>
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
                        <label>System Up Time:      <span id="sys_up_time" oid="1.3.6.1.2.1.1.3"></span></label></br>
                        <label>System Object ID:    <span><?php echo $_SESSION['SYS_INFO']['OID'];?></span></label></br>
                        <label>System Contact:      <span><?php echo $_SESSION['SYS_INFO']['CONTACT'];?></span></label></br>
                        <label>System Description:  <span><?php echo $_SESSION['SYS_INFO']['DESC'];?></span></label></br>
                        <label>System Name:         <span><?php echo $_SESSION['SYS_INFO']['NAME'];?></span></label></br>
                        <label>System Location:     <span><?php echo $_SESSION['SYS_INFO']['LOCATION'];?></span></label></br>
                        <label>System Services:     <span><?php echo $_SESSION['SYS_INFO']['SERVICES'];?></span></label></br>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <label>FPGA:                <span><?php echo $_SESSION['SYS_INFO']['FPGA'];?></span></label></br>
                        <label>Software:            <span><?php echo $_SESSION['SYS_INFO']['SOFT'];?></span></label></br>
                        <label>Firmware:            <span><?php echo $_SESSION['SYS_INFO']['FRIM'];?></span></label></br>
                        <label>Serial:              <span><?php echo $_SESSION['SYS_INFO']['SERIAL'];?></span></label></br>

                        <label>Remote Device Address:   <span class=""><?php echo $_SESSION['host']; ?></span></label></br>
                        <label>Loaded MIB:              <span class=""><?php echo $mib->getSelectedMibFileName(); ?></span></label></br>
                        <label>Loaded MIB Root:         <span class=""><?php echo $mib->tree->root->children[0]->getOid(); ?></span></label></br>

                    </div>

                </div>

            <div class="col-md-3"></div>

        </div>
        <div class="text-center"><span class="glyphicon glyphicon-star" style="color: #ECC500; font-size: 13px;"></span> - Add to favorite</div>
        <div class="text-center"><span class="glyphicon glyphicon-star-empty" style="color: #ECC500; font-size: 13px;"></span> - Remove from favorite</div>
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