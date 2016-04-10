<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="./jquery/global.js"></script>
    <script type="text/javascript" src="./jquery/breweries.js"></script>

    <link rel="stylesheet" type="text/css" href="./style/main.css">
    <title>Pub Survey - Breweries</title>
</head>

<body>

<?php include '../pubsurvey/application/controllers/session.php'; ?>

<div id="container">

    <?php $this->load->view('header_view'
            , array( 'selected' => 'Breweries', 'forename' => $forename, 'branch' => $branch
                   , 'stats' => $stats, 'year' => $year ) ); ?>

    <div id="body">

        <div id="left_content" style="width: 250px">
            <ul>
                <div>
                    <input style="width: 120px" type="text" name="searchbreweryvalue" id="searchbreweryvalue" value="" maxlength="100" size="50" autocomplete="off" />
                </div>
                <?php
                    $class = "breweryitem";
                    foreach( $breweries as $brewery ) {
			$breweryCode = ($brewery['BreweryCode'] == '') ? '' : ' ('.$brewery['BreweryCode'].')';
                        $breweryName = $brewery['BreweryName'];
                        $breweryID = $brewery['Id'];
                        echo '<li class="'.$class.'" id="'.$breweryID.'">'.$breweryName.$breweryCode.'</li>';
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
    $('#searchbreweryvalue').keyup(function() {
        // get the search field value
        var search = this.value;
        // hide any li items which don't match
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
        // prvent form submitting
        return false;
    }); 
</script>

</body>
</html>
