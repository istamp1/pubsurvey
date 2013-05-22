<ul>
<?php
    foreach( $beers as $beer ) {
        $breweryBeer = $beer['Beer'];
        $beerID = $beer['BeerID'];
        echo '<li class="findbeer" id="'.$beerID.'">'.$breweryBeer.'</li>';
        }
?>
</ul>
<script>
    // when selecting a beer, populate the Add form fields
    $('.findbeer').click(function() {
        // get the beer details   
        var beerID = this.id; 
        var liText = $(this).html().split(", ");  
        var brewery = liText[0]
        var beer = liText[1]
        var abv = liText[2]
        var beerstyle = liText[3]
        abv = abv.substr(0, abv.length - 1);
        // populate the form fields
        $('#beerid').val(beerID);
        $('#breweryname').val(brewery);
        $('#beername').val(beer);
        $('#abv').val(abv);
        $('#beerstyle').val(beerstyle);
        // focus on the price
        $('#price').focus(); 
    });        
</script>
