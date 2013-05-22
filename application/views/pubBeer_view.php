<?php    
echo '<tr class="'.$beerCider.'">
      <td width="150px">'.$pubbeer['BreweryName'].'</td>
      <td width="150px">'.$pubbeer['BeerName'].'</td>
      <td width="50px">'.$pubbeer['ABV'].'%</td>
      <td width="50px">£'.$pubbeer['Price'].'</td>
      <td width="85px">'.$pubbeer['DispenseDescription'].'</td>
      <td width="85px">'.$pubbeer['StyleDescription'].'</td>
      <td width="10px"><button class="pubbeerdelete" id="'.$pubbeer['PubID'].'='.$pubbeer['BeerID'].'">Delete</button></td>
  </tr>'
?>