<?php
return array(
	'connectionString' => 'mysql:host=' . $_ENV['DB_HOST'] .';dbname=' . $_ENV['DB_NAME'],
	'emulatePrepare' => true,
	'username' => $_ENV['DB_USER'],
	'password' => $_ENV['DB_PASSWORD'],
	'charset' => 'utf8',
);