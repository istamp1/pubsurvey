    <div id="heading">
        <div id="logo">
            <a href=""><img src="http://www.norwichcamra.org.uk/pix/1brlogo.jpg"></a>
        </div>
        <div id="title">
            <div>
                Norwich & Norfolk CAMRA
            </div>
            <div id="title-left">
                Pub Survey
            </div>
            <div id="title-right"><?php echo $stats;?>
            </div> 
        </div>
        <div id="login"> 
            <div id="loginmenu">
                <ul>
                    <li class="loginmenuitem">
                        <a href="./login" target="_top"><?php if( $forename == '') { echo "Log In"; } else { echo $forename.' ('.$branch.')'; } ?></a> 
                    </li>
                    <li class="loginmenuitem">
                        <a class="undec-link" href="http://www.norwichcamra.org.uk/pubdb/GetPubsByLocation.php" target="_blank">Norfolk Pubs |</a>
                    </li>
                    <li class="loginmenuitem">
                        <a class="undec-link" href="http://whatpub.com" target="_blank">What Pub |</a>
                    </li>
                    <li class="loginmenuitem">
                        <a class="undec-link" href="http://www.norwichcamra.org.uk" target="_blank">Branch |</a>
                    </li>
                    <li class="loginmenuitem">
                        <a class="undec-link" href="http://www.camra.org.uk" target="_blank">CAMRA |</a>
                    </li>
                </ul>
            </div>
            <a href="http://www.camra.org.uk" target="_blank"><img style="padding: 10px 0 0 0; float: right;" src="http://www.norwichcamra.org.uk/pubdb/gifs/CAMRAlogoS.gif"></a>
        </div>
    </div>
    <div class="clear"></div>
    
    <div id="MenuContainer"> 
        <ul>
            <li class="MenuItem<?php if ($selected=='Home') { echo 'Selected'; }; ?>"><a href="./home" target="_top">Home</a></li>
            <li class="MenuItem<?php if ($selected=='Pubs') { echo 'Selected'; }; ?>"><a href="./pubs" target="_top">Pubs</a></li>
            <li class="MenuItem<?php if ($selected=='Breweries') { echo 'Selected'; }; ?>"><a href="./breweries" target="_top">Breweries</a></li>
            <li class="MenuItem<?php if ($selected=='Beers') { echo 'Selected'; }; ?>"><a href="./beers" target="_top">Beers</a></li>
        </ul> 
    </div>
    <div class="clear"></div>
