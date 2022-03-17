<?php

include "/Users/tomgsmith99/projects/baseball_import_players/.env.php";

function getDBconn($dbname = "baseball") {

	$mysqli = new mysqli($GLOBALS["DB_HOST"], $GLOBALS["DB_USERNAME"], $GLOBALS["DB_PASSWORD"], $dbname, $GLOBALS["DB_PORT"]);

	if ($mysqli->connect_error) {
		echo "<p>could not connect to db.";

		die('Connect Error (' . $mysqli->connect_errno . ') '
			. $mysqli->connect_error);
	}
	else {
		echo "The db connection worked.\n";
	}

	return $mysqli;
}