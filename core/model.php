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
        $offset = $p_num * $p_size;
        // Create paginated query
        if ($filter != '') {

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
            AND datas.nodeName = '$filter'
            ORDER BY node.iLeft
            LIMIT $offset, $p_size ");
        } else {

            $query = $this->db->query("SELECT datas.idNode ,datas.nodeName, (
                SELECT COUNT(*)
                FROM node_tree AS node,
                node_tree AS parent
                WHERE node.iLeft BETWEEN parent.iLeft AND parent.iRight
                AND node.level = parent.level +1
                AND parent.idNode = datas.idNode
            ) AS childs
            FROM node_tree AS node,
                    node_tree AS parent,
                    node_tree_names AS datas
            WHERE node.iLeft BETWEEN parent.iLeft AND parent.iRight
            AND parent.idNode = $idNode
            AND node.idNode = datas.idNode
            AND datas.language = '$language'
            ORDER BY node.iLeft
            LIMIT $offset, $p_size ");
        }

        $resultSet =  [
            'data' => [],
            'pages' => [],
        ];

        while ($row = $query->fetch_object()) {

            array_push($resultSet['data'], $row);
        }

        // Pagination


        // Query to get total of results without pagination

        if ($filter != '') {

            $arrTotalPages = $this->db->query("SELECT COUNT(*) as cont
            FROM node_tree AS node,
                node_tree AS parent,
                node_tree_names AS datas
            WHERE node.iLeft BETWEEN parent.iLeft AND parent.iRight
            AND parent.idNode= $idNode 
            AND datas.language = '$language'
            AND datas.nodeName = '$filter'
            AND node.idNode = datas.idNode
        ");
        } else {

            $arrTotalPages = $this->db->query("SELECT COUNT(*) as cont
                FROM node_tree AS node,
                    node_tree AS parent,
                    node_tree_names AS datas
                WHERE node.iLeft BETWEEN parent.iLeft AND parent.iRight
                AND parent.idNode= $idNode 
                AND datas.language = '$language'
                AND node.idNode = datas.idNode
            ");
        }
        if($p_size!=0){
            $intTotalPages = ceil($arrTotalPages->fetch_assoc()['cont'] / $p_size);
        } else {
            $intTotalPages = $arrTotalPages->fetch_assoc()['cont'];
        }


        // Writing links

        for ($i = 0; $i < $intTotalPages; $i++) {
            $resultSet['pages'][] =  'http://localhost/backApp/api.php?node=' . $idNode . '&language=' . $language . '&page_size='
                . $p_size . '&page_num=' .  $i . '&filter=' . $filter;
            //$resultSet['links'][] =  "<a href='?page=" . $i . "'>[" . $i . "]</a>&bsp;";
        }

        $resultSet['pages'] = (object)$resultSet['pages'];

        return $resultSet;
    }
}
