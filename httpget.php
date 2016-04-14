<?php
include('./Requests/library/Requests.php');
Requests::register_autoloader();

$response = Requests::get('http://localhost:8080/marmotta/ldp/', array('Accept' => 'application/rdf+xml'));
var_dump($response->body);


