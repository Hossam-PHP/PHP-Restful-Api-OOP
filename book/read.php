<?php

//  - file that will output JSON data based from "books" database records.

/*
    - The code below shows headers about who can read this file and which type of content it will return.

    - In this case, our read.php file can be read by anyone (asterisk * means all) and will return a data in JSON format.

    - Open api folder. Create book folder. Open book folder. Create read.php file. Place the following code inside it.
*/
/*
    - In the code below, we included the database.php and book.php files. These are the files we created earlier.

    - We need to use the getConnection() method of the Database class to get a database connection. We pass this connection to the book class.

    - Replace of // database connection will be here comment of read.php file with the following code.
*/
/*
    - In the code below, we used the read() method of book class to read data from the database. Through the $num variable, we check if there are records found.

    - If there are records found, we loop through it using the while loop, add each record to the $books_arr array, set a 200 OK response code and show it to the user in JSON format.

    - Replace of // read books will be here comment of read.php file with the following code.

*/
/*
    - If the $num variable has a value of zero or negative, it means there are no records returned from the database. We need to tell the user about this.

    - On the code below, we set the response code to 404 - Not found and a message that says No books found.

    - Replace of // no books found will be here comment of read.php file with the following code.
*/


// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// database connection will be here

// include database and object files
include_once '../config/database.php';
include_once '../objects/book.php';
 
// instantiate database and book object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$book = new Book($db);
 
// read books will be here

// query books
$stmt = $book->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // books array
    $books_arr=array();
    $books_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $book_item=array(
            "id" => $id,
            "name" => $name,
            "author" => html_entity_decode($author),
            "isbn" => $isbn,
            "category_id" => $category_id,
            "category_name" => $category_name
        );
 
        array_push($books_arr["records"], $book_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show books data in json format
    echo json_encode($books_arr);
    
}else{// no books found will be here
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no books found
    echo json_encode(
        array("message" => "No books found.")
    );
}