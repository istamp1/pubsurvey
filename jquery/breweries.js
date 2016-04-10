$(document).ready(function() {

    var showFirst = setTimeout(function(){showBrewery()},100)

    function showBrewery() {
        var breweryId = $('.breweryitem:first').prop('id'); 
	var yr = $('#year').text();
        $.post('./breweries/showBrewery',
            { 'breweryid':breweryId, 'year':yr },
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

