<?php
class Model
{
    // DB stuff
    private $table;
    private $db;
    private $conectar;


    // Constructor with DB
    public function __construct($page_num, $page_size)
    {
        $this->table = 'node_tree_names';
        require_once 'config.php';
        $this->conectar = new Config();
        $this->db = $this->conectar->connection();
    }

    // Get Nodes
    public function read()
    {

        // Create query
        $query = $this->db->query("SELECT * FROM $this->table");
        // print_r($query);
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }

        return $resultSet;
    }
}
