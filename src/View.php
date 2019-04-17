<?php
namespace Akkk\View;
require __DIR__.'/config.php';
use DOMDocument;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Os;
use Sinergi\BrowserDetector\Language;
use Sinergi\BrowserDetector\Device;

class View {
    private $viewPath = null;
    private $dom = null;
    private $shouldIndex = false;
    private $scripts = [];
    private $styles = [];
    private $links = [];
    private $meta = [];
    // User agent
    public $browser = null;
    public $device = null;
    public $os = null;
    public $lang = 'en-GB';
    private $props = [];

    function __construct($viewPath = null, $props = []) {
        global $config;
        $this->links = $config['links'];
        $this->meta = $config['meta'];
        $this->dom = new DOMDocument('1.0', 'utf-8');
        $this->dom->formatOutput = true;
        if($viewPath) {
            $this->viewPath = $viewPath;
        }
        if(count($props)) {
            foreach ($props as $key => $value) {
                $this->{$key} = $value;
            }
        }
        $detect = new Browser;
        $this->browser = $detect->getName();
        $os = new Os;
        $this->os = $os->getName();
        $lang = new Language;
        $this->lang = $lang->getLanguage();
        $device = new Device;
        $this->device = $device->getName();

    }
    function title($title) {
        $this->title = $title;
        return $this;
    }
    public function render($viewName = null){
        $rootEl = $this->_el('html', null, [
            'lang' => $this->lang,
        ]);
        $this->dom->appendChild($rootEl);
        {
            $headEl = $this->_el('head');
            $rootEl->appendChild($headEl);
            $headEl->appendChild($this->_el('base', null, [
                'href' => $this->baseUrl
            ]));
            $headEl->appendChild($this->_el('title', $this->title));
            $headEl->appendChild($this->_el('meta', null, [
                'charset' => 'utf-8',
            ]));
            $this->meta([
                'robots' => 'index, follow',
            ]);
            $this->meta([
                'keywords' => $this->keywords
            ]);
            $this->meta([
                'description' => $this->description,
            ]);
            if($this->shouldIndex) {
                $this->meta([
                    'robots' => 'index, follow',
                ]);
                $this->meta([
                    'googlebot' => 'index, follow',
                ]);
            } else {
                $this->meta([
                    'robots' => 'noindex, nofollow',
                ]);
                $this->meta([
                    'googlebot' => 'noindex, nofollow',
                ]);
            }
            if($this->meta) {
                foreach ($this->meta as $key => $metaObj) {
                    $props = [];
                    foreach ($metaObj as $metaKey => $value) {
                        $props[$metaKey] = $value;
                    }
                    $styleEl = $this->_el('meta', null, $props);
                    $headEl->appendChild($styleEl);
                }
            }
            if($this->links) {
                foreach ($this->links as $key => $value) {
                    $linkEl = $this->_el('link', null, [
                        'rel' => $value['rel'],
                        'href' => $value['href'],
                    ]);
                    $headEl->appendChild($linkEl);
                }
            }
            foreach ($this->styles as $key => $value) {
                $styleEl = $this->_el('link', null, [
                    'rel' => 'stylesheet',
                    'href' => $value,
                ]);
                $headEl->appendChild($styleEl);
            }
            $headEl->appendChild($this->_el('link', null, [
                'rel' => 'canonical',
                'href' => $this->canonical,
            ]));
            // Adding Schema script [starts here]
            $schemaContent = json_encode([
                '@context' => 'http://schema.org',
                '@type' => 'Organization',
                'url' => $this->baseUrl,
            ]);
            $schemaEl = $this->_el('script', $schemaContent, [
                'type' => 'application/ld+json'
            ]);
            $headEl->appendChild($schemaEl);
            // Adding Schema script [ends here]
            // Adding service worker [starts here]
            $swScriptEl = $this->_el('script', null, [
                'src' => rtrim($this->baseUrl, '/')."/serviceworker.js"
            ]);
            $headEl->appendChild($swScriptEl);
            // Adding service worker [ends here]
            $bodyEl = $this->_el('body', null, [
                'data-browser' => $this->browser,
                'data-os' => $this->os,
                'data-device' => $this->device,
                'data-lang' => $this->lang,
            ]);
            $rootEl->appendChild($bodyEl);
            $bodyEl->appendChild($this->_el('noscript', 'Enable script to run application'));
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
        if($this->meta) {
            $isExist = \array_filter($this->meta, function ($item) use ($params) {
                if(array_key_exists('name', $params) && array_key_exists('name', $item)) {
                    return $params['name'] == $item['name'];
                }
            });
        }
        if($params && count($params)) {
            if(array_key_exists('name', $params) && property_exists($this, $params['name'])) {
                throw new Exception("Property already exists", 1);
            } else {
                if($this->meta) {
                    array_push($this->meta, $params);
                }
            }
        }
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
    public function __set($prop, $value)
    {
        $this->props[$prop] = $value;
    }
    public function __get($prop)
    {
        if(array_key_exists($prop, $this->props)) {
            return $this->props[$prop];
        }
    }
}
