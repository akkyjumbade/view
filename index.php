<?php
require_once __DIR__.'/src/View.php';
use Akkk\View\View;
$view = new View(__DIR__.'/tmp.html');
$view->title('Welcome to the home')->addStyle('css/main.css')
    ->addScript('js/main.js');
$view->render();