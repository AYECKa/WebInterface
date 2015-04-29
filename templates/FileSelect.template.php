<!doctype html>
<html>
<head>
    <title>Ayecka Web Interface</title>
    <meta name="viewport" content="width=device-width">
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
    <script type="text/javascript" src="js/fileSelect.js"></script>
    <style type="text/css">
        body {
            padding-top: 80px;
            padding-bottom: 40px;

        }
    </style>
</head>

<body>

<div class="row">
    <div class="col-lg-4"></div>
    <div class="col-lg-4 text-center">
        <div class="well">
            <img src="img/ayeckaLogo.png">
            <form method="post" action="#">
                <input id="host" type="text" placeholder="AYECKa Device host" class="form-control input-sm" name="host" value=""><br>
                <input id="community-read" type="text" placeholder="Community-Read" value="public" class="form-control input-sm" name="community-read"><br>
                <input type="text" placeholder="Community-Write" value="private" class="form-control input-sm" name="community-write"><br>
                <div class="checkbox text-left"><label><input name="use-mock" unchecked type="checkbox"/>Use mock device</label></div>
                <div class="checkbox text-left"><label><input id="manual-mib" unchecked type="checkbox"/>Select Mib Manually</label></div>
                <select id="fileSelect" class="form-control input-sm" disabled>
                    <option selected disabled>Select Mib</option>
                    <?php
                    foreach($mib->getMibFileList() as $file)
                    {
                        echo '<option value="' . $file .'">'. $file .'</option>';
                    }
                    ?>
                </select><br>

                <input id="mib-input" type="hidden" value="" name="mib">
                <input id="submit" type="submit" value="Manage" name="operation" class="btn btn-success btn-lg disabled">
            </form>
            <hr>
            <footer class="text-left">&copy; <a href="http://www.ayecka.com">Ayecka</a> Comunnication System</footer>
        </div>
    </div>
    <div class="col-lg-4"></div>
</div>


<!-- /container -->
</body>

</html>