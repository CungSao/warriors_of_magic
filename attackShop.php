<?php
/**attackShop.php
 * shop for buying weapons and spells
 */

function attackShop() {
    global $db;
    global $player;
    global $self_link;

    // Fetch weapon db
    $result = $db->query("SELECT * FROM attacks");
    $attacks = [];
    while($attack = $db->fetch($result)) {
        $attacks[$attack['id']] = $attack;
    }
    
    if(!empty($_POST['buy'])) {
        $attack_id = (int)$_POST['attack_id'];

        try {
            // if (!isset($attacks[$attack_id])) {
            //     throw new Exception("Invalid attack");
            // }
            if (isset($player->attacks[$attack_id])) {
                throw new Exception("<p>You already have this attack!</p>");
            }
            if ($player->money < $attacks[$attack_id]['purchase_cost']) {
                throw new Exception("<p>You don't have enough money to buy this attack!</p>");
            }

            // Purchase technique
            $player->money -= $attacks[$attack_id]['purchase_cost'];
            $player->attacks[$attack_id] = [];
            $player->update();

            echo "<p>Attack purchased!</p>";
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } 

    // Display form
    $label_width = 20;
    echo "<table class='formContainer centerDiv center' style='width:500px;'>
        <tr>
            <th style='width:{$label_width}%;'>Name</th>
            <th style='width:{$label_width}%;'>Type</th>
            <th style='width:{$label_width}%;'>Price</th>
            <th style='width:{$label_width}%;'>&nbsp;</th>
        </tr>";
        foreach ($attacks as $id => $attack) {
            if (isset($player->attacks[$id])) {
                continue;
            }
            echo "<tr>
            <td>{$attack['name']}</td>
            <td>{$attack['type']}</td>
            <td>{$attack['purchase_cost']}</td>
            <td>
                <form action='$self_link' method='POST' >
                    <input type= 'hidden' name='attack_id' value='$id'><br>
                    <input type='submit' name='buy' value='Buy'>
                </form>
            </td>
        </tr>";
        }
        
        echo "</table>";
}

?>