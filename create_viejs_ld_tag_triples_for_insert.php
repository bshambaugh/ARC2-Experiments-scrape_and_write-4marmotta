<?php
    /**
     * Making a SPARQL SELECT query
     *
     * This example creates a new SPARQL client, pointing at the
     * dbpedia.org endpoint. It then makes a SELECT query that
     * returns all of the countries in DBpedia along with an
     * english label.
     *
     * Note how the namespace prefix declarations are automatically
     * added to the query.
     *
     * @package    EasyRdf
     * @copyright  Copyright (c) 2009-2013 Nicholas J Humfrey
     * @license    http://unlicense.org/
     */

    set_include_path(get_include_path() . PATH_SEPARATOR . './easyrdf-0.9.0/lib/');
    require_once "./easyrdf-0.9.0/lib/EasyRdf.php";
  //  require_once "../html_tag_helpers.php";

    // Setup some additional prefixes for the Drupal Site
    EasyRdf_Namespace::set('schema', 'http://schema.org/');
    EasyRdf_Namespace::set('content', 'http://purl.org/rss/1.0/modules/content/');
    EasyRdf_Namespace::set('dc', 'http://purl.org/dc/terms/');
    EasyRdf_Namespace::set('foaf', 'http://xmlns.com/foaf/0.1/');
    EasyRdf_Namespace::set('og', 'http://ogp.me/ns#');
    EasyRdf_Namespace::set('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
    EasyRdf_Namespace::set('sioc', 'http://rdfs.org/sioc/ns#');
    EasyRdf_Namespace::set('sioct', 'http://rdfs.org/sioc/types#');
    EasyRdf_Namespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
    EasyRdf_Namespace::set('xsd', 'http://www.w3.org/2001/XMLSchema#');
    EasyRdf_Namespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    EasyRdf_Namespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    EasyRdf_Namespace::set('rss', 'http://purl.org/rss/1.0/');
    EasyRdf_Namespace::set('site', 'http://localhost/iksce/ns#');

    $sparql = new EasyRdf_Sparql_Client('http://localhost:8080/marmotta/sparql/');
?>

<?php
// Set debugging
function dbg($level, $message, $dbg) {
  if ($dbg = 1) {
      if ($level = 1) {
          echo $message;
      }
      elseif ($level = 2) {
          print_r($message);
      }
      elseif ($level = 3) {
         var_dump($message);
      }
  }
}

// Set debug to 1 for debugging
$dbg = 1;
?>

<?php
 // Initially assume that we do not need to add the Tag to the rdf store
 $newrdftag = "false";

 // Perform SELECT query on RDF store to populate array for all triples with schema:isRelatedTo predicate
 $result = $sparql->query(
    'PREFIX schema: <http://schema.org/>
     SELECT * { ?s schema:isRelatedTo  ?o .
     FILTER regex(?s, "isaportal3")  }'
 );

// Initialize itermediary storage array for subject, predicate, and object from query with schema:isRelatedTo predicate
 $subarray = array();
 $predarray = array();
 $objarray = array();

// Populate the storage arrays including the schema:isRelatedTo predicate
 foreach ($result as $row) {
     array_push($subarray, $row->s);
     array_push($predarray, "schema:isRelatedTo");
     array_push($objarray, $row->o);
 }

if ($dbg == 1) {
   echo "Triples with the schema:isRelatedTo predicate".'<br/>';
   foreach($subarray as $i => $value) {
      echo $subarray[$i].' '.$predarray[$i].' '.$objarray[$i].'<br/>';
   }
}


// Perform SELECT query on RDF store to populate array for all triples with rdfs:seeAlso predicate
 $result_two = $sparql->query(
 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
  SELECT * { ?s rdfs:seeAlso  ?o .
  FILTER regex(?s, "isaportal3")  }'
 );

// Initialize itermediary storage array for subject, predicate, and object from query with  predicate
 $subarray_two = array();
 $predarray_two = array();
 $objarray_two = array();

// Populate the storage arrays including the  predicate
 foreach ($result_two as $row) {
     array_push($subarray_two, $row->s);
     array_push($predarray_two, "rdfs:seeAlso");
     array_push($objarray_two, $row->o);
 }

if ($dbg == 1) {
   echo "Triples with the rdfs:seeAlso predicate".'<br/>';
   foreach($subarray_two as $i => $value) {
     echo $subarray_two[$i].' '.$predarray_two[$i].' '.$objarray_two[$i].'<br/>';
   }
}


// Initialize temporary mapping arrays for results with the schema:isRelatedTo predicate
 $mapsubjarray = array();
 $mappredarray = array();
 $mapobjarray = array();

// Populate the mapping arrays with results from the schema:isRelatedTo query
foreach($subarray as $i => $value) {
  array_push($mapsubjarray,$subarray[$i]);
}

foreach($predarray as $i => $value) {
  array_push($mappredarray,$predarray[$i]);
}

foreach($objarray as $i => $value) {
  array_push($mapobjarray,$objarray[$i]);
}

if ($dbg == 1) {
   echo "Triple Mappings with the schema:isRelatedTo predicate".'<br/>';
   foreach($mapsubjarray as $i => $value) {
     echo $mapsubjarray[$i].' '.$mappredarray[$i].' '.$mapobjarray[$i].'<br/>';
  }
}

// Initialize the mapping arrays for resuts with the rdfs:seeAlso predicate
$mapsubjarray_two = array();
$mappredarray_two = array();
$mapobjarray_two = array();

// Populate the mapping arrays with results from the rdfs:seeAlso query
foreach($subarray_two as $i => $value) {
  array_push($mapsubjarray_two,$subarray_two[$i]);
}

foreach($predarray_two as $i => $value) {
  array_push($mappredarray_two,$predarray_two[$i]);
}

foreach($objarray_two as $i => $value) {
  array_push($mapobjarray_two,$objarray_two[$i]);
}

if ($dbg == 1) {
   echo "Triple Mappings with the rdfs:seeAlso predicate".'<br/>';
   foreach($mapsubjarray_two  as $i => $value) {
     echo $mapsubjarray_two[$i].' '.$mappredarray_two[$i].' '.$mapobjarray_two[$i].'<br/>';
  }
}

// Remove mappings that have objects ($mapobjarray), in this case taxonomy term URIs, that match the subjects ($subarray_two)
// of rdfs:seeAlso triples. (e.g.  http://localhost/iksce/node/16 schema:isRelatedTo ?http://localhost/iksce/taxonomy/term/1
// , http://localhost/iksce/taxonomy/term/1 rdfs:seeAlso http://dbpedia.org/resource/Quiet_Riot )
foreach( $mapobjarray as $i => $value_one) {
  foreach($mapsubjarray_two as $j => $value_two) {
    if( $mapobjarray[$i] == $mapsubjarray_two[$j]) {
       if ($dbg == 1) {
       echo "map objarray is: ".$mapobjarray[$i]." sub_array_two is: ".$subarray_two[$j]."We are equal: ".'<br/>';
       }
       unset($mapobjarray[$i]);
       unset($mappredarray[$i]);
       unset($mapsubjarray[$i]);
    } elseif ($mapobjarray[$i] !== $mapsubarray_two[$j]) {
       if ($dbg == 1) {
       echo "map objarray is: ".$mapobjarray[$i]." sub_array_two is: ".$subarray_two[$j]."We are not equal: ".'<br/>';
       }
    }
  }
}

// Count the number of elements in each array to indicate elimination of schema:isRelatedTo array mappings from
// the preceding code
$count_objarray = count($objarray);
$count_subarray_two = count($subarray_two);

if ($dbg == 1) {
echo "==== Counts of arrays for schema:isRelatedTo predicate that have a rdfs:seeAlso predicate with the taxonomy term".'<br/>';
echo "count_objarray for schema:isRelatedTo is: ".$count_objarray.'<br/>';
echo "count_subarray_two for rdfs:seeAlso is: ".$count_subarray_two.'<br/>';
}

// Return a value of true if there are triples that have a schema:isRelatedTo predicate
// and taxonomy term object, but no triple that has the same taxonomy term as a subject and
// a rdfs:seeAlso predicate
if($count_subarray_two < $count_objarray) {
  $newrdftag = "true";
}


if ($dbg == 1) {
    echo "Mapping array with schema:isRelatedTo predicate after ones with related rdfs:seeAlso predicate removed".'<br/>';
    foreach($mapsubjarray as $i => $value) {
      echo $mapsubjarray[$i].' '.$mappredarray[$i].' '.$mapobjarray[$i].'<br/>';
    }
}


  //  $sparql = new EasyRdf_Sparql_Client('http://192.168.2.11/0329/isp_data_endpoint');
$torwmapobjarray = array_unique($mapobjarray);

 foreach($torwmapobjarray as $i => $value) {
   echo $torwmapobjarray[$i];
   echo("\r\n");
 }

/*
 echo('new mapping here');
 echo("\r\n");
 // rewrite the map obj array so that it hopefully is resolvable
 foreach($torwmapobjarray as $i => $value) {
   $new_mapobjarray[$i] = preg_replace('/taxonomy_term/i','taxonomy/term', $torwmapobjarray[$i]);
   if($dbg == 1) {
     echo($new_mapobjarray[$i]);
     echo("\r\n");
   }
 }
*/


// Execute only if true
 if ($newrdftag === "true") {

// Remove the declaration of the array outside of the foreach loop
   $subject = array();
   $predicate = array();
   $object = array();

 foreach($torwmapobjarray as $i => $value) {
   //  Dereference the taxonomy term URI in mapobjarray and remove markup
   $html = implode('', file($torwmapobjarray[$i]));
   $naked = strip_tags($html);
   if ($dbg == 1) {
  //   echo "The html without the tags for ".$mapobjarray[$i]." is:".'<br/>';
  //   echo $naked;
   }


   // Locate URI in result
    $pattern = "/URI:.*http:\/\/.* /";

      $input_str = $naked;

//      $subject = array();
//      $predicate = array();
//      $object = array();

     if (preg_match_all($pattern, $input_str, $matches_out)) {
        $p = $matches_out[0];
        $withComma = implode(" ", $p);

        // Select only the URI from the page and remove surrounding whitespace
       $regex = "/URI:&nbsp;/";
       $new_string = preg_replace($regex, "$2 $1", $withComma);
       $new_string = preg_replace('/\s+/i', '', $new_string);
       // Bind result to subject, predicate, and object arrays that together
       // form triples of the form "taxonomy term" rdfs:seeAlso "dbpedia or other referenced URI from the taxonomy page"
       array_push($subject,'<'.$mapobjarray[$i].'>');
       array_push($predicate,'<http://www.w3.org/2000/01/rdf-schema#seeAlso>');
       array_push($object,'<'.$new_string.'>');
 }
}

if ($dbg == 1) {
  echo "The created triples are:".'<br/>';
  foreach($subject as $i => $value) {
  echo $subject[$i].' '.$predicate[$i].' '.$object[$i].'<br/>';
  }
}

}



?>
