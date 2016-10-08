<?php
// Backend for Lincoln Community Watch.
// [c] hako 2016

require __DIR__  . '/../vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

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

// Initialise Instagram auth and parse Instagram data.
// Flight::map('pullInstagramData', function() {
// });


// Main route.
Flight::route('GET /', function() {
	include "index.html";
});

// API route.
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
		$data["properties"]["Image"] = $s->entities->media[0]->media_url_https;
		$data["properties"]["Link"] = $s->entities->media[0]->url;
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

// Start Flight.
Flight::start();
?>