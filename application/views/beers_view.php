<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="./jquery/global.js"></script> 
    
    <link rel="stylesheet" type="text/css" href="./style/main.css"> 
    <title>Pub Survey - Beers</title>    
</head>

<body>
    
<?php include 'session_view.php'; ?>
    
<div id="container">
    
    <?php $this->load->view('header_view', array( 'selected' => 'Beers', 'forename' => $forename, 'branch' => $branch ) ); ?>

    <div id="body">
        
        <div id="left_content"> 
        </div>
        
        <div id="main_content">
        </div>
        
        <div id="right_content">
        </div>
        
        <div class="clear"></div>

    </div>
    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>
</body>
</html>
