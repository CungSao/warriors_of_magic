<?php
/**
 * monsterPages.php
 * Functions for creating and editing monsters
 */

function createMonster() {
    global $db;
    global $player;
    global $self_link;

    if(!empty($_POST['create_monster'])) {
        try {
            $name = $db->clean($_POST['name']);
            $level = (int)$_POST['level'];
            $max_health = (int)$_POST['max_health'];
            $strength = (int)$_POST['strength'];
            $intelligence = (int)$_POST['intelligence'];
            $endurance = (int)$_POST['endurance'];

            if (!$name) {
                throw new Exception("Please enter a name");
            }
            if ($level < 1) {
                throw new Exception("Level must be greater than 0");
            }
            if ($max_health < 1) {
                throw new Exception("Max health must be greater than 0");
            }
            if ($strength < 1) {
                throw new Exception("Strength must be greater than 0");
            }
            if ($intelligence < 1) {
                throw new Exception("Intelligence must be greater than 0");
            }
            if ($endurance < 1) {
                throw new Exception("Endurance must be greater than 0");
            }

            $attacks = [];
            foreach ($_POST['attacks'] as $id => $attack) {
                if(!is_int($id)) {
                    throw new Exception("Invalid attack $id");
                }
                
                $attacks[$id]['combat_text'] = $db->clean($attack['combat_text']);
                if(!$attacks[$id]['combat_text']) {
                    throw new Exception("Please enter combat text for $id!");
                }
                
                $attacks[$id]['type'] = $db->clean($attack['type']);
                if($attacks[$id]['type'] != 'melee' && $attacks[$id]['type'] != 'magic') {
                    throw new Exception("Invalid attack $id type!");
                }
                
                $attacks[$id]['power'] = (int)$attack['power'];
                if($attacks[$id]['power'] < 1) {
                    throw new Exception("Please enter attack $id power!");
                }
            }

            // Insert new monster into db
            $attacks = json_encode($attacks);
            // echo $attacks . "<br>";
            $db->query("INSERT INTO monsters (name, level, max_health, strength, intelligence, endurance, attacks)
                VALUES ('$name', '$level', '$max_health', '$strength', '$intelligence', '$endurance', '$attacks')");
            if($db->affected_rows() > 0) {
                echo "Monster created!<br>";
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
        <label style='width:{$label_width}em;'>Level:</label><input type='number' name='level'><br>
        <label style='width:{$label_width}em;'>Max Health:</label><input type='number' name='max_health'><br>
        <label style='width:{$label_width}em;'>Strength:</label><input type='number' name='strength'><br>
        <label style='width:{$label_width}em;'>Intelligence:</label><input type='number' name='intelligence'><br>
        <label style='width:{$label_width}em;'>Endurance:</label><input type='number' name='endurance'><br><br>
        <label>Attacks:</label>
            <p style='margin-left:10px;'>";
            $label_width = 4;
            for ($i=1; $i <= 2; $i++) { 
                echo "<label style='width:{$label_width}em;'>Text:</label>
                    <input type='text' name='attacks[$i][combat_text]' / ><br>
                <label style='width:{$label_width}em;'>Type:</label>
                    <select name='attacks[$i][type]'>
                        <option value='melee'>Melee</option>
                        <option value='Magic'>magic</option>
                    </select><br>
                    <label style='width:{$label_width}em;'>Power:</label>
                        <input type='number' name='attacks[$i][power]'><br>
                    <br>";
                }
            echo "</p>
            <input type='submit' name='create_monster' value='Create Monster' />
        </form>
    </div>";
}

function editMonster() {
    
}
?>