<?php

//  - contains properties and methods for "book" database queries.

/*
    - The code below shows a class named book with several of its properties. It also shows a constructor method that will accept the database connection.

    - We will use this class to read data from the database. Open api folder. Create objects folder. Open objects folder. Create book.php file. Place the following code inside it.

    - We used the read() method on the previous section but it does not exist yet in the book class. We need to add this read() method. The code below shows the query to get records from the database.

    - Open objects folder. Open book.php file. Place the following code inside the book class. To make sure you added it correctly, put the code before the last closing curly brace.
 
*/


class Book{
 
    // database connection and table name
    private $conn;
    private $table_name = "books";
 
    // object properties
    public $id;
    public $name;
    public $isbn;
    public $author;
    public $category_id;
    public $category_name;
    public $created_at;
    public $update_flag;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read books
	function read(){
	 
	    // select all query
	    $query = "SELECT
	                c.name as category_name, p.id, p.name, p.author, p.isbn, p.category_id, p.created_at
	            FROM
	                " . $this->table_name . " p
	                LEFT JOIN
	                    categories c
	                        ON p.category_id = c.id
	            ORDER BY
	                p.created_at DESC";
	 
	    // prepare query statement
	    $stmt = $this->conn->prepare($query);
	 
	    // execute query
	    $stmt->execute();
	 
	    return $stmt;
	}

	// create book
	function create(){

		global $update_flag;

        $update_flag = false;

		$count = $this->unique_values();

		if ($count >= 1) {

	    	return 2;
	    } else {
	    	// query to insert record
		    $query = "INSERT INTO
		                " . $this->table_name . "
		            SET
		                name=:name, isbn=:isbn, author=:author, category_id=:category_id, created_at=:created_at";
		 
		    // prepare query
		    $stmt = $this->conn->prepare($query);
		 
		    // sanitize
		    $this->name=htmlspecialchars(strip_tags($this->name));
		    $this->isbn=htmlspecialchars(strip_tags($this->isbn));
		    $this->author=htmlspecialchars(strip_tags($this->author));
		    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
		    $this->created_at=htmlspecialchars(strip_tags($this->created_at));
		 
		    // bind values
		    $stmt->bindParam(":name", $this->name);
		    $stmt->bindParam(":isbn", $this->isbn);
		    $stmt->bindParam(":author", $this->author);
		    $stmt->bindParam(":category_id", $this->category_id);
		    $stmt->bindParam(":created_at", $this->created_at);
		 
		    // execute query
		    if($stmt->execute()){
		        return true;
		    }
		 
		    return false;
	    }
	     
	}

	// used when filling up the update book form
	function readOne(){
		
	    // query to read single record
	    $query = "SELECT
	                c.name as category_name, p.id, p.name, p.author, p.isbn, p.category_id, p.created_at
	            FROM
	                " . $this->table_name . " p
	                LEFT JOIN
	                    categories c
	                        ON p.category_id = c.id
	            WHERE
	                p.id = ?
	            LIMIT
	                0,1";
	 
	    // prepare query statement
	    $stmt = $this->conn->prepare( $query );
	 
	    // bind id of book to be updated
	    $stmt->bindParam(1, $this->id);
	 
	    // execute query
	    $stmt->execute();
	 
	    // get retrieved row
	    $row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
	    // set values to object properties
	    $this->name = $row['name'];
	    $this->isbn = $row['isbn'];
	    $this->author = $row['author'];
	    $this->category_id = $row['category_id'];
	    $this->category_name = $row['category_name'];
	}

	// update the book
	function update(){

        global $update_flag;

		$this->searchinside();

	    if($this->id!=null){

	    	$update_flag = true;
	    	$count = $this->unique_values();

			if ($count >= 1) {

		    	return 2;
		    } else {
		    	// update query
			    $query = "UPDATE " . $this->table_name . " SET name = :name, isbn = :isbn, author = :author, category_id = :category_id WHERE id = :id";
			 
			    // prepare query statement
			    $stmt = $this->conn->prepare($query);
			    
			 
			    // sanitize
			    $this->name=htmlspecialchars(strip_tags($this->name));
			    $this->isbn=htmlspecialchars(strip_tags($this->isbn));
			    $this->author=htmlspecialchars(strip_tags($this->author));
			    $this->category_id=htmlspecialchars(strip_tags($this->category_id));
			    $this->id=htmlspecialchars(strip_tags($this->id));
			 
			    // bind new values
			    $stmt->bindParam(':name', $this->name);
			    $stmt->bindParam(':isbn', $this->isbn);
			    $stmt->bindParam(':author', $this->author);
			    $stmt->bindParam(':category_id', $this->category_id);
			    $stmt->bindParam(':id', $this->id);
			 
			    // execute the query
			    if($stmt->execute()){
			        return true;
			    }
			    return false;
			}
	    }
	 
	    return false;
	}

