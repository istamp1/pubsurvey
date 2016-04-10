<!DOCTYPE html>
<?php // session_start(); ?>
<html lang="en">
<head>
    <meta charset="utf-8">    
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="./jquery/global.js"></script> 
    
    <link rel="stylesheet" type="text/css" href="./style/main.css">    
    <title>Pub Survey</title>
</head>

<body>
    
<?php include '../pubsurvey/application/controllers/session.php'; ?>
    
<div id="container">
    
    <?php $this->load->view('header_view', array( 'selected' => 'Home', 'forename' => $forename, 'branch' => $branch, 'year' => $year ) ); ?>
    
    <div id="body">
        
        <div id="left_content">
        </div>
        
        <div id="main_content">
            <h1>Welcome!</h1>
            <p class="normal">Welcome to the Norwich & Norfolk CAMRA's Pub Survey website! 
                The 4th annual survey will take place on Saturday, August 2nd, when we hope to beat last year's 
                score of 259 different beers. <br>
            <p class="normal">On the day, we'll visit as many as possible of the 150+ pubs in Norwich to
                discover which beers and ciders are on sale. The data will  
                be entered using this website during the day - you'll see the running totals above.<br>
            <h1>Area covered</h1>
            <p class="normal">The survey covers the city centre and suburbs, postcodes NR1 - NR7 plus 
                a few pubs in other postcodes but which would generally considered to be in Norwich.<br>
            <h1>UK Capital of Ale</h1>
            <p class="normal">This title was initially claimed by Sheffield a few years ago, and is also 
                claimed by both Derby and Nottingham. We claimed it for Norwich after our first survey in 2011,
                when we found 215 different real ales on sale in the City. This was about 30 less than 
                Sheffield, but given that Norwich is only a third of the size of Sheffield we felt 
                justified in making the claim!<br>
        </div>
        
        <div id="right_content" style="width: 275px;" >
            <a class="twitter-timeline" data-dnt="true" href="https://twitter.com/ian_stamp" data-widget-id="320918873316663296">Tweets by @NrwichPubSurvey</a>
            <script>
                !function(d,s,id){
                    var js,fjs=d.getElementsByTagName(s)[0];
                    if(!d.getElementById(id)) { 
                        js=d.createElement(s);
                        js.id=id;
                        js.src="//platform.twitter.com/widgets.js";
                        fjs.parentNode.insertBefore(js,fjs);
                    }
                }
                (document,"script","twitter-wjs");
            </script>
        </div>
        
        <div class="clear"></div>
        
    </div>
    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>
</body>
</html>