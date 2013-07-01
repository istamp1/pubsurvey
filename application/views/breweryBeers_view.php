<div class="marg-10">
    <div class="col2">
        <p class="main_heading">
        <?php
            echo $BreweryName;
        ?>
    </div>
    <div class="col2">
        <p class="main_detail aln-r">
        <?php
            echo $BLOName;
            echo $BLOEmail;
        ?>
    </div>
    <div class="clear"></div>
    <div class="updateBrewery">
        <form id="breweryUpdateForm">
            <input type="text" name="breweryName" id="breweryName" value="<?php echo $BreweryName; ?>">
            <input type="hidden" name="breweryId" id="breweryId" value="<?php echo $BreweryID; ?>">
            <button type="submit" value="Submit">Update</button>
        </form>
    </div> 
    <div class="mergeBrewery">
        <form id="breweryToMergeForm">
            <select id="breweryToMerge" name="breweryToMerge">
                <?php
                    foreach ( $breweries as $brewery ) {
                        echo '<option value="'.$brewery['BreweryID'].'">'.$brewery['BreweryName'].'</option>';
                    }
                ?>
            </select>
            <input type="hidden" name="breweryId" id="breweryId" value="<?php echo $BreweryID; ?>">
            <button type="submit" value="Submit">Merge</button>
        </form>
    </div> 

    <script>
        $('#breweryToMergeForm').submit(function() {
            var breweryToMerge = $('#breweryToMerge').val();
            var breweryId = $('#breweryId').val();

            $.post( './breweries/mergeBreweries'
                  , { 'breweryId':breweryId, 'breweryToMergeId':breweryToMerge }
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
        
        $('#breweryUpdateForm').submit(function() {
            var breweryId = $('#breweryId').val();
            var breweryName = $('#breweryName').val(); 
            
            $.post( './breweries/updateBrewery'
                  , { 'breweryId':breweryId, 'breweryName':breweryName }
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
