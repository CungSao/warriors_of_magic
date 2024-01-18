<?php
/**healingShop.php
 * Shop for purchasing health restoral
 */

function healingShop() {
    global $db;
    global $player;
    global $self_link;

    $healing = array(
        'small' => array(
            'cost' => 10,
            'health' => 10,
        ),
        'medium' => array(
            'cost' => 20,
            'health' => 20,
        ),
        'large' => array(
            'cost' => 30,
            'health' => 30,
        ),
    );

    if(!empty($_POST)) {
        foreach ($healing as $name => $kit) {
            if (!empty($_POST[$name])) {
                try {
                    if ($player->money < $kit['cost']) {
                        throw new Exception("You don't have enough money to buy this health kit.");
                    }
                    elseif($player->health >= 100) {
                        throw new Exception("You already have full health.");
                    }
                    
                    // Purchase
                    $player->money -= $kit['cost'];
                    $player->health += $kit['health'];
                    $player->update();
                    echo "<p>You have purchased a {$name} health kit.<br>
                        Your health is now {$player->health}.<br>
                        You still have {$player->money}$</p>";
    
                } catch (Exception $e) {
                    echo "<p>{$e->getMessage()}</p>";
                }
            }
        }
    }

    // Display
    $label_width = 20;
    echo "<table class='formContainer centerDiv' style='width:500px;'>
        <tr>
            <th style='width:{$label_width}%;'>Name</th>
            <th style='width:{$label_width}%;'>Health</th>
            <th style='width:{$label_width}%;'>Price</th>
            <th style='width:{$label_width}%;'>&nbsp;</th>
        </tr>";
        foreach ($healing as $name => $kit) {
            echo "<tr>
                <td>" . ucwords($name) . " health kit</td>
                <td>{$kit['health']}</td>
                <td>{$kit['cost']}</td>
                <td>
                    <form action='$self_link' method='POST'>
                        <br><input  type='submit' name='$name' value='Purchase'>
                    </form>
                </td>
            </tr>";
        }
    echo "</table>";
}

?>