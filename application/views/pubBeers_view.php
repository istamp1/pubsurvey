<div class="marg-10">
    <div class="col2">
        <p class="main_heading">
        <?php 
            if( $NoThe != 'Y' ) {
                echo 'The ';
            }
            echo $PubName;
        ?>
        &nbsp;<span id="pubid"><?php echo $PubID ?></span> 
        <p class="main_detail" id="norealale">
        <?php echo $norealaleview; ?>
    </div>
    <div class="col2">
        <p class="main_detail aln-r">
        <?php 
            $fullURL = $URL;
            if ( strpos( $fullURL, "http://" ) !== 0) {
                if ( strpos( $fullURL, "www." ) === 0) {
                    $fullURL = "http://".$fullURL;
                } else {
                    $fullURL = "http://www.".$fullURL;
                }
            }
            echo $Address.', '.$Location.', '.$PostCode;
            if ($Telephone != '' ) { echo '<br>'.$Telephone; }
            if ($URL != '') { echo '<br><a href="'.$fullURL.'" target="_new">'.$URL.'</a>'; }
            if ($Email != '') { echo '<br><a href="mailto:'.$Email.'">'.$Email.'</a>'; }
        ?>  
    </div>
    <div class="clear"></div>
    <div class="pubVolunteer">
        <?php
            if ($MemberNo == 0) {
                ?>
                    <form id="pubVolunteerAddForm">
                        <label for="memberno">Volunteer ID:</label>
                        <input type="text" name="memberno" id="memberno" value="" size="5" maxlength="6">
                        <label for="membername">Name:</label>
                        <input type="text" name="membername" id="membername" value="" size="10" maxlength="40">
                        <label for="memberemail">Email:</label>
                        <input type="text" name="memberemail" id="memberemail" value="" size="15" maxlength="60">
                        <button type="submit" value="Submit" style="margin-top: 2px;">Add</button>
                    </form>
                <?php
            } else {
                echo '<p class="aln-r">'.$MemberName.' ('.$MemberNo.') '.$MemberEmail.'&nbsp<button class="deleteVolunteer" id="'.$PubID.'">Delete</button>';
            }
        ?>
	<form class="aln-r" id="pubVolunteerSelectForm">
	    <label style="float:none;" for="selectedmember">Volunteer:</label>
	    <select id="selectedmember" name="selectedmember">
		<?php
		    foreach($members as $member) {
			if($member['MemberNo'] == $MemberNo) {
			    echo '<option value="'.$member['MemberNo'].'" selected>'.$member['MemberName'].'</option>';
			} else {
			    echo '<option value="'.$member['MemberNo'].'">'.$member['MemberName'].'</option>';
			}
		    }
		?> 
	    </select>
	</form>
    </div>
    
    <script>
	$('#pubVolunteerSelectForm').change(function() { 
            var pubid = $('#pubid').text();
            var yr = $('#year').text();
            var memberno = $('#selectedmember').val();
            $.post( './pubs/updatePubVolunteer'
                  , { 'pubid':pubid, 'year':yr, 'memberno':memberno }
                  , function(result) {  
                        // if there is a result, fill the list div and fade it in  
                        if(result) {
                            $('#main_content').html(result);
                            $('#main_content').fadeIn(100); 
                        }
                    });
            // prvent form submitting 
            return false;
	});
	
        $('#pubVolunteerAddForm').submit(function() {
            var pubid = $('#pubid').text();
            var yr = $('#year').text();
            var memberno = $('#memberno').val();
            var membername = $('#membername').val();
            $.post( './pubs/updatePubVolunteer'
                  , { 'pubid':pubid, 'year':yr, 'memberno':memberno, 'membername':membername }
                  , function(result) {  
                        // if there is a result, fill the list div and fade it in  
                        if(result) {
                            $('#main_content').html(result);
                            $('#main_content').fadeIn(100); 
                        }
                    });
            // prvent form submitting 
            return false;
        });
        
        $('.NRA').click(function() {
            var pubid = this.id;
            var yr = $('#year').text();
            var norealale = 1; 
            $.post( './pubs/updatePubVolunteer'
                  , { 'pubid':pubid, 'year':yr, 'norealale':norealale }
                  , function(result) {  
                        // if there is a result, fill the div and fade it in   
                        if(result) {
                            $('#main_content').html(result);
                            $('#main_content').fadeIn(100); 
                        }
                    });
            // prvent form submitting 
            return false;
        });
        
        $('.deleteVolunteer').click(function() {
            var pubid = this.id;
            var yr = $('#year').text();
            var memberno = 0; 
            var membername = '';  
            $.post( './pubs/updatePubVolunteer'
                  , { 'pubid':pubid, 'year':yr, 'memberno':memberno, 'membername':membername }
                  , function(result) {  
                        // if there is a result, fill the list div and fade it in  
                        if(result) {
                            $('#main_content').html(result);
                            $('#main_content').fadeIn(100); 
                        }
                    });
            // prvent form submitting 
            return false;
        });
    </script>
</div>
