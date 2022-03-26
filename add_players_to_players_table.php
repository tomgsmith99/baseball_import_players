<?php

include "year.php";

include "includes/dbconn.php";

$dbconn = getDBconn();

/*********************************/

$query = "SELECT * FROM player_pool";

$result = mysqli_query($dbconn, $query);

while ($row = mysqli_fetch_array($result)) {

	$player_id = $row["id"];
	$salary = $row["salary"];
	$team = $row["team"];
	$pos = $row["pos"];

	$query = "INSERT INTO playersXseasons SET player_id = $player_id, season = $year, salary = $salary, team = '$team', pos = '$pos'";

	echo $query . "\n";

	mysqli_query($dbconn, $query);

	if (mysqli_error($dbconn)) {
		echo mysqli_error($dbconn);
		exit;
	}
}

exit;
