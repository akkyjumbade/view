<?php 
namespace Akkk\View;

class Asset {
    private $path = '';
    public function __construct($path) {
        $this->path = $path;
    }
    public function render(){
        return $this->path;
    }
}