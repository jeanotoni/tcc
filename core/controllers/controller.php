<?php

namespace controllers;

class controller extends \filters\filter {

    public function view($name, $data = null, $template = true) {

        if ($data != null) {
            extract($data);
        }

        if ($template) {
            require_once DIR_VIEWS . 'includes/header.phtml';
        }

        $path = DIR_VIEWS . $name . '.phtml';
        if (file_exists($path)) {
            require_once $path;
        }

        if ($template) {
            require_once DIR_VIEWS . 'includes/footer.phtml';
        }
    }

    public function toJson($obj) {
        $json = (array) $obj;
        return json_encode($json, JSON_NUMERIC_CHECK);
    }

}