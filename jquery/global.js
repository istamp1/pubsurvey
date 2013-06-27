/* Global JavaScript File for working with jQuery library */

// execute when the HTML file's (document object model: DOM) has loaded
$(document).ready(function() {

    $('.pubitem').click(function() {
        // get the pub id
        var pubid = this.id;
        $.post('./pubs/showPub',
            { 'pubid':pubid },
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
        $.post('./breweries/showBrewery',
            { 'breweryid':breweryid },
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
        $.post('./beers/showBeer',
            { 'beerid':beerid },
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
       $.post('./pubs/getStats', { },
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



