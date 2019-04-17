<?php
namespace Akkk\View;
require __DIR__.'/config.php';
use DOMDocument;

class View {
    private $viewPath = null;
    private $dom = null;
    private $title = 'Missing title for view';
    private $scripts = [];
    private $styles = [];
    private $links = [];
    private $meta = [];

    function __construct($viewPath = null) {
        global $config;
        $this->links = $config['links'];
        $this->meta = $config['meta'];

        $this->dom = new DOMDocument('1.0', 'utf-8');
        $this->dom->formatOutput = true;
        if($viewPath) {
            $this->viewPath = $viewPath;
        }
    }
    function title($title) {
        $this->title = $title;
        return $this;
    }
    public function render($viewName = null){
        $rootEl = $this->_el('html', null, [
            'lang' => 'en-GB',
        ]);
        $this->dom->appendChild($rootEl);
        {
            $headEl = $this->_el('head');
            $rootEl->appendChild($headEl);
            $headEl->appendChild($this->_el('title', $this->title));
            $headEl->appendChild($this->_el('base', null, [
                'href' => '/'
            ]));
            foreach ($this->meta as $key => $metaObj) {
                $props = [];
                foreach ($metaObj as $metaKey => $value) {
                    $props[$metaKey] = $value;
                }
                $styleEl = $this->_el('meta', null, $props);
                $headEl->appendChild($styleEl);
            }
            foreach ($this->links as $key => $value) {
                $linkEl = $this->_el('link', null, [
                    'rel' => $value['rel'],
                    'href' => $value['href'],
                ]);
                $headEl->appendChild($linkEl);
            }
            foreach ($this->styles as $key => $value) {
                $styleEl = $this->_el('link', null, [
                    'href' => $value,
                ]);
                $headEl->appendChild($styleEl);
            }
            $bodyEl = $this->_el('body');
            $rootEl->appendChild($bodyEl);
            $componentEl = $this->_el('div', null, ['id' => 'root']);
            $bodyEl->appendChild($this->dom->createComment('Root Component [starts here]'));
            $bodyEl->appendChild($componentEl);
            $bodyEl->appendChild($this->dom->createComment('Root Component [ends here]'.PHP_EOL));
            if ($this->viewPath) {
                $htmlFromFile = (@file_get_contents($this->viewPath));
                $componentEl->appendChild($this->_el('main', $htmlFromFile));
            }
            foreach ($this->scripts as $key => $value) {
                $scriptEl = $this->_el('script', null, [
                    'src' => $value,
                ]);
                $bodyEl->appendChild($scriptEl);
            }
        }
        $this->save();
        return $this->dom;
    }
    public function addStyle($style_path) {
        array_push($this->styles, $style_path);
        return $this;
    }
    public function addScript($script_path, $attrs = null) {
        array_push($this->scripts, $script_path);
        return $this;
    }
    public function meta($params) {
        $isExist = \array_filter($this->meta, function ($item) use ($params) {
            if(array_key_exists('name', $params) && array_key_exists('name', $item)) {
                return $params['name'] == $item['name'];
            }
        });
        array_push($this->meta, $params);
        return $this;
    }
    public function _el($elName, $childNode = null, $attrs = []) {
        $el = $this->dom->createElement($elName, $childNode);
        foreach ($attrs as $key => $value) {
            $attr = $this->dom->createAttribute($key);
            $attr->value = $value;
            $el->appendChild($attr);
        }
        return $el;
    }
    public function save()
    {
        echo $this->dom->saveHTML();
    }
    public function saveFile()
    {
        $this->dom->saveHTMLFile(__DIR__.'/../dist/result.html');
    }
}
