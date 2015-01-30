<!doctype html>
<html>
<head>
    <title>Ayecka Web Interface</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>-->
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
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
                <input type="text" placeholder="AYECKa Device host" class="form-control input-sm" name="host"><br>
                <input type="text" placeholder="Community-Read" value="public" class="form-control input-sm" name="community-read"><br>
                <input type="text" placeholder="Community-Write" value="private" class="form-control input-sm" name="community-write"><br>
                <div class="checkbox text-left"><label><input name="use-mock" checked type="checkbox"/>Use mock device</label></div>
                <select class="form-control input-sm" name="mib">
                    <option selected disabled>Select Mib</option>
                    <?php
                        foreach($mib->getMibFileList() as $file)
                        {
                            echo '<option value="' . $file .'">'. $file .'</option>';
                        }
                    ?>
                </select>
                <br>
                <input type="submit" value="Manage" name="operation" class="btn btn-success btn-lg">
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