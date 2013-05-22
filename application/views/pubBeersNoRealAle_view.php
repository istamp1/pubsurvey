        <?php 
            if ($NoRealAle) {
                echo 'No Real Ale'; 
            } else {
                if (!$BeerCount) {
                    echo '<button class="NRA" id="'.$PubID.'">No Real Ale?</button>';
                }
            };
        ?>
