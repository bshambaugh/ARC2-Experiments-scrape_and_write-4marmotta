<?php
$sparqlupdate = new EasyRdf_Sparql_Client('http://localhost:8080/marmotta/sparql/update');

if ($dbg == 1) {
  echo "The created triples are:".'<br/>';
  foreach($subject as $i => $value) {
  echo $subject[$i].' '.$predicate[$i].' '.$object[$i].'<br/>';
  }

/*
  string = '<http://192.168.2.11/0329/taxonomy/term/301>';

  preg_match_all('/\/[0-9]*>/',$string,$matches);
  $strip1 = preg_replace('/\//','',$matches[0]);
  $strip2 = preg_replace('/>/','',$strip1[0]);
  $strip2;
  */
}

foreach ($subject as $key => $value) {
   echo ($subject[$key].' '.$predicate[$key].' '.$object[$key]);
   echo("\r\n");
   $data = '<'.$subject[$key].'>'.' ''<'.$predicate[$key].'>'.' '.'<'.$object[$key].'>';
   $resulttwo = $sparqlupdate->insert($data, $graphUri = null);
}


 ?>
