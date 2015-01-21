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
                <input type="submit" value="select_file" name="operation" class="btn btn-success btn-lg">
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