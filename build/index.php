<?php
// Backend for Lincoln Community Watch.
// [c] hako 2016

require __DIR__  . '/../vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;
use \Colors\RandomColor;

// Default Timezone
date_default_timezone_set('Europe/London');

// Load dev environment.
$dotenv = new Dotenv\Dotenv(__DIR__  . '/../');
$dotenv->load();

// Initialise Twitter auth and parse Twitter data.
Flight::map('pullTwitterData', function() {

	// Twitter tokens.
	$consumer_key = getenv("CONSUMER_KEY");
	$consumer_secret = getenv("CONSUMER_SECRET");
	$access_token = getenv("ACCESS_TOKEN");
	$access_token_secret = getenv("ACCESS_TOKEN_SECRET");

	// Tags we are searching for in Lincoln.
	$tags = [
		"q" => "#LincolnCommunityWatch",
		//"geocode" => "53.2281204811426,-0.554566456479403,5m", // Lincoln
		"count" => "100",
	];
	$auth = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
	$results = $auth->get("search/tweets", $tags);
	return $results;
});

// Main display route.
Flight::route('GET /', function() {
	include "index.html";
});

// Stats display route.
Flight::route('GET /stats', function() {
	include "statistics.html";
});

// API data route.
Flight::route('GET /api/fetch_data', function() {
	$results = Flight::pullTwitterData();
	$status = (array)$results->statuses;
	$counter = 0;

	$matched_status = [
			"type" => "FeatureCollection",
			"features" => [
				[
				],
			]
		];

	// Loop over statuses.
	foreach ($status as $s) {
		$data = [
		"type" => "Feature",
			"id" => $counter,
			"properties" => [
				"Description" => "",
				"Category" => "",
				"Date" => "",
				"User" => "",
				"Avatar" => "",
				"Image" => "",
				"Link" => ""
				],
			"geometry" => [
				"type" => "Point",
				"coordinates" => [
				0,
				0
				]
			]
			];

		// Map properties to prepared array.
		$property_data = fmtproperties($s->text);
		$data["properties"]["Description"] = $property_data["description"];
		$data["properties"]["Category"] = $property_data["category"];
		$data["properties"]["User"] = $s->user->screen_name;
		$bigger = str_replace("_normal", "", $s->user->profile_image_url_https);
		$data["properties"]["Avatar"] = $bigger;
		if (property_exists($s->entities, "media") == false) {
			$data["properties"]["Image"] = "";
			$data["properties"]["Link"] = "";
		} else {
			$data["properties"]["Image"] = $s->entities->media[0]->media_url_https;
			$data["properties"]["Link"] = $s->entities->media[0]->url;
		}
		$data["properties"]["Date"] = $s->created_at;
		$position = explode(",", $property_data["position"]);

		// Coordinates. (Convert to float)
		$lat = (float) $position[0];
		$lng = (float) $position[1];

		// Geometry
		$data["geometry"]["coordinates"][0] = $lat;
		$data["geometry"]["coordinates"][1] = $lng;

		// Add the array to another array.
		$matched_status["features"][$counter] = $data;
		$counter += 1;
	}
	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin: *');
	echo json_encode($matched_status);
});

// API stats route.
Flight::route('GET /api/stats', function() {
	// Check query.
	$q = Flight::request()->query->s;

	if ($q == null) {
		Flight::stop(400,'Bad Request');
	}

	switch ($q) {
		case 'categories':
			categories();
			break;

		case 'activities':
			activities();
			break;

		case 'mau':
			mau();
			break;
		
		default:
			Flight::stop(400,'Bad Request');
			break;
		}
});

// API 'faux data' route.
Flight::route('GET /api/fetch_faux_data', function() {
	// Check query.
	$n = Flight::request()->query->n;

	if ($n == null) {
		Flight::stop(400,'Bad Request');
	}

	// Fake profiles of size n.
	$faux_profiles = faux_data((int)$n);

	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin: *');
	echo json_encode($faux_profiles);
});

// Formats a description contained in a tweet.
function fmtproperties($text) {
	$rep = explode(", ", str_replace("#LincolnCommunityWatch", "", $text));
	
	$category = trim(explode(":", $rep[0])[1]);
	$description = trim(explode(":", $rep[1])[1]);
	$position_data =  explode(" ", $rep[2]);
	$position = $position_data[1];

	$property_data = [
		"category" => $category,
		"description" => $description,
		"position" => $position,
	];

	return $property_data;
}

