<ul>
<?php
    foreach( $beers as $beer ) {
        $breweryBeer = $beer['BreweryName'].', '.$beer['BeerName'].' '.$beer['ABV'].' ('.$beer['Pubs'].')';
        $beerID = $beer['Id'];
?>
        <li class="findbeer" id="li<?php echo $beerID; ?>">
	    <?php echo $breweryBeer ?>
            <input type="hidden" name="bis" value="<?php echo $beer['BIS']; ?>" />
            <input type="hidden" name="breweryname" value="<?php echo $beer['BreweryName']; ?>" />
            <input type="hidden" name="beername" value="<?php echo $beer['BeerName']; ?>" />
            <input type="hidden" name="abv" value="<?php echo $beer['ABV']; ?>" />
            <input type="hidden" name="beerstyle" value="<?php echo $beer['BeerStyle']; ?>" />
	</li>
<?php
        }
?>
</ul>
<script>
    // when selecting a beer, populate the Add form fields
    $('.findbeer').click(function() {
        // get the beer details
        var liBeerID = this.id;
        var beerID = liBeerID.substr(2);
	
        // populate the form fields
        $('#beerid').val(beerID);
        $('#breweryname').val($('#' + liBeerID + ' input[name=breweryname]').val());
        $('#beername').val($('#' + liBeerID + ' input[name=beername]').val());
        $('#abv').val($('#' + liBeerID + ' input[name=abv]').val());
        $('#beerstyle').val($('#' + liBeerID + ' input[name=beerstyle]').val());
	// enable / disable depending on whether sourced from BIS
	var isBIS = false;
	if($('#' + liBeerID + ' input[name=bis]').val() == 1) {
	    isBIS = true;
	}
        $('#isbis').val($('#' + liBeerID + ' input[name=bis]').val());
	
	// disable if BIS
	$('#breweryname').prop('disabled', isBIS)
	$('#beername').prop('disabled', isBIS)
	$('#abv').prop('disabled', isBIS)
	$('#beerstyle').prop('disabled', isBIS)
	
	// clear search field and the results
	$('#searchresults').html('');
	
        // focus on the price
        $('#price').focus();
    });
</script>
