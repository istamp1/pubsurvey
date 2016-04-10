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
    
<?php include '../pubsurvey/application/controllers/session.php'; ?>
    
<div id="container">

    <?php $this->load->view('header_view'
            , array( 'selected' => 'Beers', 'forename' => $forename, 'branch' => $branch
                   , 'stats' => $stats, 'year' => $year ) ); ?>
    
    <div id="body">
        
        <?php  
//			var_dump($xmlString);
            if ($xmlString == ''
			|| (is_array($xmlString) && (!$xmlString['checkSuccessful'] || !$xmlString['validLogin']))
			) {
				if(is_array($xmlString)) {
					if(!$xmlString['checkSuccessful']) {
						echo "Login check failed - please try again<br>";
					}
					if(!$xmlString['validLogin']) {
						echo "Login invalid - please try again on the <a href='https://members.camra.org.uk/web/guest'>CAMRA Members Website</a> 
							or reset your password <a href='http://password.camra.org.uk/'>here</a><br>";
					}
				}
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
				if($xmlString['checkSuccessful'] && $xmlString['validLogin']) {
					echo "<strong>Login Succesful</strong><br>"; 
					echo "<strong>Membership Number:</strong> ".$xmlString['membershipNumber']."<br>"; 
					echo "<strong>Name:</strong> ".$xmlString['forename']." ".$xmlString['surname']."<br>"; 
					echo "<strong>Branch:</strong> ".$xmlString['branch']."<br>";
					echo "<strong>Email: </strong>".$xmlString['email']."<br>";  
					echo '<br><p><a href="logoff">Log off</a>';
				} else {
					
				}
            }
        ?>
        
    </div>
    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>
</body>
</html>
