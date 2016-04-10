<div style="font-family: Arial">
    <div style="background-color:
    <?php
	if($BeerCount == 0) {
	    if($NoRealAle == 1) {
		echo 'White; color: Black';
	    } else {
		echo 'Red; color: White';
	    }
	} else {
	    echo 'Green; color: White';
	}
    ?>;
	     width: 150px;
	     font-size: 1.2em;
	     font-weight: bold;
	     text-align: center">
	    <?php echo $PubName; ?>
    </div>
    <div>
	<?php 
	    if($BeerCount == 0) {
		if($NoRealAle == 1) {
		    echo 'No Real Ale';
		} else {
		    echo 'Awaiting Survey';
		}
	    } else {
		echo $BeerCount; 
		if($BeerCount == 1) {
		    echo ' real ale';
		} else {
		    echo ' real ales';
		}
	    }
	?>
    <div>
</div>
