<div id="pubBeerAdd" class="marg-10">
    <form id="pubBeerAddForm" autocomplete="off">
        <fieldset>
            <div class="search row">
                <label for="searchvalue">Search:</label>
                <input type="text" name="searchvalue" id="searchvalue" value="" maxlength="100" size="50" autocomplete="off" />
                <input type="hidden" id="beerid" name="beerid" />
                <input type="hidden" id="isbis" name="isbis" />
                <div id="searchresults"></div>
            </div>
            <div class="row">
                <div class="col2">
                   <label for="breweryname">Brewery:</label>
                   <input type="text" class="clearbeerid" id="breweryname" name="breweryname" value="" maxlength="100" size="25" />
                </div>
                <div class="col2">
                   <label for="beername">Beer:</label>
                   <input type="text" class="clearbeerid" id="beername" name="beername" value="" maxlength="100" size="25" />
                </div>
            </div>
            <div class="row">
                <div class="col2">
                    <label for="abv">ABV:</label>
                    <input type="text" id="abv" name="abv" value="" maxlength="4" size="5" style="text-align: center" />
                </div>
                <div class="col2">
                    <label for="beerstyle">Style:</label>
                    <select id="beerstyle" name="style">
                        <?php
                        foreach ( $beerStyles as $style ) {
                            echo '<option value="'.$style['BeerStyle'].'">'.$style['StyleDescription'].'</option>';
                        }
                        echo '<option value="CI">Cider</option>';
                        echo '<option value="PE">Perry</option>';
                        ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col2">
                    <label for="price">Price (pence):</label>
                    <input type="text" id="price" name="price" value="" maxlength="4" size="10" style="text-align: center" />
                </div>
                <div class="col2">
                    <label for="dispense">Dispense:</label>
                    <select id="dispense" name="dispense">
                        <option value="H">Handpump</option>
                        <option value="G">Gravity</option>
                        <option value="O">Other</option>
                    </select>
                </div>
            </div>
            <button class="fr" type="submit" value="Submit">Add</button>
        </fieldset>
    </form>

<script>
    // Add a Beer to the Pub
    $('#pubBeerAddForm').submit(function() 
    {
        // get the form data
        var yr = $('#year').text();
        var pubid = $('#pubid').text();
        var beerid = $('#beerid').val();
        var isBIS = $('#isbis').val();
        var breweryname = $('#breweryname').val();
        var beername = $('#beername').val();
        var abv = $('#abv').val();
        var beerstyle = $('#beerstyle').val();
        var dispense = $('#dispense').val();
        var price = $('#price').val(); 

        // check input
        if (breweryname == '' || beername == '' ) { return false; }

        // post it to the controller
        $.post( './pubs/addPubBeer'
            , { 'pubid':pubid, 'year':yr, 'beerid':beerid, 'isbis' : isBIS, 'breweryname':breweryname
               , 'beername':beername, 'abv':abv, 'beerstyle':beerstyle, 'dispense':dispense, 'price':price }
            , function(result) {
                // if there is a result
                if(result) {
                    // determine where to append
                    var hdrid = '#BeersHeader';
                    if (beerstyle == "CI" || beerstyle == "PE" ) {
                        hdrid = '#CidersHeader';
                    }
                    // add the beer to the correct list
                    $(hdrid).append(result);
                    // move focus to search field and clear it and the results
                    $('#searchvalue').val('');
                    $('#searchvalue').focus();
                    $('#searchresults').html('');
		    
		    $('#breweryname').val('');
		    $('#beername').val('');
		    $('#abv').val('');
		    $('#beerstyle').val('');
		    $('#dispense').val('');
		    $('#price').val(''); 
                }
            });

        // prvent form submitting
        return false;
    });

    // when a brewery or beer is changed, clear the beerid
    $('.clearbeerid').keyup(function() {
        $('#beerid').val('');
        // prvent form submitting
        return false;
    });

    // search database for beers and breweries containing search string
    // and populate a list
    $('#searchvalue').keyup(function() {
        // get the search field and pub id
        var search = this.value; 
	if(search.length > 2) {
	    var pubid = $('#pubid').val(); 
	    var yr = $('#year').text(); 
	    // post it to the controller
	    $.post( './pubs/findBeers'
		, { 'search':search, 'pubid':pubid, 'year':yr }
		, function(result) {
		    // if there is a result, fill the list div and fade it in
		    if(result) {
			    $('#searchresults').html(result);
			    $('#searchresults').fadeIn(100);
		    }
		}
	    );
	    // prvent form submitting
	    return false;
	} else {
	    $('#searchresults').html('');
	    $('#searchresults').fadeIn(100);
	}
    });

</script>

</div>
