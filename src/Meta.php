<?php 
namespace Akkk\View;

class Meta {
    private $name = '';
    public function __construct($name) {
        $this->name = $name;
    }
    public function render(){
        return $this->name;
    }
}