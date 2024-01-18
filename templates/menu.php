<div id='menu'>
    <ul>
        <form action='./' method='POST'>
            <input type='submit' name='logout' value='Logout' class='formContainer' style='color: red;'>
        </form>
        <?php
        foreach ($pages as $id => $page) {
            echo "<li><a href='?page=" . $id . "'>" . $page['name'] . "</a></li>";
        }
        ?>
    </ul>
</div>