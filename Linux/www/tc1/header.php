<div class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">

        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li <?php if($active=="index"){ echo 'class="active"';} ?>> <a href="index.php">Status</a></li>
                <li> | </li>
                <li <?php if($active=="rf1"){ echo 'class="active"';} ?>> <a href="rf.php?rf=1">RF1</a></li>
                <li> | </li>
                <li <?php if($active=="rf2"){ echo 'class="active"';} ?>> <a href="rf.php?rf=2">RF2</a></li>
                <li> | </li>
                <li <?php if($active=="rf_control"){ echo 'class="active"';} ?>> <a href="rf_control.php">RF Control</a></li>
                <li> | </li>
                <li <?php if($active=="egress"){ echo 'class="active"';} ?>> <a href="egress.php">Egress</a></li>
                <li> | </li>
                <li <?php if($active=="network"){ echo 'class="active"';} ?>> <a href="network.php">Network</a></li>
                <li> | </li>
                <li <?php if($active=="images"){ echo 'class="active"';} ?>> <a href="images.php">Images</a></li>
                <li> | </li>
                <li <?php if($active=="system"){ echo 'class="active"';} ?>> <a href="system.php">System</a></li>
                <li> | </li>
                <li> <a href="http://www.ayecka.com/Files/TC1_UserManual_V1.0.pdf" target="_blank">TC1 User Manual</a></li>
            </ul>
        </div>
    </div>
</div>