<?php
/**
 * attackPages.php
 * Functions for creating and editing attacks
 */

function createAttack() {
    global $db;
    global $player;
    global $self_link;

    if(!empty($_POST['create_attack'])) {
        try {
            $name = $db->clean($_POST['name']);
            $combat_text = $db->clean($_POST['combat_text']);
            $type = $db->clean($_POST['type']);
            
            $power = (int)$_POST['power'];
            $purchase_cost = (int)$_POST['purchase_cost'];

            if (!$name) {
                throw new Exception("Please enter a name");
            }
            if(strlen($name) > 50) {
                throw new Exception("Name must be less than 50 characters");
            }
            
            if (!$combat_text) {
                throw new Exception("Please enter combat text");
            }
            if(strlen($combat_text) > 500) {
                throw new Exception("Combat text must be less than 500 characters");
            }

            if($power < 1) {
                throw new Exception("Power must be greater than 0");
            }
            if($purchase_cost < 1) {
                throw new Exception("Purchase cost must be greater than 0");
            }

            // Insert new attack into db
            $db->query("INSERT INTO attacks (name, combat_text, type, power, purchase_cost)
                VALUES ('$name', '$combat_text', '$type', '$power', '$purchase_cost')");
            if($db->affected_rows() > 0) {
                echo "Attack created!<br>";
            }
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    // Dislay form
    $label_width = 6;
    echo "<div class='formContainer centerDiv' style='width:300px ;text-align:left;'>
        <form action='$self_link' method='post'>
            <label style='width:{$label_width}em;'>Name:</label><input type='text' name='name'><br>
            <label style='width:{$label_width}em;'>Combat Text:</label><input type='text' name='combat_text'><br>
            <label style='width:{$label_width}em;'>Type:</label>
                <select name='type'>
                    <option value='melee'>Melee</option>
                    <option value='magic'>Magic</option>
                </select><br>
            <label style='width:{$label_width}em;'>Power:</label><input type='number' name='power'><br>
            <label style='width:{$label_width}em;'>Purchase Cost:</label><input type='number' name='purchase_cost'><br>

            <input type='submit' name='create_attack' value='Create Attack' />
        </form>
    </div>";
}

function editAttack() {
    
}
?>