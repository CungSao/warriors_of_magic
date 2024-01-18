<?php
function profile() {
    global $db;
    global $player;

    $label_width = 7;
    echo " <div class='centerDiv' style='width:300px; text-align:left'>
    <label style='width:{$label_width}em;'>Username:</label> {$player->username}<br>
    <label style='width:{$label_width}em;'>Level:</label> {$player->level}<br>
    <label style='width:{$label_width}em;'>Exp:</label> {$player->exp}<br>
    <label style='width:{$label_width}em;'>Health:</label> {$player->health} / {$player->max_health}<br>
    <label style='width:{$label_width}em;'>Money:</label> {$player->money} <br>
    <label style='width:{$label_width}em;'>Strength:</label> {$player->strength}<br>
    <label style='width:{$label_width}em;'>Intelligence:</label> {$player->intelligence}<br>
    <label style='width:{$label_width}em;'>Endurance:</label> {$player->endurance}<br>
    <label style='width:{$label_width}em;'>Attacks:</label>";
        echo json_encode($player->attacks);
    echo "</div>";
}
?>