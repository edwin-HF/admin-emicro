<?php


namespace annotation;


use EMicro\Application;


/**
 * @Annotation
 */
class View
{

    public function run($params,$data){

        if ($data && !is_array($data))
            throw new \Exception('render data type err, need array');

        $viewPathArr = explode(':',$params);

        $main = (count($viewPathArr) == 1 ? 'main' : $viewPathArr[0]);
        $routeView = (count($viewPathArr) == 1 ? $params : $viewPathArr[1]);

        header('Content-Type:text/html');

        $extraData = array_merge($data ?? [],[
            'session' => $_SESSION,
            'get'  => $_GET,
            'post' => $_POST
        ]);
        extract($extraData);

        ob_start();
        require Application::getInstance()->getAppPath().'/view/'.$routeView.'.phtml';
        $content = ob_get_clean();

        if (empty($main)){
            die($content);
        }

        die(require Application::getInstance()->getAppPath().'/view/layout/'.$main.'.phtml');
    }

}