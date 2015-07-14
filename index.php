<?php
require __DIR__ . '/vendor/autoload.php';

$imagine = new Imagine\Imagick\Imagine();

$app = new \Slim\Slim();

$app->get('/', function () {
    // render index
});

$app->get('/images', function() use ($app) {
   $files = \GabGallery\ImageFiles::getFiles();
   $app->response->headers->set('Content-Type', 'application/json');
   $app->response->setBody(json_encode($files));
});

$app->get('/image/:id(/:info)', function($id, $info = false) {
    echo "hi!";
})->conditions(['info' => '(basic|exif)']);

$app->run();