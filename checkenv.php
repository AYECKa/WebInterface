<?php
$death_reason = "";
if(!function_exists('snmp2_set'))
	$death_reason = "the SNMP extention is not installed properlly";
if(!function_exists('mysql_connect'))
	$death_reason = "the mysql extention is not installed properlly";


if($death_reason != "")
{
	die_gracefully($death_reason);
}
?>

<?php
function die_gracefully($reason)
{
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
            <h1>Oops...</h1>
            <h3>We couldn't start the Web Interface because</h3>
            <h4><?php echo $reason; ?></h4>
            <hr>
            <footer class="text-left">&copy; <a href="http://www.ayecka.com">Ayecka</a> Comunnication System</footer>
        </div>
    </div>
    <div class="col-lg-4"></div>
</div>
<?php die(); } ?>