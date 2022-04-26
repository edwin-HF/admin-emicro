<?php


namespace annotation;


use EMicro\Application;


/**
 * @Annotation
 */
class View
{

    public function run($params, $data){
        \util\View::render($params, $data);
    }

}