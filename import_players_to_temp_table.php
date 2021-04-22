<?php

include "year.php";

include "includes/dbconn.php";

$dbconn = getDBconn();

// open players.txt file
// each row in the players.txt file should look like this:
// C	MOLINA	YADIER	960	STL

$players = file("players.txt");

$text_file_num_rows = count($players);

echo "the number of rows in the players.txt file is: " . $text_file_num_rows . "\n";

$query = "SELECT * FROM players_temp";

$result = mysqli_query($dbconn, $query);

if (mysqli_error($dbconn)) {
	echo mysqli_error($dbconn);
	exit;
}

$num_rows = mysqli_num_rows($result);

echo "the number of rows in the players_temp table is: " . $num_rows . "\n";

if ($num_rows != 0) {
	echo "The players_temp table is not empty. Please empty the table before running the script.\n";
	exit;
}

echo "The players_temp table is empty, so we are going to start populating that table.\n";

echo "OK to continue?\n";

echo "y/n: ";

$line = trim(fgets(STDIN));

if ($line != "y") {
	exit;
}

$number_of_fields = 5;

foreach ($players as $index => $row) {

	$fields = explode("\t", trim($row));

	echo "the row is: " . json_encode($fields) . "\n";

	if (count($fields) != 5) {
		echo "this player does not have the right number of fields. Please fix before proceeding.\n";
		echo "TIP: if this is a JR., add the player to the temp table manually and delete from the players.txt file, then re-run.\n";
		exit;
	}

	$pos = $fields[0];

	$last_name = $fields[1];

	$first_name = $fields[2];

	$salary = $fields[3];

	$team = $fields[4];

	$first_name = mysqli_real_escape_string($dbconn, ucfirst(strtolower($first_name)));
	$last_name = mysqli_real_escape_string($dbconn, ucfirst(strtolower($last_name)));

	$query = "INSERT INTO players_temp ";
	$query .= "SET last_name='" . $last_name . "', ";
	$query .= "first_name='" . $first_name . "', ";
	$query .= "pos='" . $pos . "', ";
	$query .= "salary=" . $salary . ", ";
	$query .= "team='" . $team . "'";

	echo $query . "\n";

	$result = mysqli_query($dbconn, $query);

	if (mysqli_error($dbconn)) {
		echo mysqli_error($dbconn);
		exit;
	}
}

exit;
