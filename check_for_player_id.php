<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "year.php";

include ".env.php";

include "includes/dbconn.php";

$dbconn = getDBconn();

// **************************************************************

echo "\nWhat is the player id? ";

$line = fgets(STDIN);

echo "\nthe input is: " . $line;

$player_id = intval($line);

// **************************************************************

$tables = array("ownersXrosters", "playersXowners", "playersXpoints", "playersXseasons", "player_current");

foreach ($tables as $key => $table) {

	$query = "SELECT * FROM $table WHERE player_id = $player_id";

	$result = mysqli_query($dbconn, $query);

	if (mysqli_error($dbconn)) {
		echo mysqli_error($dbconn);
		exit;
	}

	$num_rows = mysqli_num_rows($result);

	if ($num_rows == 0) {
		echo "\nplayer id " . $player_id . " not found in " . $table;
	}
	else {
		echo "\nFOUND player id $player_id in $table";
	}
}

echo "\n";

echo "\nDo you want to replace this player id? ";

$line = fgets(STDIN);

echo "\nthe input is: $line\n";

if (trim($line) == "y" || trim($line) == "Y") {
	echo "\nwe are going to delete player id $player_id\n";

	echo "\nWhat is the new player id? ";

	$line = fgets(STDIN);

	echo "\nthe input is: $line\n";

	$good_player_id = intval($line);

	foreach ($tables as $key => $table) {

		$query = "UPDATE $table SET player_id = $good_player_id WHERE player_id = $player_id";

		$result = mysqli_query($dbconn, $query);

		if (mysqli_error($dbconn)) {
			echo mysqli_error($dbconn);
			exit;
		}
	}

	$query = "UPDATE trades SET dropped_player_id = $good_player_id WHERE dropped_player_id = $player_id";

	$result = mysqli_query($dbconn, $query);

	if (mysqli_error($dbconn)) {
		echo mysqli_error($dbconn);
		exit;
	}

	$query = "UPDATE trades SET added_player_id = $good_player_id WHERE added_player_id = $player_id";

	$result = mysqli_query($dbconn, $query);

	if (mysqli_error($dbconn)) {
		echo mysqli_error($dbconn);
		exit;
	}
}

exit;
