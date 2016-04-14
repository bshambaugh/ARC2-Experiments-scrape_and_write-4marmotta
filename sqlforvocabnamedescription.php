<?php
//412
// Access control to the database
$user = 'root';
$pass = 'password';

  try {
      $dbh = new PDO('mysql:host=localhost;dbname=drupal-7.42', $user, $pass);
        foreach($dbh->query("SELECT  `taxonomy_vocabulary`.`name`,  `taxonomy_vocabulary`.`description` ,
`taxonomy_term_data`.`vid` FROM `taxonomy_term_data` 
JOIN `taxonomy_vocabulary` ON `taxonomy_term_data`.`vid` = `taxonomy_vocabulary`.`vid`
WHERE `taxonomy_term_data`.`tid` = 24") as $row) {
  //    foreach($dbh->query('SELECT * from `pan_node` LIMIT 10') as $row) {
//          print_r($row);
          if($row['name'] !== NULL) {
          echo($row['name']);
        //  array_push($nid_array_for_text_field, $row['nid']);
          }
          if($row['description'] !== NULL) {
          echo($row['description']);
         // array_push($content_type_array, $row['type']);
          }
          if($row['vid'] !== NULL) {
          echo($row['vid']);
         // array_push($field_text_field_name_value_array_all, $row[$table_text_field_name_value]);
          }
     }
      $dbh = null;
  } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
  }
