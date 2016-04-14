<?php
include('./Requests/library/Requests.php');
Requests::register_autoloader();
//  $fp = fopen('./'.$filename,'w');


foreach($subject as $key => $value) {
 // echo $subject[$key];
  preg_match_all('/\/[0-9]*>/',$subject[$key],$matches);
  $strip1 = preg_replace('/\//','',$matches[0]);
  $strip2 = preg_replace('/>/','',$strip1[0]);
  $striptotag = '<#'.$strip2.'>';
//  echo $strip2;
  $subject_taxid[$key] = $striptotag;
//  echo "\r\n";
}

// This is temporary for now. Use pattern <http://192.168.2.11/0329/taxonomy/term/194> <http://schema.org/name> 'Database'
foreach($object as $key => $value) {
 // echo $subject[$key];
  preg_match_all('/\/[A-Za-z0-9-_%\(\)]*>/',$object[$key],$objmatches);
  $objstrip1 = preg_replace('/\//','',$objmatches[0]);
  $objstrip2 = preg_replace('/>/','',$objstrip1[0]);
  $objstriptotag = '\''.$objstrip2.'\'';
//  echo $objstriptotag;
  $objject_taxid[$key] = $objstriptotag;
//  echo "\r\n";
}

foreach($subject as $key => $value) {
  $triplearray[$key] = $subject_taxid[$key].' '.$predicate[$key].' '.$object[$key].' .'."\n".
     $subject_taxid[$key].'  '.'<http://www.w3.org/2002/07/owl#sameAs>'.' '.$subject[$key].' .'."\n".
     $subject_taxid[$key].'  '.'<http://purl.org/dc/terms/title>'.'  '.$objject_taxid[$key].' .';
//  echo $subject[$key].' '.'rdfs:seeAlso'.' '.$object[$key];
}

/*
foreach($subject as $key => $value) {
  $triplearray[$key] = $subject[$key].' '.$predicate[$key].' '.$object[$key].' .';
//  echo $subject[$key].' '.'rdfs:seeAlso'.' '.$object[$key];
}
*/

/*
foreach ($subject_taxid as $key => $value) {
  echo $subject_taxid[$key].'>';
}
*/

foreach($triplearray as $key => $value) {
 // echo $triplearray[$key];
}

/*
foreach($subject as $key => $value) {
  $skosmembers[$key] = '<http://www.w3.org/2004/02/skos/core#member>'.' '.$subject[$key].' ;';
}
*/

foreach($subject as $key => $value) {
  $skosmembers[$key] = '<http://www.w3.org/2004/02/skos/core#member>'.' '.$subject_taxid[$key].' ;';
}


$skosmembersstringpre = implode("\n",$skosmembers);
$skosmembersstring = substr($skosmembersstringpre, 0, -2);
//echo '<> '.$skosmembersstring.' .';

// perhaps an element from the subject array can be fed into the string
// varibale and a number can be pulled out. If it does not have a form
// where the number exists than pehaps the url alias sql table has a hint.
/*
  string = '<http://192.168.2.11/0329/taxonomy/term/301>';

  preg_match_all('/\/[0-9]*>/',$string,$matches);
  $strip1 = preg_replace('/\//','',$matches[0]);
  $strip2 = preg_replace('/>/','',$strip1[0]);
  $strip2;

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
            // $label = $row['name'];
        //  array_push($nid_array_for_text_field, $row['nid']);
          }
          if($row['description'] !== NULL) {
          echo($row['description']);
          $comment = $row['description'];
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
  */
$label = 'Tags';
$rdfslabel = '\''.$label.'\'';
$comment = '\'Use tags to group articles on similar topics into categories\'';

//echo '<>'.' '.'rdfs:label'.' '.$label.' ;'."\n".'rdfs:comment'.' '.$comment."\n".' '.$skosmembersstring.' .';

// $skos = '@prefix skos: <http://www.w3.org/2004/02/skos/core#> .';
// $rdfs = '@prefix rdfs: <http://www.w3.org/2000/01/rdfschema#> .';
// $rdf = '@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .';

 $prefixes = array('@prefix skos: <http://www.w3.org/2004/02/skos/core#> .','@prefix rdfs: <http://www.w3.org/2000/01/rdfschema#> .','@prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .');
 $prefixesn = implode("\n",$prefixes);
//  echo ($prefixesn);

 $triplesstring = implode("\n",$triplearray);
// echo $withcommapre;
// var_dump($withcommapre);
// $triplesstring = substr($triplespre, 0, -2);
// echo $withcommamid;
//$withcomma = $prefixesn."\n".'<>'."\n".$triplesstring;
$data = '<>'.' '.'<http://purl.org/dc/terms/title>'.' '.$rdfslabel.' ;'."\n".'<http://www.w3.org/1999/02/22-rdf-syntax-ns#type>'.' '.'<http://www.w3.org/2004/02/skos/core#Collection>'.' ;'."\n".'<http://www.w3.org/2000/01/rdfschema#comment>'.' '.$comment.' ;'."\n".$skosmembersstring.' .'."\n".$triplesstring;
echo $data;


$filename = $label.'.ttl';
 $fp = fopen('./'.$filename,'w');
	// echo $withcomma;

 fwrite($fp, $data);
  fclose($fp);


 pushandput($filename);


function pushandput ($inputfile) {
$containertitle = preg_replace('/\.ttl/','',$inputfile);
$url = 'http://localhost:8080/marmotta/ldp/';
$headers = array('Content-Type' => 'text/turtle','Slug' => $containertitle);
$response = Requests::post($url, $headers);
var_dump($response->body);
$handle = fopen($inputfile,'r');
echo($handle);
$data = fread($handle, filesize($inputfile));
echo($data);
$url = 'http://localhost:8080/marmotta/ldp/'.$containertitle;
$existingheaders = get_headers($url);
print_r($existingheaders);
echo($existingheaders[5]);
$etag = preg_replace('/ETag: /i','',$existingheaders[5]);
echo("\n");
echo($etag);
echo("\n");
$headers = array('Content-Type' => 'text/turtle','If-Match' => $etag,'Slug' => $containertitle);
//$headers = array('Content-Type' => 'text/turtle','If-Match' => 'W/"1459004153000"','Slug' => 'Penguins are Awesome');
$response = Requests::put($url, $headers, $data);
//$response = Requests:_put($url, $headers, json_encode($data));
var_dump($response->body);
fclose($handle);
}
