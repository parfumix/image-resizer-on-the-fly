<?php
require __DIR__.'/../vendor/autoload.php';

if(! isset($_GET['static']) )
    return;

$staticImage = $_GET['static'];

list($alias, $original) = explode('@', $staticImage);
$raw = pathinfo($alias);

(new \Parfumix\ImageOnFly\ImageManager($original, $raw['filename']))
    ->render();