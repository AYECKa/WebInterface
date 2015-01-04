<?php

if(isset($_POST['submit'])){
    if($_POST['device'] != ""){
        echo '<script type="text/javascript">window.location = "'.$_POST['device'].'"</script>';
    }
}
?>
<!doctype html>

<html>

<head>
    <title>Ayecka Web Interface</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>-->
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
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
            <img src="images/ayeckaLogo.png">
            <form method="post">
                <select class="form-control input-sm" name="device">
                    <option selected disabled>Select Device</option>
                    <?php
                    $filename = 'sr1';
                    if (file_exists($filename)) {
                        echo '<option value="sr1">SR1 - Advanced DVB-S2 Receiver with GigE interface</option>';
                    }
                    $filename = 'tc1';
                    if (file_exists($filename)) {
                        echo '<option value="tc1">TC1 - Transport stream Media converter</option>';
                    }

                    $filename = 'st1';
                    if (file_exists($filename)) {
                        echo '<option value="st1">ST1 - Satellite Transmitter, IP over DVB-S2</option>';
                    }

                    $filename = 'aupc';
                    if (file_exists($filename)) {
                        echo '<option value="aupc">AUPC - Automatic Uplink Power Control</option>';
                    }
                    ?>
                </select>
                <br>
                <input type="submit" value="Go" name="submit" class="btn btn-success btn-lg">
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