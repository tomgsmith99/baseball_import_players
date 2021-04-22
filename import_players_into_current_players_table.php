<?php

include "year.php";

include "includes/dbconn.php";

$dbconn = getDBconn();

// **************************************************************

$query = "SELECT * FROM players_temp WHERE found = 0";

$result = mysqli_query($dbconn, $query);

if (mysqli_error($dbconn)) {
	echo mysqli_error($dbconn);
	exit;
}

$num_rows = mysqli_num_rows($result);

echo "the number of players not yet found in the players_temp table is: " . $num_rows . "\n";

while ($row = mysqli_fetch_array($result)) {

	$query = "SELECT * FROM players WHERE first_name = '" . $row["first_name"] . "' ";
	$query .= "AND last_name = '" . $row["last_name"] . "' ";
	$query .= "AND middle_initial";

	if ($row["middle_initial"] == '') {
		$query .= " IS NULL ";
	}
	else {
		$query .= "='" . $row["middle_initial"] . "' ";
	}

	$query .= "AND suffix";

	if ($row["suffix"] == '') {
		$query .= " IS NULL ";
	}
	else {
		$query .= "= '" . $row["suffix"] . "' ";
	}

	$query .= "AND active = 1";

	echo $query . "\n";

	$r = mysqli_query($dbconn, $query);

	if (mysqli_error($dbconn)) {
		echo mysqli_error($dbconn);
		exit;
	}

	$num_rows = mysqli_num_rows($r);

	echo "the number of matching rows is: " . $num_rows . "\n";

	if ($num_rows === 0) {

		echo "\n\n*********************\n";
		echo "could not find this player in the players table:\n";
		echo "\n";
		echo $row["first_name"] . " " . $row["last_name"];
		echo "\n";
		echo "do you want to add this player to the players table with the following query?\n";

		$query = "INSERT INTO players SET first_name='" . $row["first_name"] . "', ";
		$query .= "last_name='" . $row["last_name"] . "', ";
		$query .= "year_added=" . $year;

		echo $query . "\n";

		echo "y/n: ";

		$line = trim(fgets(STDIN));

		if ($line === "y") {
			mysqli_query($dbconn, $query);

			if (mysqli_error($dbconn)) {
				echo mysqli_error($dbconn);
				exit;
			}
			else {
				$player_id = mysqli_insert_id($dbconn);
				add_player_to_this_year_table(
					$player_id,
					$row["salary"],
					$row["team"],
					$row["pos"],
					$row["first_name"],
					$row["last_name"]
				);
			}
		}
	}

	if ($num_rows === 1) {

		$player = mysqli_fetch_array($r);

		add_player_to_this_year_table(
			$player["player_id"],
			$row["salary"],
			$row["team"],
			$row["pos"],
			$row["first_name"],
			$row["last_name"]
		);
	}

	if ($num_rows > 1) {
		echo "\n";
		exit;
	}
}

function add_player_to_this_year_table($player_id, $salary, $team, $pos, $first_name, $last_name) {
	global $dbconn;
	global $year;

	$query = "INSERT INTO players_current_temp SET ";
	$query .= "player_id=" . $player_id . ", ";
	$query .= "salary=" . $salary . ", ";
	$query .= "team='" . $team . "', ";
	$query .= "pos='" . $pos . "'";

	echo "the query is: " . $query . "\n";

	mysqli_query($dbconn, $query);

	if (mysqli_error($dbconn)) {
		echo mysqli_error($dbconn);
		exit;
	}

	$query = "UPDATE players_temp SET found = 1 WHERE";
	$query .= " salary=" . $salary;
	$query .= " AND team='" . $team . "'";
	$query .= " AND pos='" . $pos . "'";
	$query .= "AND first_name='" . $first_name . "' ";
	$query .= "AND last_name='" . $last_name . "'";

	echo "the query is: " . $query . "\n";

	mysqli_query($dbconn, $query);

	if (mysqli_error($dbconn)) {
		echo mysqli_error($dbconn);
		exit;
	}
}

exit;
