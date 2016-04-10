<tr class="<?php echo $beerCider; ?>" id="<?php echo $pubbeer['BeerId'] ?>">
    <td> 
	<table>
	    <tr class="pubbeerdetailread" style="background: inherit;">
		<td width="100px"><?php echo $pubbeer['BreweryName']; ?></td>
		<td width="120px"><?php echo $pubbeer['BeerName']; ?></td>
		<td width="50px"><?php echo $pubbeer['ABV']; ?>%</td>
		<td width="50px">£<?php echo $pubbeer['Price']; ?></td>
		<td width="85px"><?php echo $pubbeer['DispenseDescription']; ?></td>
		<td width="85px"><?php echo $pubbeer['StyleDescription']; ?></td>
		<td width="10px"><button class="pubbeerupdate" id="U<?php echo $pubbeer['BeerId']; ?>">Upd</button></td>
		<td width="10px"><button class="pubbeerdelete" id="D<?php echo $pubbeer['BeerId']; ?>">Del</button></td>
	    </tr>
	    <tr class="pubbeerdetailupdate hidden" style="background: inherit">
		<td colspan="8">
		    <?php if($pubbeer['isBIS'] == 0) { ?>
			<div class="row">
			    <div class="col2">
			       <label>Brewery:</label>
			       <input type="text" name="brewery" value="<?php echo $pubbeer['BreweryName']; ?>" maxlength="100" size="25" /> 
			    </div>
			    <div class="col2">
			       <label>Beer:</label>
			       <input type="text" name="beer" value="<?php echo $pubbeer['BeerName']; ?>" maxlength="100" size="25" />
			    </div>
			</div>
			<div class="row">
			    <div class="col2">
				<label>ABV:</label>
				<input type="text" name="abv" value="<?php echo $pubbeer['ABV']; ?>%" maxlength="4" size="5" style="text-align: center" />
			    </div>
			    <div class="col2">
				<label>Style:</label>
				<select name="beerstyle">
				    <?php
				    foreach ( $beerStyles as $style ) {
					$selected = ( $pubbeer['StyleDescription'] == $style['StyleDescription'] ) ? ' selected' : '';
					echo '<option value="'.$style['BeerStyle'].'" '.$selected.'>'.$style['StyleDescription'].'</option>';
				    }
				    $selected = ( $pubbeer['StyleDescription'] == 'CI' ) ? ' selected' : '';
				    echo '<option value="CI"'.$selected.'>Cider</option>';
				    $selected = ( $pubbeer['StyleDescription'] == 'PE' ) ? ' selected' : '';
				    echo '<option value="PE"'.$selected.'>Perry</option>';
				    ?>
				</select>
			    </div>
			</div>
		    <?php } ?>
		    <div class="row">
			<div class="col2">
			    <label>Price (pence):</label>
			    <input type="text" name="price" value="<?php echo $pubbeer['Price']; ?>" maxlength="4" size="10" style="text-align: center" />
			</div>
			<div class="col2">
			    <label for="dispense">Dispense:</label>		     
			    <select name="dispense">
				<option value="H"<?php echo ( $pubbeer['DispenseDescription'] == 'Handpump' ) ? ' selected' : ''; ?>>Handpump</option>
				<option value="G"<?php echo ( $pubbeer['DispenseDescription'] == 'Gravity' ) ? ' selected' : ''; ?>>Gravity</option>
				<option value="O"<?php echo ( $pubbeer['DispenseDescription'] == 'Other' ) ? ' selected' : ''; ?>>Other</option>
			    </select>
			</div>
		    </div>
		    <button class="pubbeercancel fr" id="X<?php echo $pubbeer['BeerId']; ?>">Cancel</button> 
		    <button class="pubbeersave fr" id="S<?php echo $pubbeer['BeerId']; ?>">Save</button> 
		</td>
	    </tr>
	</table>
    </td>
</tr>

