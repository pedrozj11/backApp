<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once 'config.php';
include_once 'model.php';
include_once 'config/validator.php';


$validator = new Validator();


$validator = $validator->check($_GET, array(
    'node' =>  [
        'required' => true,
    ],
    'language' => [
        'required' => true,
        'enum' => [
            'italian',
            'english'
        ],
    ]
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
        array('422' => 'Not Valid arguments')
    );
}