// Creates some faux (fake) data, call it 'reinforcements' in a hackathon if you will.
function faux_data($n) {

	// Default is 0.
	if($n == 0) {
		$n = 1;
	}

	// Collection of Profiles.
	$profiles = [
	];

	for ($i=0; $i < (int)$n; $i++) { 

		$faker = Faker\Factory::create();
		$picture = "";

		// Random Gender
		$gender = $faker->randomElements(array(
			"Male", "Female"
		));

		$gender = $gender[0];
		$faker->title = $faker->title($gender);
		$faker->name($gender);
		$faker->firstName($gender);

		if ($gender == "Female") {
			$result = file_get_contents("http://api.randomuser.me/?inc=picture&gender=female&noinfo");
			$picture = json_decode($result)->results[0]->picture->large;
		} else {
			$result = file_get_contents("http://api.randomuser.me/?inc=picture&gender=male&noinfo");
			$picture = json_decode($result)->results[0]->picture->large;
		}

		// Random Categories.
		$category = $faker->randomElements(array(
			"Brayford", "High Street", ucfirst($faker->word), "Houses", "Other", "People", "Community", "Puzzling", "Education",
			"Animal Welfare", "Animals", "Food", "Health", ucfirst($faker->word), "Littering", "Assault", "Political", "Uncategorized", "Common", ucfirst($faker->word), 
		));

		// Random Username.
		$username = $faker->userName;

		// Random Description.
		$description = $faker->sentence(6, true);

		// Random Date.
		$date = $faker->dateTimeThisMonth('now')->format('Y-m-d H:i:s');

		// Build profile.
		$profile = [
		"type" => "Feature",
			"id" => $i,
			"properties" => [
				"Description" => $description,
				"Category" => $category[0],
				"Date" => $date,
				"User" => $username,
				"Avatar" => $picture,
				"Image" => "http://source.unsplash.com/collection/376921",
				"Link" => "http://source.unsplash.com/collection/376921"
				],
			"geometry" => [
				"type" => "Point",
				"coordinates" => [
				$faker->randomFloat($nbMaxDecimals = 5, $min = -0.55023, $max = -0.55923),
				$faker->randomFloat($nbMaxDecimals = 5, $min = 53.23037, $max = 53.20437),
				]
			]
			];

		// Change geometry to random coordinates.
		$profiles[$i] = $profile;
	}

	return $profiles;
}

// Activities stats.
function activities() {
	$results = Flight::pullTwitterData();

	// Query System, stats holder.
	$status = (array)$results->statuses;
	$counter = 0;

	$activities = [
		 "labels" => [

	    ],
	    "datasets" => []
	];

	$activities["datasets"]["label"] = "Activites Per User / day";
	$activities["datasets"]["backgroundColor"] = [];
	$activities["datasets"]["borderColor"] = [];
	$activities["datasets"]["borderWidth"] = 1;

	// Loop over activities.
	foreach ($status as $s) {
		// Generate a Random color.
		$color = RandomColor::one();

		// Generate second Random color.
		$second_color = RandomColor::one();

		$activities["labels"][$counter] = $s->user->screen_name;
		$activities["datasets"]["backgroundColor"][$counter] = $color;
		$activities["datasets"]["borderColor"][$counter] = $second_color;
		$counter += 1;
	}

	$activities["datasets"]["data"] = array_values(array_count_values($activities["labels"]));
	$activities["labels"] = array_values(array_unique($activities["labels"]));
	echo json_encode($activities);
}

// Categories stats.
function categories() {
	$results = Flight::pullTwitterData();

	// Query System, stats holder.
	$status = (array)$results->statuses;
	$counter = 0;

	$stats = [
		 "labels" => [

	    ],
	    "datasets" => []
	];

	$stats["datasets"]["backgroundColor"] = [];
	$stats["datasets"]["hoverBackgroundColor"] = [];

	// Loop over stats.
	foreach ($status as $s) {
		// Generate a Random color.
		$color = RandomColor::one();

		// Generate second Random color.
		$second_color = RandomColor::one();

		$property_data = fmtproperties($s->text);
		$category = $property_data["category"];
		$stats["datasets"]["backgroundColor"][$counter] = $color;
		$stats["datasets"]["hoverBackgroundColor"][$counter] = $second_color;
		$stats["labels"][$counter] = $category;
		$counter += 1;
	}
	$stats["datasets"]["data"] = array_values(array_count_values($stats["labels"]));
	$stats["labels"] = array_values(array_unique($stats["labels"]));

	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin: *');
	echo json_encode($stats);
}

// Most Active User stats.
function mau() {
	$results = Flight::pullTwitterData();

	// Query System, stats holder.
	$status = (array)$results->statuses;
	$counter = 0;

	$mau = [
		 "labels" => [
	    ],
	    "datasets" => []
	];

	$mau["datasets"]["label"] = "Most Active User";
	$mau["datasets"]["borderWidth"] = 1;

	// Basic ranking system.
	$rank = [
		"name" => "",
		"score" => 0,
	];

	// Loop over the most active user.
	foreach ($status as $s) {
		$mau["labels"][$counter] = $s->user->screen_name;
		$mau["datasets"]["backgroundColor"] = [];
		$mau["datasets"]["borderColor"] = [];
		$counter += 1;
	}

	// Count all instances of users contributions.
	$mau["datasets"]["data"] = array_values(array_count_values($mau["labels"]));
	$mau["labels"] = array_values(array_unique($mau["labels"]));

	// Loop over all the users to find the most active one.
	for ($i = 0; $i < count($mau["labels"]); $i++) { 
		 if ($mau["datasets"]["data"][$i] > $rank["score"]) {
		 	$rank["score"] = $mau["datasets"]["data"][$i];
		 	$rank["name"] = $mau["labels"][$i];
		 }
	}
	
	// Generate a Random colour.
	$color = RandomColor::one();

	// Generate second Random colour.
	$second_color = RandomColor::one();

	// Background & border colour for graph stats.
	$mau["datasets"]["backgroundColor"] = [$color];
	$mau["datasets"]["borderColor"] = [$second_color];

	// Only get the Most Active User(s)
	$mau["datasets"]["data"] = $rank["score"];
	$mau["labels"] = $rank["name"];

	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin: *');
	echo json_encode($mau);
}

// Start Flight.
Flight::start();
?>