</table> 

<script>
    // attach Click event to all buttons in a BeerTable
    $(".BeerTable").on("click", ".pubbeerdelete", function(){deletePubBeer(this)} ); 
    $(".BeerTable").on("click", ".pubbeerupdate", function(){updatePubBeer(this)} ); 
    $(".BeerTable").on("click", ".pubbeersave", function(){savePubBeer(this)} ); 
    $(".BeerTable").on("click", ".pubbeercancel", function(){cancelPubBeer(this)} ); 
    
    function updatePubBeer(thisbutton) { 
	// get beerid
        var beerid = thisbutton.id.substring(1); 
        // show the updateable fields
	var id = '#' + beerid + ' .pubbeerdetailupdate'; 
	$(id).removeClass("hidden");
	
        return false;
    };    
    
    function cancelPubBeer(thisbutton) { 
	// get beerid
        var beerid = thisbutton.id.substring(1); 
        // hide the updateable fields
	var id = '#' + beerid + ' .pubbeerdetailupdate'; 
	$(id).addClass("hidden"); 
	
        return false;
    };    
    
    function savePubBeer(thisbutton) { 
	// get beerid
        var beerid = thisbutton.id.substring(1); 
	// get tr id to populate
	var trid = '#' + beerid;
	
        // get the pubid, year and other vars
        var pubid = $('#pubid').text();
	var yr = $('#year').text();   
	
	// get the brewery name etc here
	var brewery = '';
	if($(trid + ' input[name=brewery]').length) {
	    brewery = $(trid + ' input[name=brewery]').val();  
	}
	var beer = '';
	if($(trid + ' input[name=beer]').length) {
	    beer = $(trid + ' input[name=beer]').val();  
	} 
	var abv = '';
	if($(trid + ' input[name=abv]').length) {
	    abv = $(trid + ' input[name=abv]').val();  
	} 
	var beerstyle = '';
	if($(trid + ' select[name=beerstyle]').length) {
	    beerstyle = $(trid + ' select[name=beerstyle]').val();  
	} 
	
	var price = $(trid + ' input[name=price]').val();  
	var dispense = $(trid + ' select[name=dispense]').val();  
	
        // update the pub beer details
        $.post('./pubs/updatePubBeer',
            { 'pubid' : pubid, 'year' : yr, 'beerid' : beerid
		, 'brewery' : brewery, 'beer' : beer
		, 'abv' : abv, 'beerstyle' : beerstyle
		, 'price' : price, 'dispense' : dispense },
            // populate tr with result
            function(result) {
		$(trid).html(result);
	    }
        ); 
	
        return false;
    };    
    
    function deletePubBeer(thisbutton) { 
	// get beerid
        var beerid = thisbutton.id.substring(1); 
        // remove the tr the button is in 
	var trid = '#' + beerid;
        $(trid).remove()   
	
        // get the pubid and year        
        var pubid = $('#pubid').text();
	var yr = $('#year').text();  
        // delete the beer from the pub
        $.post('./pubs/deletePubBeer',
            { 'pubid' : pubid, 'year' : yr, 'beerid' : beerid },
            // do nothing when the Web server responds
            function() { }
        ); 
	    
        return false;
    };    
</script>
    