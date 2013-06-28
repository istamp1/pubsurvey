<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="./jquery/global.js"></script>
    <script type="text/javascript" src="./jquery/pubs.js"></script>
    <link rel="stylesheet" type="text/css" href="./style/main.css">
    <title>Pub Survey - Pubs</title>
</head>

<body>

<?php include 'session_view.php'; ?>

<div id="container">

    <?php $this->load->view('header_view'
            , array( 'selected' => 'Pubs', 'forename' => $forename, 'branch' => $branch
                   , 'stats' => $stats ) ); ?>

    <div id="body">

        <div id="left_content">
            <ul>
                <div>
                    <input style="width: 120px" type="text" name="searchpubvalue" id="searchpubvalue" value="" maxlength="100" size="50" autocomplete="off" />
                </div>
                <?php
                    foreach( $pubs as $pub ) {
                        $pubName = $pub['PubName'];
                        $pubID = $pub['PubID'];
                        if ( $pub['PubNameQualifier'] != '' ) {
                            $pubName .= ' ('.$pub['PubNameQualifier'].')';
                        }
                        if ( $pub['BeerCount'] > 0 ) {
                            $class = 'pubitem realale';
                        } else {
                            if ( $pub['NoRealAle'] ) {
                                $class = 'pubitem norealale';
                            } else {
                                $class = 'pubitem unsurveyed';
                            }
                        }
                        echo '<li class="'.$class.'" id="'.$pubID.'">'.$pubName.'</li>';
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
        $('#searchpubvalue').keyup(function() {
        // get the search field value
        var search = this.value;
        // hide any pub li items which don't match
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