<?php
/**
 * arena.php
 * arena for fighting monsters
 */

function arena() {
    global $db;
    global $player;
    global $self_link;
    require('Monster.php');

    // Select opponent
    if (empty($_SESSION['monster_id']) && !empty($_POST['fight'])) {
        $monster_id = (int)$_POST['monster_id'];
        
        try {
            $monster = new Monster($monster_id, $db);
            $_SESSION['monster_id'] = $monster_id;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    // Fight opponent
    if (isset($_SESSION['monster_id'])) {
        if (!isset($monster)) {
            $monster = new Monster($_SESSION['monster_id'], $db);
        }

        $winner = battle($player, $monster);
        if ($winner) {
            if($winner == 'player') {
                $money_gain = 10 + ($monster->level * 5);
                $player->money += $money_gain;
                $player->update();
    
                echo "<div class='centers'>
                    You gained {$money_gain} money!<br>
                    You currently have {$player->money}$
                </div>";
            }
            elseif ($winner == 'opponent') {
                // 
            }
            unset($_SESSION['monster_id']);
            unset($_SESSION['monster_health']);
        }
    }
    // Display select form
    else {
        $result = $db->query("SELECT id, name, level FROM monsters");
        echo "<div class='formContainer centerDiv' style='width:350px;'>
            <form action='$self_link' method='POST'>";
                while($monster = $db->fetch($result)) {
                    echo "<input type='radio' name='monster_id' value='{$monster['id']}'> $monster[name] ($monster[level])<br>";
                }
                echo "<input type='submit' name='fight' value='Fight'>";
            }
}

function battle($player, $opponent) {
    global $db;
    global $self_link;

    // Fetch attacks db
    $result = $db->query("SELECT * FROM attacks");
    $attacks = [];
    while($attack = $db->fetch($result)) {
        $attacks[$attack['id']] = $attack;
    }

    $winner = false;
    $combat_display = '';
    if (!empty($_POST['attack'])) {
        $attack_id = (int)$_POST['attack_id'];

        try {
            if(!isset($attacks[$attack_id])) {
                throw new Exception("Invalid attack");
            }
            if(!isset($player->attacks[$attack_id])) {
                throw new Exception("You don't have this attack");
            }

            $attack = $attacks[$attack_id];
            // Run turn

            // Calc player damage
            function calculateDamage($attacker, $attack, $opponent) {
                $damage = $attack['power'];

                if ($attack['type'] == 'melee') {
                    $damage *= 10 + $attacker->strength;
                }
                elseif ($attack['type'] == 'magic') {
                    $damage *= 10 + $attacker->intelligence;
                }
                $damage *= mt_rand(3, 5);
                $damage = round($damage / (1 + $opponent->endurance), 2);

                return $damage;
            }

            function displayDamageResult($attacker, $attack, $damage) {
                return $attacker->name . ' ' . $attack['combat_text'] . "<br>" .
                $attacker->name . ' deals ' . $damage . ' damage';
            }

            $player_damage = calculateDamage($player, $attack, $opponent);
            
            // Calc opponent damage
            $attack = $opponent->attacks[array_rand($opponent->attacks)];
            $opponent_damage = calculateDamage($opponent, $attack, $player);

            $combat_display .= displayDamageResult($player, $attack, $player_damage);
            $combat_display .= "<br><br>";
            $combat_display .= displayDamageResult($opponent, $attack, $opponent_damage);

            // Apply damage
            $opponent->health -= $player_damage;
            if($opponent->health > 0) {
                $player->health -= $opponent_damage;
            }
            
            // Check for winner
            if ($opponent->health <= 0) {
                $opponent->health = 0;
                $winner = 'player'; //true
                $combat_display .= "<br>You win!";
            }
            elseif ($player->health <= 0) {
                $player->health = 0;
                $winner = 'opponent';
                $combat_display .= "<br>You lose! {$opponent->name} wins!";
            }

            // Update db
            $player->update();
            $opponent->update();
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    // Display
    echo "<table class='centerDiv center' style='width: 500px; border: 1px solid rgb(0,0,0);'>
        <tr>
            <th style='width:50%;'>$player->name</th>
            <th style='width:50%;'>$opponent->name</th>
        </tr>
        <tr>
            <td style='width:50%;'>$player->health / $player->max_health</td>
            <td style='width:50%;'>$opponent->health / $opponent->max_health</td>
        </tr>";

        // Attack text
        if($combat_display) {
            echo "<tr>
            <td colspan='2'>$combat_display</td>
            </tr>";
        }
        
        // Move prompt
        echo "<tr><td colspan='2' style='align:left'>";
        if(is_array($player->attacks)) {
            echo "<br>";
            echo "<form action='$self_link' method='POST'>";
            foreach ($player->attacks as $id => $attack) {
                echo "<input type='radio' name='attack_id' value = '$id' />" .  $attacks[$id]['name'] . "<br>";
            }
            echo "<br><input type='submit' name='attack' value='Attack'>
            </form>";
        }
        // else {
        //     unset($_SESSION['monster_id']);
        //     unset($_SESSION['monster_health']);
        // }
        echo "</td></tr>";
        
    echo "</table>";

    return $winner;
}

?>