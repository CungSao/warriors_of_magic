<?php

// ob_start();

require('DatabaseObject.php');
require('databaseVars.php');

$db = new DatabaseObject($host, $username, $password, $db);

if (!empty($_POST['register'])) {
    $username = $db->clean($_POST['username']);
    $password = $db->clean($_POST['password']);

    try {
        if(strlen($username) < 5) {
            throw new Exception("Username must be at least 5 characters long");
        }
        if (strlen($password) < 5) {
            throw new Exception("Password must be at least 5 characters long");
        }
        if (!ctype_alnum($username)) {
            throw new Exception("Username must be alphanumeric");
        }
        
        // Submit to db
        $password = md5($password);
        $result = $db->query("INSERT INTO userss (username, password) VALUES('$username', '$password')");

        if ($db->affected_rows() > 0) {
            echo "Account created! Please <a href='./'>login</a>.";
        }
        
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

$output = ob_get_clean();
require('templates/header.php');
echo "<p style='text-align: center'>" . $output . "</p>";
?>

<div class='formContainer centerDiv' style='width: 320px;'>
    <form action="./register.php" method="post">
        <input type="submit" name="register" value="Register"><br>
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password">
    </form>
</div>

<?php require('templates/footer.php'); ?>