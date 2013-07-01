<div class="marg-10">
    <div class="col2">
        <p class="main_heading">
        <?php
            echo $BeerName;
        ?>
    </div>
    <div class="col2">
        <p class="main_detail aln-r">
        <?php
            echo $ABV.' % '.$StyleDescription;
        ?>
    </div>
    <div class="clear"></div>
    <div class="updateBeer">
        <form id="beerUpdateForm">
            <input type="text" name="beerName" id="beerName" value="<?php echo $BeerName; ?>">
            <input type="hidden" name="beerId" id="beerId" value="<?php echo $BeerID; ?>">
            <button type="submit" value="Submit">Update</button>
        </form>
    </div> 
</div>

    <script>
        $('#beerUpdateForm').submit(function() {
            var beerId = $('#beerId').val();
            var beerName = $('#beerName').val(); 
            
            $.post( './beers/updateBeer'
                  , { 'beerId':beerId, 'beerName':beerName }
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
