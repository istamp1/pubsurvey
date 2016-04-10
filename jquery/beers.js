$(document).ready(function() {

    var showFirst = setTimeout(function(){showBeer()},100)

    function showBeer() {
	// get passed in beer id
	var beerId = $('#beerId').text(); 
        if(!beerId) { 
	    // not set so use default
	    beerId = $('.beeritem:first').prop('id'); 
	}
	// get year
	var yr = $('#year').text();
	// POST
        $.post('./beers/showBeer',
            { 'beerid':beerId, 'year':yr },
            // when the Web server responds to the request
            function(result) {
                // if there is a result, fill the list div and fade it in
                if(result) {
                    $('#main_content').html(result);
                    $('#main_content').fadeIn(10);
                }
            }
        );
     }

});