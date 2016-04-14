<?php
/* ARC2 static class inclusion */
include_once('../arc2/ARC2.php');

/* configuration */
$config = array(
  /* remote endpoint */
  'remote_store_endpoint' => 'http://localhost/iksce/sparql',

);

/* instantiation */
$store = ARC2::getRemoteStore($config);

if (!$store->isSetUp())
  $store->setUp();

  // Set debug to 1 for debugging
  $dbg = 1;

foreach($subject as $i => $value) {

$query = 'INSERT  INTO <http://localhost/iksce/sparql> {'.$subject[$i].' '.$predicate[$i].' '.$object[$i].' .}';
if ($dbg == 1) {
  echo "The subject is: ".$subject;
  echo "The predicate is: ".$predicate;
  echo "The object is: ".$object;
}


$res = $store->query($query);

if ($dbg == 1) {
echo var_dump($store->getErrors());
echo "<br><br>executed INSERT, returned: ";
echo var_dump($res);
}


}


?>
