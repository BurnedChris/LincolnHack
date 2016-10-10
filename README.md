# Lincoln Hack - Lincoln Community Watcher

![Screenshot](screenshot.png)
## Demo
[Lincoln Community Watcher Demo](https://burnsy.github.io/LincolnHack/)
## Description 

Our hack *'Lincoln Community Watcher)* won the [Epix Media](https://www.epixmedia.co.uk/blog/up-all-night-to-get-hacky/) challenge. It was made during [Lincoln Hack](lincolnhack.org) 2016, a 24hr hackathon in Lincoln, England. The hack was started out of the challenge to **"Hack for the good of a community or group of people"**.

Using the [Twitter API](https://dev.twitter.com) as a resource for our data, we created a map based system that renders markers curated upon the location of tweets around Lincoln. If the user has uploaded an image on Twitter, the image will be displayed inside the marker, otherwise just the tweet will be displayed on the map.

Statistics about these tweets can be viewed in the stats tab. Categories, Activity and the Most Contributing User (MCU) are shown to inform users and to visualise what the community is interested in or concerned about in Lincoln. 

### Tweet Template

To have your say in the community, format your message as the following (without quotes)

"#LincolnCommunityWatch Category: [Category], Description: [Description], Position: [Longitude],[Latitude]"

You can optionally add a picture to your tweet, below is an example tweet for reference.

[#LincolnCommunityWatch Category: Graffiti, Description: Graffiti on wall, Position: -0.545291,53.227254](https://twitter.com/BurnedByChris/status/785084760586653696/photo/1)

Make sure the hashtag [**#LincolnCommunityWatch**](https://twitter.com/hashtag/LincolnCommunityWatch?src=hash) is present in the tweet, otherwise it won't show on the map!

## Requirements

If you would like to run this project yourself, you must have the following installed:

- [Node](https://nodejs.org/) & [npm](https://www.npmjs.com/)
- PHP (or [hhvm](https://hhvm.com))
- [Composer](https://getcomposer.org)

This project makes use of **Twitter's API**, so make sure you [sign up](https://dev.twitter.com) for a developer account to be able to use this project!

### Getting Started in 6 steps!

Run the following commands:

1. `git clone https://github.com/Burnsy/lincolnhack.git` or [download the project](https://github.com/Burnsy/LincolnHack/archive/master.zip)
2. `cd lincolnhack`
3. `npm install`
4. `composer install`
5. Once you've installed everything, just edit the `.env` file^1 and replace the following:

	`CONSUMER_KEY = "..."`
	`CONSUMER_SECRET = "..."`
	`ACCESS_TOKEN = "..."`
	`ACCESS_TOKEN_SECRET = "..."`

	With your own Twitter API tokens.
6. `php -S localhost:5000 -t build`

Your very own community watch page [should be live here.](http://localhost:5000)

^1 *Alternatively, if you want to deploy this onto a real server, instead of editing the `.env` file, just
set the tokens above on your server as environment variables, and set `PRODUCTION = TRUE`.*


### LICENSE

MIT
