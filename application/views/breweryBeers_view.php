<div class="marg-10">
    <div>
        <p class="main_heading">
        <?php
	    if($BreweryCode != '') {
		$BreweryCode = " ($BreweryCode)";
	    }
            echo $BreweryName.$BreweryCode;
        ?>
    </div>
    <div class="col2">
        <p class="main_detail aln-r">
        <?php
        ?>
    </div>
    <div class="clear"></div>
    <div class="mergeBrewery">
        <form id="breweryToMergeForm">
            <select id="breweryToMerge" name="breweryToMerge">
                <?php
                    foreach ( $breweries as $brewery ) {
			$breweryCode = ($brewery['BreweryCode'] == '') ? '' : ' ('.$brewery['BreweryCode'].')';
                        echo '<option value="'.$brewery['Id'].'">'.$brewery['BreweryName'].$breweryCode.'</option>';
                    }
                ?>
            </select>
            <input type="hidden" name="breweryId" id="breweryId" value="<?php echo $Id; ?>">
            <button type="submit" value="Submit">Merge</button>
        </form>
    </div>

<script>
    $('#breweryToMergeForm').submit(function() {
	var breweryToMergeId = $('#breweryToMerge').val();
	var breweryId = $('#breweryId').val(); 

	$.post( './breweries/mergeBreweries'
	      , { 'breweryId':breweryId, 'breweryToMergeId':breweryToMergeId }
	      , function(result) {
		    // if there is a result, fill the list div and fade it in
		    if(result) {
			var liToRemove = '#' + breweryToMergeId;
			$(liToRemove).remove()
			$('#main_content').html(result);
			$('#main_content').fadeIn(100);
		    }
		});
	// prvent form submitting
	return false;
    });
    
    $('.brewerybeer').click(function() {
	// strip 'beer' from beerid
	var beerId = this.id.substr(4); 
	var yr = $('#year').text(); 
	// POST data and switch to /beers
	post( './beers'
	      , { 'beerid':beerId, 'year':yr }
	  );
    });
    
    $('.mergeBeer').click(function() {
	// strip 'into' from beerid
	var intoBeerId = this.id.substr(4); 
	var sel = "#select" + intoBeerId;
	var mergeBeerId = $(sel).val().substr(5);
	var yr = $('#year').text(); 
	var breweryId = $('#breweryId').val(); 

	$.post( './breweries/mergeBeers'
	      , { 'breweryId':breweryId, 'mergeBeerId':mergeBeerId, 'intoBeerId':intoBeerId, 'year':yr }
	      , function(result) {
		    // if there is a result, fill the list div and fade it in
		    if(result) {
			$('#main_content').html(result);
			$('#main_content').fadeIn(100);
		    }
		});
    });

    function post(path, params) {
	var method ="post"; 
	
	var form = document.createElement("form");
	form.setAttribute("method", method);
	form.setAttribute("action", path);

	for(var key in params) {
	    if(params.hasOwnProperty(key)) {
		var hiddenField = document.createElement("input");
		hiddenField.setAttribute("type", "hidden");
		hiddenField.setAttribute("name", key);
		hiddenField.setAttribute("value", params[key]);

		form.appendChild(hiddenField);
	     }
	}

	document.body.appendChild(form);
	form.submit();
    }

</script>

</div>
