<?php
// Backend for Lincoln Community Watch.
// [c] hako 2016

// Twitter Tokens (Place in ENV)

const CONSUMER_KEY = "";
const CONSUMER_SECRET = "";
const ACCESS_TOKEN = "";
const ACCESS_TOKEN_SECRET = "";

require __DIR__  . '/../vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

// Initialise Twitter auth and parse Twitter data.
Flight::map('pullTwitterData', function() {
	// Tags we are looking for.
	$tags = [
		"q" => "#",
		"geocode" => "53.2281204811426,-0.554566456479403,5m", // Lincoln
		"count" => "100",
	];
	$auth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
	$results = $auth->get("search/tweets", $tags);
	return $results;
});

// Initialise Instagram auth and parse Instagram data.
// Flight::map('pullInstagramData', function() {
// });


// Main route.
Flight::route('GET /', function() {
	//$results = Flight::pullTwitterData();
	include "index.html";
});

// Start Flight.
Flight::start();
?>