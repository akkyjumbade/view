<?php
require_once __DIR__.'/src/View.php';
use Akkk\View\View;
$view = new View(__DIR__.'/tmp.html', [
    'title' => 'Welcome to the home',
]);
$view->title('Welcome to the home')->meta([
    'name' => 'robots',
    'content' => 'index',
])->addStyle('css/main.css')
    ->addScript('js/main.js');
$view->render();
