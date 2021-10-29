<?php


namespace util;


class Response
{

    const CODE_SUCCESS           = 1000;
    const INFO_SUCCESS           = '操作成功';
    //业务错误代码以2***
    const CODE_ERR_RESPONSE      = 2000;      //响应错误
    const INFO_ERR_RESPONSE      = '操作失败';
    const CODE_ILLEGAL_REQUEST   = 2001;      //非法请求
    const INFO_ILLEGAL_REQUEST   = '无权访问';
    const CODE_ERR_LOGIN         = 2002;      //登录失效
    const INFO_ERR_LOGIN         = '登录信息失效,请重新登录';
    const CODE_ERR_REQUEST_PARAM = 2003;      //请求参数错误
    const INFO_ERR_REQUEST_PARAM = '请求参数错误';
    const CODE_LOGIN_LOCK        = 2004;
    const CODE_ILLEGAL_ENV       = '非法环境角色';


    private function __construct() {}

    /**
     * 请求参数错误
     * @author Edwin Fan
     * @contact edwin.fan@foxmail.com
     */
    public static function errRequestParam(){
        return self::out(self::INFO_ERR_REQUEST_PARAM,self::CODE_ERR_REQUEST_PARAM);
    }

    /**
     * 无权访问
     * @author Edwin Fan
     * @contact edwin.fan@foxmail.com
     */
    public static function errIllegalRequest(){
        return self::out(self::INFO_ILLEGAL_REQUEST,self::CODE_ILLEGAL_REQUEST);
    }

    /**
     * 未登录
     * @author Edwin Fan
     * @contact edwin.fan@foxmail.com
     */
    public static function errLogin(){
        return self::out(self::INFO_ERR_LOGIN,self::CODE_ERR_LOGIN);
    }

    /**
     * @param $data
     * @param array $extraData
     * @return mixed
     */
    public static function success($data = [],$extraData = []){
        return self::out($data,self::CODE_SUCCESS,self::INFO_SUCCESS,$extraData);
    }

    /**
     * @param $msg
     * @param int $code
     * @return mixed
     */
    public static function error($msg,$code = self::CODE_ERR_RESPONSE){
        return self::out($msg,$code);
    }

    /**
     * 统一输出
     * @param array $data
     * @param string $code
     * @param string $message
     * @param array $extraData
     * @param int $httpCode
     * @return mixed
     * @author Edwin Fan
     * @contact edwin.fan@foxmail.com
     */
    private static function out($data = [],$code = '',$message = '', $extraData = [], $httpCode=200){

        $result = [];

        if (is_array($data)){
            $result['code'] = empty($code) ? self::CODE_SUCCESS : $code;
            $result['info'] = $message;
            $result['result'] = $extraData;
            $result['result']['data'] = $data;
        }else{
            $result['code'] = empty($code) ? self::CODE_ERR_RESPONSE : $code;
            $result['info'] = is_string($data) ? $data : $message;
            $result['result'] = $extraData;
            $result['result']['data'] = [];
        }

        return Helper::arr2str($result);

    }

}