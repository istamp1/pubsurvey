<!DOCTYPE html>
<?php session_start(); ?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="./jquery/global.js"></script> 
    
    <link rel="stylesheet" type="text/css" href="./style/main.css"> 
    <title>Pub Survey - Login</title>
</head>

<body>
    
<?php include 'session_view.php'; ?>
    
<div id="container">
    
    <?php $this->load->view('header_view', array( 'selected' => 'Login', 'forename' => $forename, 'branch' => $branch ) ); ?>
    
    <div id="body">
        
        <?php  
            if ($xmlString == '') {  
        ?>
            <form name="login" action="./login" method="post">
                <table border=0 summary="">
                    <tr>
                         <td>User Id</td>
                         <td><input type="text" id="uname" name="uname" size="12" maxlength="40"></td>
                    </tr>
                    <tr>
                         <td>Password</td>
                         <td><input type="password" name="password" size="12" maxlength="40"></td>
                    </tr>
                    <tr>
                         <td colspan=2><input type="submit" value="Submit"></td>
                    </tr>
                </table>
            </form> 
        <?php
            } else {         
                echo "<strong>Member:</strong> ".$xml->Forename1." ".$xml->Surname."<br>"; 
                echo "<strong>Branch:</strong> ".$xml->BranchDesc." (".$xml->Branch.")<br>";
                echo "<strong>Address:</strong> ".$xml->Add1." ".$xml->Add2." ".$xml->Add3." ".$xml->Postcode."<br>";
                echo "<strong>Phone: </strong>".$xml->PhoneHome." (home) ".$xml->PhoneWork." (work) ".$xml->PhoneMobile." (mobile)<br>";
                //echo "<strong>Date of Birth: </strong>".$xml->DateOfBirth."<br>";  
            }
        ?>
        
    </div>
    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>
</body>
</html>
