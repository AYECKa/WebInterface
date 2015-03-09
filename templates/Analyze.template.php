<html>
    <head>
        <title>Ayecka Web Interface</title>
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/bootstrap-editable.css">
        <link rel="stylesheet" href="css/custom-style.css">
        <link rel="stylesheet" href="css/dataTables.bootstrap.css">
        <script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/bootstrap-editable.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="js/mainView.js"></script>
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
        <div class="text-center">
        	<img src="img/analyze.gif" width="300px"/></br>
        	<h4>Analyzing MIBs</h4>
        	<h6 id="wait">please wait</h6>
        	<script>
        		var number = 0;

        		setInterval(function () 
        		{
        			number = ((number + 1) % 4)
        			var dots = "";
        			for(var i=0;i<number;i++) dots+=".";
        			$('#wait').html('please wait' + dots);
        		},1000);
        	</script>
        </div>
    </body>