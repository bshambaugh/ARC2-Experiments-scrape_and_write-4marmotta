<?php
$string = '<http://192.168.2.11/0329/taxonomy/term/301>';

preg_match_all('/\/[0-9]*>/',$string,$matches);
$strip1 = preg_replace('/\//','',$matches[0]);
$strip2 = preg_replace('/>/','',$strip1[0]);
//print_r($matches);
//print_r($strip1);
//print_r($strip2);
$strip2;
