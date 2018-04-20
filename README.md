# tinyurl
This project is intended to generate a clean short URL from a long URL &amp; to find the hit count

Scope:
	We should be able to put a URL and get back a url of the shortest possible length.
	We should be redirected to the full URL when we click on the short URL, or copy paste that to browser
	There is a top 100 board with the most frequently accessed tiny URLs with counter

Further Scope:
	Algo to generate the short URL could be modified
	Redis or similar DB could give performance boost for searching unique string


- Technology PlatForm
	Fully open source utilizing following scripting language & DB
		PHP version: 7.2.3
		MariaDB version: 10.1.31

- Basic configurations
	Clone the project from development branch only
	Set $config['base_url'] in /application/config/config.php to your server base location to access the app
	The app utilizes .htaccess file, so your Apache's module mod_rewrite should be writable

- Database configurations
	This app by default utilizes mysqli driver and related DB credentials
	Set database credntials into /application/config/database.php
	Run migration for the project by accessing url - BASE_URL/migrate , it will automatically create all required
	tables for the app

All the dependencies i.e - AngularJS, Bootstrap, jQuery are within /assets folder in root so don't need to install any 
dependencies

