<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en"> 
<head>
    <meta charset="utf-8">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="./jquery/global.js"></script>
    <script type="text/javascript" src="./jquery/beers.js"></script>

    <link rel="stylesheet" type="text/css" href="./style/main.css">
    <title>Pub Survey - Beers</title>
</head>

<body>

<?php include '../pubsurvey/application/controllers/session.php'; ?>

<div id="container">

    <?php $this->load->view('header_view'
            , array( 'selected' => 'Beers', 'forename' => $forename, 'branch' => $branch
                   , 'stats' => $stats, 'year' => $year ) ); 
    ?>

    <div id="body">
	<span id="beerId" style="display: none"><?php echo $beerid; ?></span>

        <div id="left_content">
            <div>
                <input style="width: 120px" type="text" name="searchbeervalue" id="searchbeervalue" value="" maxlength="100" size="50" autocomplete="off" />
            </div>
            <ul>
                <?php
                    $lastBrewery = "";
                    foreach( $beers as $beer ) {
                        if ($beer['BreweryName'] != $lastBrewery) {
                            if ($lastBrewery != "") {
								echo '</div>';
                            }
                            echo '<div class="brewery" style="width: 120px">';
                            $lastBrewery = $beer['BreweryName'];
                            echo '<strong>'.$lastBrewery.'</strong>';
                        }
                        $beerName = $beer['BeerName'];
                        $beerID = $beer['Id'];
                        $class = "beeritem";
                        echo '<li class="'.$class.'" id="'.$beerID.'">'.$beerName.'</li>';
                    }
                ?>
            </ul>
        </div>

        <div id="main_content">
        </div>

        <div id="right_content">
        </div>

        <div class="clear"></div>

    </div>
    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

<script>
    $('#searchbeervalue').keyup(function() {
        $( "#left_content .brewery" ).show(); 
        $( "#left_content li" ).show(); 
        // get the search field value
        var search = this.value;
        // hide any beer li items which don't match
        var str = '';
        $( "#left_content li" ).each(function() {
               str = $(this).text();
               if (str.toLowerCase().indexOf(search.toLowerCase()) >= 0) {
                   $(this).show();
               } else {
                   $(this).hide();
               }
            }
        ); 
        $( "#left_content .brewery" ).each(function() {  
				if($(this).find('li:hidden').length  == $(this).find('li').length) {
					$(this).hide();
				} else {
					$(this).show();
				}
			} 
		);
        // prvent form submitting
        return false;
    });
</script>

</body>
</html>
