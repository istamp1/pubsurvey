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
    <div class="mergeBrewery">
        <form id="breweryToMergeForm">
            <label for="breweryToMerge">Brewery to merge:</label>
            <input type="text" name="breweryToMerge" id="breweryToMerge" value="" size="5" maxlength="6"> 
            <input type="text" name="breweryId" id="breweryId" value="<?php echo $BreweryID; ?>"> 
            <button type="submit" value="Submit">Merge</button>
        </form> 
    </div>
    
    <script>
        $('#breweryToMergeForm').submit(function() {
            var breweryToMerge = $('#breweryToMerge').val(); 
            alert(breweryToMerge);
            var breweryId = $('#breweryId').val(); 
            alert(breweryId);
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
    </script>
</div>
