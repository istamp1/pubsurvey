$(document).ready(function() {

    var showFirst = setTimeout(function(){showPub()},100)

    function showPub() {
        var pubid = $('.pubitem:first').prop('id');
		var yr = $('#year').text();
        $.post('./pubs/showPub',
            { 'pubid':pubid, 'year':yr },
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