	// delete the book
	function delete(){

        $this->searchinside();

	    if($this->id!=null){
		    // delete query
		    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
		 
		    // prepare query
		    $stmt = $this->conn->prepare($query);
		 
		    // sanitize
		    $this->id=htmlspecialchars(strip_tags($this->id));
		 
		    // bind id of record to delete
		    $stmt->bindParam(1, $this->id);
		 
		    // execute query
		    if($stmt->execute()){
		        return true;
		    }
		 
		    return false;
	    }

	    return false; 
	}

    // check record is already exist ..
	function searchinside(){

		$query = "SELECT p.id FROM " . $this->table_name . " p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ".$this->id." LIMIT 0,1";
	 
	    // prepare query statement
	    $stmt = $this->conn->prepare( $query );
	 
	    // bind id of book to be updated
	    $stmt->bindParam(1, $this->id);
	 
	    // execute query
	    $stmt->execute();
	 
	    // get retrieved row
	    $row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
	    // set values to object properties
	    $this->id = $row['id'];
	}

	// search books
	function search($keywords){
	 
	    // select all query
	    $query = "SELECT
	                c.name as category_name, p.id, p.name, p.author, p.isbn, p.category_id, p.created_at
	            FROM
	                " . $this->table_name . " p
	                LEFT JOIN
	                    categories c
	                        ON p.category_id = c.id
	            WHERE
	                p.name LIKE ? OR p.author LIKE ? OR p.isbn LIKE ?
	            ORDER BY
	                p.created_at DESC";
	 
	    // prepare query statement
	    $stmt = $this->conn->prepare($query);
	 
	    // sanitize
	    $keywords=htmlspecialchars(strip_tags($keywords));
	    $keywords = "%{$keywords}%";
	 
	    // bind
	    $stmt->bindParam(1, $keywords);
	    $stmt->bindParam(2, $keywords);
	    $stmt->bindParam(3, $keywords);
	 
	    // execute query
	    $stmt->execute();
	 
	    return $stmt;
	}

	// read books with pagination
	public function readPaging($from_record_num, $records_per_page){
	 
	    // select query
	    $query = "SELECT
	                c.name as category_name, p.id, p.name, p.author, p.isbn, p.category_id, p.created_at
	            FROM
	                " . $this->table_name . " p
	                LEFT JOIN
	                    categories c
	                        ON p.category_id = c.id
	            ORDER BY p.created_at DESC
	            LIMIT ?, ?";
	 
	    // prepare query statement
	    $stmt = $this->conn->prepare( $query );
	 
	    // bind variable values
	    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
	    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
	 
	    // execute query
	    $stmt->execute();
	 
	    // return values from database
	    return $stmt;
	}

	//The total rows are needed to build the pagination array. It is included in the 'paging' computation.
	// used for paging books
	public function count(){
	    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
	 
	    $stmt = $this->conn->prepare( $query );
	    $stmt->execute();
	    $row = $stmt->fetch(PDO::FETCH_ASSOC);
	 
	    return $row['total_rows'];
	}

	// used for checking if book is repeated or not .
	function unique_values(){

		global $update_flag;

		$query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . " WHERE name = ? or isbn = ?";
	 
	    $stmt = $this->conn->prepare( $query );
	    $name_keywords =htmlspecialchars(strip_tags($this->name));
	    $isbn_keywords =htmlspecialchars(strip_tags($this->isbn));
	 
	    // bind
	    $stmt->bindParam(1, $name_keywords);
	    $stmt->bindParam(2, $isbn_keywords);
	    $stmt->execute();
	    $row = $stmt->fetch(PDO::FETCH_ASSOC);

	    // var_dump( 'update_flag : ' . $update_flag);

	    //if action is update (update_flag is true) 
	    //and total rows is one so it just the updated row
	    if ($update_flag == 1 && $row['total_rows'] == 1){
	    	$row['total_rows'] = 0;
	    }

	    return $row['total_rows'];
	}
}
