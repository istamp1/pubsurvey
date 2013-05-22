</table> 

<script>
        // attach Click event to all buttons in a BeerTable
    $(".BeerTable").on("click", "button", function(){deletePubBeer(this)} ) 
    
    function deletePubBeer(thisbutton) {
        // remove the tr the button is in 
        $(thisbutton).parents('tr').get(0).remove()  
        // get the pub and beer ids         
        var str = thisbutton.id.split("="); 
        var pubid = str[0];
        var beerid = str[1];
        // delete the beer from the pub
        $.post('./pubs/deletePubBeer',
            { 'pubid':pubid, 'beerid':beerid },
            // do nothing when the Web server responds
            function() { }
        ); 
        return FALSE;
    };    
</script>
    