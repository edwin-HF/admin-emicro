<?php


namespace annotation;

/**
 * @Annotation
 */
class ApiResponseHeader
{

    public function run(){
        header('Content-Type:application/json');
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:GET, POST, PATCH, PUT, OPTIONS');
        header('Access-Control-Allow-Headers:X-Requested-With,X_Requested_With,X-PINGOTHER,Content-Type,Origin, Cookie, Accept');
    }

}