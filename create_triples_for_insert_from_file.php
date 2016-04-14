<?php
// read in the input file
$dbg = 0;
$inputfile = "./subject_object.txt";
$handle = fopen($inputfile,'r');
echo($handle);
$data = fread($handle, filesize($inputfile));

// split the input file data into array elements split by the newline character
$columns = array();
$pieces = explode("\n", $data);
// build a multidimensional array by splitting by spaces in each element
foreach($pieces as $key => $value) {
// echo $pieces[$key];
     $columns[$key] = preg_split('/\s+/i', $pieces[$key]);
// echo ("\r\n");
}

// remove the last element of the array since it is empty due the the newline split
$colshort = array_slice($columns, 0, count($columns) - 1);
// remove the last new line
//print_r($columns);
//print_r($colshort);

// declare storage arrays for the subject, predicat, and object
$subject = array();
$predicate = array();
$object = array();

// split the multidimensional array into subject and objects, and add a predicate
foreach($colshort as $a => $b) {
 foreach($b as $c => $d) {
//  echo($columns[$a][$c]);
// echo($c);
 if($c == 0) {
  //  echo "The subject is".$columns[$a][$c];
  array_push($subject,'<'.$colshort[$a][$c].'>');
 }
 if($c == 1) {
  //  echo "The object is".$columns[$a][$c];
     array_push($object,'<'.$colshort[$a][$c].'>');
 }
    array_push($predicate,'<http://www.w3.org/2000/01/rdf-schema#seeAlso>');
 echo("\r\n");
 }
}

print_r($subject);
print_r($object);

if($dbg == 1) {
foreach($subject as $key => $value) {
  echo $subject[$key].' '.$predicate[$key].' '.$object[$key];
//  echo $subject[$key].' '.'rdfs:seeAlso'.' '.$object[$key];
  echo ("\r\n");

  
}
}
