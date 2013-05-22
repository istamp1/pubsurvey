<div class="marg-10">
    <div class="col2">
        <p class="main_heading">
        <?php 
            if( $NoThe != 'Y' ) {
                echo 'The ';
            }
            echo $PubName;
        ?>
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
                        <button type="submit" value="Submit">Add</button>
                    </form>
                <?php
            } else {
                echo '<p class="aln-r">'.$MemberName.' ('.$MemberNo.')&nbsp<button class="deleteVolunteer" id="'.$PubID.'">Delete</button>';
            }
        ?>
    </div>
    
    <script>
        $('#pubVolunteerAddForm').submit(function() {
            var pubid = $('#pubid').val();
            var memberno = $('#memberno').val();
            var membername = $('#membername').val();
            $.post( './pubs/updatePubVolunteer'
                  , { 'pubid':pubid, 'memberno':memberno, 'membername':membername }
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
            var norealale = 1; 
            $.post( './pubs/updatePubVolunteer'
                  , { 'pubid':pubid, 'norealale':norealale }
                  , function(result) {  
                        // if there is a result, fill the div and fade it in   
                        alert(result);
                        if(result) {
                            $('#norealale').html(result);
                            $('#norealale').fadeIn(100); 
                        }
                    });
            // prvent form submitting 
            return false;
        });
        
        $('.deleteVolunteer').click(function() {
            var pubid = this.id;
            var memberno = 0; 
            var membername = '';  
            $.post( './pubs/updatePubVolunteer'
                  , { 'pubid':pubid, 'memberno':memberno, 'membername':membername }
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
