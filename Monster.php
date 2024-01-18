<?php
/**
 * Monster.php
 * Object for holding and managing monster data
 */

class Monster {
    public $id;

    public $name;
    public $level;
    public $maxhealth;
    
    public $strength;
    public $intelligence;
    public $endurance;
    public $attacks;

    private $db;
    
    public function __construct($monster_id, $db) {
        $this->db = $db;
        $monster_id = (int)$monster_id;

        $result = $this->db->query("SELECT * FROM monsters WHERE id = '$monster_id' LIMIT 1");
        if ($this->db->num_rows($result) == 0) {
            throw new Exception("Monster does not exist!");
        }
        
        $monster = $this->db->fetch($result);
        $this->id = $monster['id'];
        $this->name = $monster['name'];
        $this->level = $monster['level'];

        $this->max_health = $monster['max_health'];
        if (!isset($_SESSION['monster_health'])) {
            $_SESSION['monster_health'] = $this->max_health;
        }
        $this->health = $_SESSION['monster_health'];

        $this->strength = $monster['strength'];
        $this->intelligence = $monster['intelligence'];
        $this->endurance = $monster['endurance'];
    
        $this->attacks = $monster['attacks'];
        if ($monster['attacks']) {
            $this->attacks = json_decode($monster['attacks'], true);
        }
    }

    public function update() {
        $_SESSION['monster_health'] = $this->health;
    }
}
?>