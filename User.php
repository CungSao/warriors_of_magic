<?php

/**
 * User.php
 * Class for managining user data
 */

class User {
    public $id;

    public $username;
    public $password;

    public $name;
    public $level;
    public $exp;

    public $health;
    public $maxhealth;
    public $money;

    public $strength;
    public $intelligence;
    public $endurance;
    public $attacks;

    private $db;
    
    public function __construct($user_id, $db) {
        $this->db = $db;
        $user_id = (int)$user_id;

        $result = $this->db->query("SELECT * FROM userss WHERE id = '$user_id' LIMIT 1");
        if ($this->db->num_rows($result) < 1) {
            throw new Exception("User does not exist!");
        }
        
        $user = $this->db->fetch($result);
        $this->id = $user['id'];
        $this->username = $user['username'];
        $this->name = $user['username'];
        $this->password = $user['password'];

        // $result = $this->db->query("SELECT * FROM characters WHERE id=$user_id");
        // $character = $this->db->fetch($result);
        // if ($character == null) {
        //     $character = "INSERT INTO characters (id) VALUES ($user_id)";
        //     $db->query($character);
        // }
        // $result = $this->db->query("SELECT * FROM characters WHERE id=$user_id");
        // $character = $this->db->fetch($result);
        
        // Alias
        $this->level = $user['level'];
        $this->exp = $user['exp'];

        $this->health = $user['health'];
        $this->max_health = $user['max_health'];
        $this->money = $user['money'];

        $this->strength = $user['strength'];
        $this->intelligence = $user['intelligence'];
        $this->endurance = $user['endurance'];
    
        $this->attacks = $user['attacks'];
        if($user['attacks']) {
            $this->attacks = json_decode($user['attacks'], true);
        }
    }

    public function update() {
        $attacks = json_encode($this->attacks);
            $this->db->query("UPDATE userss SET
            level = '$this->level',
            exp = '$this->exp',
            health = '$this->health',
            max_health = '$this->max_health',
            money = '$this->money',
            strength = '$this->strength',
            intelligence = '$this->intelligence',
            endurance = '$this->endurance',
            attacks = '$attacks'
        WHERE id = '$this->id' LIMIT 1");
    }
}
?>