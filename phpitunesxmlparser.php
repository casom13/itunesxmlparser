<?php

$error = 0;

// DATABASE CONNECTION INFO //

// FIND iTUNES XML //

$xml = simplexml_load_file('../iTunes/iTunes Music Library.xml');

$songlist = array();

foreach($xml->dict->dict->dict as $child)
{
	$song = array();

	$i = 0;

	$tempatt = array();

	foreach($child->children() as $name => $node)
	{
		if ($i == 0){
			array_push($tempatt, $node);
			$i++;
			}
		elseif($i == 1){
			array_push($tempatt, $node);
			array_push($song, $tempatt);
			$tempatt = array();
			$i = 0;
			}
		else{
			echo "oops";
		}
	}

	array_push($songlist, $song);

}

foreach ($songlist as $song){
	$atts = '';
	$vals = '';
	$update = '';
	$numItems = count($song);
	$i = 0;
	foreach ($song as $att){

		if(++$i === $numItems) {
			$att[0] = str_replace (" ", "_", $att[0]);
			$att[1] = str_replace ("'", "\'", $att[1]);
    		$atts .= $att[0];
			$vals .= "'" . $att[1] . "'";
			$update .= $att[0] . "='" . $att[1] . "'";
  		}
  		else{
  			$att[0] = str_replace (" ", "_", $att[0]);
  			$att[1] = str_replace ("'", "\'", $att[1]);
			$atts .= $att[0] . ', ';
			$vals .= "'" . $att[1] . "'" . ', ';
			$update .= $att[0] . "='" . $att[1] . "', ";
		}
//		echo $att[0] . ': ' . $att[1] . '<br>';
	}

//	echo "INSERT INTO songs (".$atts.") Values(".$vals.")";

	$sql = mysql_query("INSERT INTO songs (".$atts.") Values(".$vals.") ON DUPLICATE KEY UPDATE ".$update."");

	if(!$sql){
		echo "Error with MySQL Query: ".mysql_error();
		$error = 1;
    }

}


if ($error == 0){
	echo "Songs Update Completed";
}



?>