<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once 'core/config.php';
include_once 'core/model.php';
include_once 'core/validator.php';


$validator = new Validator();


$validator = $validator->check($_GET, array(
    'node' =>  [
        'required' => true,
        'min' => 1,
        'max' => 12
    ],
    'language' => [
        'required' => true,
        'enum' => [
            'italian',
            'english'
        ],
    ], 
    'page_num' => [
        'numeric' => true,
        'min' => 0
    ],

    'page_size' => [
        'numeric' => true,
        'min' => 0,
        'max' => 1000
    ],
));

if ($validator->getPassed()) {


    $num = 0;
    $size = 100;
    $filter = '';
    if (isset($_GET['page_num'])) {
        $num = $_GET['page_num'];
    }

    if (isset($_GET['page_size'])) {
        $size = $_GET['page_size'];
    }

    if (isset($_GET['filter'])) {
        $filter = $_GET['filter'];
    }


    // Instantiate blog model object
    $model = new Model();

    // Blog model query
    $result = $model->read($_GET['node'], $_GET['language'], $num, $size, $filter);
    // Get row count
    $num = count($result);

    // Check if any results
    if ($num > 0) {

        echo json_encode($result);
    } else {
        // No results
        echo json_encode(
            array('message' => 'No results')
        );
    }
} else {
    echo json_encode(
        $validator->getErrors(),
    );
}
