<tr>
    <td width="65px"><?php echo ($brewerybeer['bisBeerId'] == 0) ? '' : $brewerybeer['bisBeerId'] ?></td>
    <td width="150px" class="brewerybeer" id="beer<?php echo $brewerybeer['locBeerId'] ?>"><?php echo $brewerybeer['BeerName'] ?></td>
    <td width="50px"><?php echo $brewerybeer['ABV'] ?>%</td>
    <td width="85px"><?php echo $brewerybeer['StyleDescription'] ?></td> 
    <?php
    if(sizeof($brewerybeers) > 1) {
    ?>
	<td>
	    <select class="beerToMerge" id="select<?php echo $brewerybeer['locBeerId'] ?>">
		<?php
		    foreach($brewerybeers as $beer) { 
			echo '<option value="merge'.$beer['locBeerId'].'">'.$beer['BeerName'].'</option>';
		    } 
		?>
	    </select>
	</td> 
	<td><button class="mergeBeer" id="into<?php echo $brewerybeer['locBeerId'] ?>">Merge</button></td> 
    <?php
    } else {
    ?>
	<td>&nbsp;</td><td>&nbsp;</td>
    <?php
    } 
    ?>
</tr>
