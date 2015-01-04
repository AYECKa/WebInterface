<div class="well well-sm" style="margin-bottom: 0;">
    <div class="container">
        <div class="col-lg-1"><img src="../images/ayeckaLogo.png" class="pull-left"></div>
        <div class="col-lg-10 text-center">
            <br><h4><strong><a href="http://www.ayecka.com/ST1.html">ST1</a></strong> - Satellite Transmitter, IP over DVB-S2</h4>
        </div>
        <div class="col-lg-1"><img src="../images/slogen.png" class="pull-right"></div>
    </div>
</div>

<div class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">

        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li <?php if($active=="index"){echo 'class="active"';} ?>> <a href="index.php">Status</a></li>
                <li> | </li>
                <li <?php if($active=="tx"){echo 'class="active"';} ?>> <a href="tx.php">TX Configuration</a></li>
                <li> | </li>
                <li <?php if($active=="modulator"){echo 'class="active"';} ?>> <a href="modulator.php">Modulator Configuration</a></li>
                <li> | </li>
                <li <?php if($active=="encapsulator"){echo 'class="active"';} ?>> <a href="encapsulator.php">IP Encapsulator</a></li>
                <li> | </li>
                <li <?php if($active=="buc"){echo 'class="active"';} ?>> <a href="buc.php">BUC Control</a></li>
                <li> | </li>
                <li <?php if($active=="egress"){echo 'class="active"';} ?>> <a href="egress.php">Egress Configuration</a></li>
                <li> | </li>
                <li <?php if($active=="network"){echo 'class="active"';} ?>> <a href="network.php">Network</a></li>
                <li> | </li>
                <li <?php if($active=="vrrp"){echo 'class="active"';} ?>> <a href="vrrp.php">VRRP</a></li>
                <li> | </li>
                <li <?php if($active=="system"){echo 'class="active"';} ?>> <a href="system.php">System</a></li>
                <li> | </li>
                <li <?php if($active=="images"){echo 'class="active"';} ?>> <a href="images.php">Images</a></li>
                <li> | </li>
                <li> <a href="http://www.ayecka.com/Files/ST1_UserManual.pdf" target="_blank">ST1 User Manual</a></li>
            </ul>
        </div>
    </div>
</div>