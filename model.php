<?php
class Model
{
    // DB stuff
    private $table;
    private $db;
    private $conectar;


    // Constructor with DB
    public function __construct()
    {
        $this->table = 'node_tree_names';
        require_once 'config.php';
        $this->conectar = new Config();
        $this->db = $this->conectar->connection();
    }

    // Get Nodes
    public function read($idNode, $language, $p_num, $p_size, $filter)
    {

        // Create query
        $query = $this->db->query("SELECT datas.idNode ,datas.nodeName, (
            SELECT COUNT(*)
            FROM node_tree AS node,
            node_tree AS parent
            WHERE node.iLeft BETWEEN parent.iLeft AND parent.iRight
            AND node.level = parent.level + 1
            AND parent.idNode = datas.idNode
        ) AS childs
        FROM node_tree AS node,
                node_tree AS parent,
                node_tree_names AS datas
        WHERE node.iLeft BETWEEN parent.iLeft AND parent.iRight
        AND parent.idNode = $idNode
        AND node.idNode = datas.idNode
        AND datas.language = '$language'
        ORDER BY node.iLeft;");
        //print_r($query);
        while ($row = $query->fetch_object()) {
            $resultSet[] = $row;
        }

        return $resultSet;
    }
}
