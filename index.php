<?php
require 'vendor/autoload.php';

$imagine = new Imagine\Imagick\Imagine();

spl_autoload_register(function ($class) {
    include 'library/' . $class . '.class.php';
});

Flight::register('imageFiles', 'ImageFiles');

Flight::route('GET /', function() {
    // render index
});

Flight::route('GET /images', function() {
   $files = Flight::imageFiles()->getFiles();
   Flight::json($files);
});

Flight::start();