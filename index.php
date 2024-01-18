<?php
session_start();
require('DatabaseObject.php');
require('databaseVars.php');

$db = new DatabaseObject($host, $username, $password, $db);

if (!isset($_SESSION['user_id']) && !empty($_POST['login'])) {
    $username = $db->clean($_POST['username']);
    $password = $db->clean($_POST['password']);

    $result = $db->query("SELECT id, username, password FROM userss WHERE username = '$username' LIMIT 1");
    try {
        if ($db->num_rows($result) == 0) {
            throw new Exception("Invalid username");
        }

        $user = $db->fetch($result);
        if (md5($password) != $user['password']) {
            throw new Exception("Invalid password");
        }

        $_SESSION['user_id'] = $user['id'];
        
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
elseif (isset($_SESSION['user_id']) && !empty($_POST['logout'])) {
    session_destroy();
    header("Refresh:0");
}

// If user is logged in, load data and select page
if (isset($_SESSION['user_id'])) {
    require('User.php');
    $player = new User($_SESSION['user_id'], $db);

    $default_page = 'profile';
    $pages = array(
        'profile' => array(
            'name' => 'Profile',
            'file' => 'profile.php',
            'function' => 'profile',
        ),
        'arena' => array(
            'name' => 'Combat Arena',
            'file' => 'arena.php',
            'function' => 'arena',
        ),
        'attack_shop' => array(
            'name' => 'Attack Shop',
            'file' => 'attackShop.php',
            'function' => 'attackShop',
        ),  
        'healing_shop' => array(
            'name' => 'Healing Shop',
            'file' => 'healingShop.php',
            'function' => 'healingShop',
        ),
        'create_monster' => array(
            'name' => 'Create Monster',
            'file' => 'monsterPages.php',
            'function' => 'createMonster',
        ),
        'create_attack' => array(
            'name' => 'Create Attack',
            'file' => 'attackPages.php',
            'function' => 'createAttack',
        ),
    );

    if (!empty($_GET['page'])) {
        $page = strtolower(trim($_GET['page']));

        if(isset($pages[$page])) {
            require($pages[$page]['file']);
            echo "<p class ='pageTitle'>" . $pages[$page]['name'] . "</p>";
            $self_link = "?page=" . $page;
            $pages[$page]['function']();
        } else {
            require($pages[$default_page]['file']);
            echo "<p class ='pageTitle'>" . $pages[$default_page]['name'] . "</p>";
            $self_link = "?page=" . $default_page;
            $pages[$default_page]['function']();
        }
    } else {
        require($pages[$default_page]['file']);
        $self_link = "?page=" . $default_page;
        echo "<p class ='pageTitle'>" . $pages[$default_page]['name'] . "</p>";
        $pages[$default_page]['function']();
    }

}

// DISPLAY
$output = ob_get_clean();
require('templates/header.php');

if (isset($_SESSION['user_id'])) {
    require('templates/menu.php');
    echo $output;
} else {
    echo "<p style='text-align:center'>" . $output . "</p>";
    echo "<div class='formContainer centerDiv' style='width: 320px; margin-top: 15px'>
    <form action='./' method='POST'>
    <input type='submit' name='login' value='Login'><br>
    Username: <input type='text' name='username'><br>
    Password: <input type='password' name='password'>
    </form></div>";
}
require('templates/footer.php');
?>