<?php

//  - file that will accept a book ID to update a database record.

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/book.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare book object
$book = new Book($db);
 
// get id of book to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of book to be edited
$book->id = $data->id;

// make sure data is not empty
if(
    !empty($data->name) &&
    !empty($data->isbn) &&
    !empty($data->author) &&
    !empty($data->category_id)
){
    // set book property values
    $book->name = $data->name;
    $book->isbn = $data->isbn;
    $book->author = $data->author;
    $book->category_id = $data->category_id;


    // update the book
    if($book->update() === 2){
     
        // set response code - 400 bad request
        http_response_code(400);

        // tell the user
        echo json_encode(array("message" => "Name Or Isbn Not Unique .."));
    }else{

        // set response code - 200 ok
        http_response_code(200);
     
        // tell the user
        echo json_encode(array("message" => "Book was updated."));
    }

// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update book. Data is incomplete."));
}



// // update the book
// if($book->update()){
 
//     // set response code - 200 ok
//     http_response_code(200);
 
//     // tell the user
//     echo json_encode(array("message" => "Book was updated."));
// }
 
// // if unable to update the book, tell the user
// else{
 
//     // set response code - 503 service unavailable
//     http_response_code(503);
 
//     // tell the user
//     echo json_encode(array("message" => "Unable to update book."));
// }


?>