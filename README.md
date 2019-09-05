# PHP-Restful-Api-OOP-
Project Run steps 
1- You have sql file import it . (hossamapi.sql) 
2- Put project folder in xampp/htdocs or any local server you want . 
3- Go to postman and run this api urls :-

1. READ BOOKS ( Read All ): (Get) http://localhost/api/book/read.php
2. CREATE BOOK : (POST) http://localhost/api/book/create.php 
Data to insert : 
{ 
    "name" : "Amazing keivy 20.0", 
    "isbn" : "4-7555-66777", 
    "author" : "The best pillow for amazing readers.", 
    "category_id" : 2, 
    "publish_date" : "2018-06-01 00:35:07" 
 }

3. UPDATE BOOK : (Post) http://localhost/api/book/update.php 
Data to update : 
{ 
    "id" : "66", 
    "name" : "Amazing keivy 20.0", 
    "isbn" : "4-7555-66777", 
    "author" : "The best pillow for amazing readers.",
    "category_id" : 2, 
    "publish_date" : "2018-06-01 00:35:07" 
}

4. DELETE BOOK : (Delete) http://localhost/api/book/delete.php 
Data to delete : { "id" : "66" } 

############################## 

5. READ ONE BOOK : (Get) http://localhost/api/book/read_one.php?id=60 

############################## 

6. SEARCH BOOKS : (Get) http://localhost/api/book/search.php?s=Amazing 

############################## 

7. PAGINATE BOOKS : (Get) http://localhost/api/book/read_paging.php

############################## 

8. READ CATEGORIES : (Get) http://localhost/api/category/read.php
