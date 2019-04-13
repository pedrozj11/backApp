<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once 'config.php';
include_once 'model.php';


// Instantiate blog model object
$model = new Model();

// Blog model query
$result = $model->read();
// Get row count
$num = count($result);

// Check if any results
if($num > 0) {

  echo json_encode($result);

} else {
  // No results
  echo json_encode(
    array('message' => 'No results')
  );
}
