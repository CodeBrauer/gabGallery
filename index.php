<?php
require __DIR__ . '/vendor/autoload.php';

$imagine = new Imagine\Imagick\Imagine();
$app     = new \Slim\Slim();

$app->get('/', function () {
    // render index
});

$app->get('/images', function() use ($app) {
   $files = ImageFiles::getFiles();
   $app->response->headers->set('Content-Type', 'application/json');
   $app->response->setBody(json_encode($files));
});

$app->get('/images/:id(/:info)', function($id, $info = false) use ($app, $imagine) {
    if ($info !== false) {
        $app->response->headers->set('Content-Type', 'application/json');
        $info = ImageFiles::getDetail($id, $info);
        $app->response->setBody(json_encode($info));
    } else {
        // set mime type
        $mimeType = ImageFiles::getMime($id);
        $app->response->headers->set('Content-Type', $mimeType);
        
        // output raw image data
        $filename = ImageFiles::getPathName($id);
        echo file_get_contents($filename);
    }
})->conditions(['info' => '(basic|exif)']);

$app->get('/newest(/:limit)', function() use ($app) {
   $app->response->headers->set('Content-Type', 'application/json');
   $app->response->setBody(json_encode([]));
})->conditions(['limit' => '[1-4][0-9]?$|50$']);

$app->run();