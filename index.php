<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/src/View.php';
use Akkk\View\View;
$view = new View(__DIR__.'/tmp.html', [
    'title' => 'Welcome to the home',
    'keywords' => 'Welcome to the home, Homepage, Landing page',
    'description' => 'Description for the page',
    'shouldIndex' => true,
    'canonical' => '/thie',
]);
$view->theme_color = '#f0f';
$view->addStyle('css/main.css')
    ->addScript('js/main.js');
$view->render();
