/* Global JavaScript File for working with jQuery library */

// execute when the HTML file's (document object model: DOM) has loaded
$(document).ready(function() {

    $('.pubitem').click(function() {
        // get the pub id
        var pubid = this.id;
        var yr = $('#year').text();
        $.post('./pubs/showPub',
            { 'pubid':pubid, 'year':yr },
            // when the Web server responds to the request
            function(result) {
                // if there is a result, fill the list div and fade it in
                if(result) {
                    $('#main_content').html(result);
                    $('#main_content').fadeIn(1000);
                }
            }
        );
    });

    $('.breweryitem').click(function() {
        // get the pub id
        var breweryid = this.id;
        var yr = $('#year').text();
        $.post('./breweries/showBrewery',
            { 'breweryid':breweryid, 'year':yr },
            // when the Web server responds to the request
            function(result) {
                // if there is a result, fill the list div and fade it in
                if(result) {
                    $('#main_content').html(result);
                    $('#main_content').fadeIn(1000);
                }
            }
        );
    });

    $('.beeritem').click(function() {
        // get the pub id
        var beerid = this.id;
        var yr = $('#year').text();
        $.post('./beers/showBeer',
            { 'beerid':beerid, 'year':yr },
            // when the Web server responds to the request
            function(result) {
                // if there is a result, fill the list div and fade it in
                if(result) {
                    $('#main_content').html(result);
                    $('#main_content').fadeIn(1000);
                }
            }
        );
    });


    var clock = setInterval( function(){stats()}, 60 * 1000 );

    function stats() {
        var yr = $('#year').text();
       $.post('./pubs/getStats', { 'year':yr },
            // when the Web server responds to the request
            function(result) {
                // if there is a result, fill the title-right and fade it in
                if(result) {
                    $('#title-right').html(result);
                    $('#title-right').fadeIn(10);
                }
            }
        );
    }

});



