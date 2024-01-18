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

    // Chưa buy

    // Display
    $label_width = 20;
    echo "<div class='formContainer centerDiv' style='text-align:left;width: 400px;'
        <form action='$self_link' method='POST'>";
            foreach ($healing as $name => $kit) {
                echo "<label style='width:{$label_width}em;'>" . ucwords($name) . " health kit (\${$kit['cost']}, {$kit['health']} health)</label>
                    <input type='submit' name='$name' value='Purchase'><br>";
            }
        echo "</form>
    </div>";
}
?>