<?php

namespace route;

class Route extends \filters\filter {

    public $paths = [];
    private $routes = [];
    private $controller;
    private $action;

    private function setController($name = null) {
        if ($name == null) {
            $controller = 'index';
        } else {
            $controller = $name;
        }
        
        $this->controller = $controller;
        return $controller;
    }

    private function setAction($name = null) {
        if ($name == null) {
            $action = 'init';
        } else {
            $action = $name;
        }
        
        $this->action = $action;
        return $action;
    }

    public function init() {

        $this->setPaths();

        $this->run();
    }

    private function setPaths() {

        $paths = explode('/', @ $_GET['path']);

        $controller = $this->setController(@ $paths[0]);
        $action = $this->setAction(@ $paths[1]);

        if (!empty($paths)) {

            $route = $controller . '/' . $action;

            unset($paths[0]);
            unset($paths[1]);

            $variables = $this->getRoute($route);
            
            if (!empty($variables)) {
                $x = 2;
//                debug($paths);
                foreach ($variables as $index => $filter) {
                    if ($filter !== null) {
                        $val = $this->$filter($paths[$x]);
                    } else {
                        $val = $paths[$x];
                    }
                    $this->setPath($index, $val);
                    $x++;
                }
            }
        }
        return;
    }

    public function getRoute($name) {
        if (isset($this->routes[$name])) {
            return $this->routes[$name];
        }
        return null;
    }

    public function setPath($path, $val) {
        $this->paths[$path] = $val;
        $_GET[$path] = $val;
    }

    public function setRoute($path, $variables = null) {
        $this->routes[$path] = $variables;
    }

    public static function get($index = null) {
        if ($index) {
            return $this->paths[$index];
        } else {
            return $this->paths;
        }
    }

    private function run() {

        $controller = 'controllers\\' . $this->controller;
        $action = $this->action;

        $controller = new $controller;
        $controller->$action();
    }

}
