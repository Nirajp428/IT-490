<?php
 class Database
 {
     public function dbConnection(){
         $db_host = "127.0.0.1";
         $db_name = "it490db";
         $db_username = "niraj";
         $db_password = "password";
         
         $dsn_db = 'mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8';
         try{
            $site_db = new PDO($dsn_db, $db_username, $db_password);
            $site_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $site_db;

         }catch (PDOException $e){
            echo $e->getMessage();
            exit;
         }
     } 
 }
