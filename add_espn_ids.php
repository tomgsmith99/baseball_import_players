<?php

include "year.php";

include "includes/dbconn.php";

$dbconn = getDBconn();

/*********************************/

$query = "SELECT * FROM players_current_view WHERE espn_stats_id = 0 LIMIT 100";

$result = mysqli_query($dbconn, $query);

while ($row = mysqli_fetch_array($result)) {

	$found = 0;

	$player_id = $row["player_id"];

	$lname = str_replace(" ", "+", $row["last_name"]); // Take care of last names like "De La Rosa"

	$url = "http://www.google.com/search?as_q=" . $row["first_name"] . "+" . $lname . "+" . "espn";

	echo "search url: " . $url . "\n";

	$results_page = file_get_contents($url);

	if ($results_page) {

		$urls = array(
			"https://www.espn.com/mlb/player/_/id/",
			"http://espn.go.com/mlb/player/stats/_/id/",
			"http://espn.go.com/mlb/player/_/id/",
			"http://espn.go.com/mlb/player/splits/_/id/"
		);

		foreach ($urls as $espn_url) {
			$a = explode($espn_url, $results_page);

			if (count($a) > 1) {
				$found = 1;
				break;
			}
		}

		if ($found) {

			$espn_id = substr($a[1], 0, 5);

			echo "The espn_id is: " . $espn_id . "\n";

			$query = "UPDATE players SET espn_stats_id = " . $espn_id . " WHERE player_id = " . $player_id;

			mysqli_query($dbconn, $query);
		}
		else {
			echo "COULD NOT FIND A PLAYER ID\n";
		}
	}
	else {
		echo "There was a problem getting the player page.";
	}
	echo "*************************************\n";
}

exit;
