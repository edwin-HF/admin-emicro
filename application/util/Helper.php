<?php


namespace util;


class Helper
{

    public static function verifyCode($length = 6){
        $pool='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0, $mt_rand_max = strlen($pool) - 1; $i < $length; $i++)
        {
            $code .= $pool[mt_rand(0, $mt_rand_max)];
        }
        return $code;
    }

    public static function random($len, $special=false){
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );

        if($special){
            $chars = array_merge($chars, array(
                "!", "@", "#", "$", "?", "|", "{", "/", ":", ";",
                "%", "^", "&", "*", "(", ")", "-", "_", "[", "]",
                "}", "<", ">", "~", "+", "=", ",", "."
            ));
        }

        $charsLen = count($chars) - 1;
        shuffle($chars);                            //打乱数组顺序
        $str = '';
        for($i=0; $i<$len; $i++){
            $str .= $chars[mt_rand(0, $charsLen)];    //随机取出一位
        }
        return $str;
    }

    public static function uuid($prefix = ''){
        $chars = md5(uniqid(mt_rand(), true));
        $uuid  =  substr ( $chars, 0, 8 )  . '-'
                . substr ( $chars, 8, 4 )  . '-'
                . substr ( $chars, 12, 4 ) . '-'
                . substr ( $chars, 16, 4 ) . '-'
                . substr ( $chars, 20, 12 );
        return $prefix . $uuid ;
    }

    public static function arrMapGroup($arr, $key, $field=null)
    {

        $map = [];

        array_walk(
            $arr,
            function ($val) use (&$map, $key, $field) {
                if (empty($field)){
                    $map[$val[$key]][] = $val;
                }elseif (is_array($field)){
                    $tmpData = [];
                    foreach ($field as $item){
                        $tmpData[$item] = $val[$item] ?? '';
                    }
                    $map[$val[$key]][] = $tmpData;
                }else{
                    $map[$val[$key]][] = $val[$field] ?? $val;
                }
            }
        );

        return $map;

    }

    public static function arrField($arr, $field){
        return array_column($arr, $field);
    }


    public static function arrMap($arr, $key, $field=null)
    {
        return array_column($arr, $field, $key);
    }

    public static function arr2str(&$arr)
    {

        foreach ($arr as &$item) {
            if (is_array($item)) {
                self::arr2str($item);
            } else {
                $item = strval($item);
            }
        }

        return $arr;

    }

    public static function arrFilter($arr, $expect){
        return array_filter($arr,function ($item) use ($expect){
            return in_array($item,$expect);
        },ARRAY_FILTER_USE_KEY);
    }

    public static function arrSort($data, $column, $sort = SORT_ASC){
        $keysValue = [];
        foreach ($data as $k => $v) {
            $keysValue[$k] = $v[$column];
        }
        array_multisort($keysValue, $sort, $data);
        return $data;
    }

    public static function password($password,$salt = 'move-x'){
        return hash_hmac('md5',$password,$salt);
    }

    public static function appendChild(&$tree, $pid, callable $callback, $primaryKey = 'id'){

        if (empty($pid)){
            array_push($tree,array_merge($callback(),['child' => []]));
        }else{
            foreach ($tree as &$item){
                if ($item[$primaryKey] == $pid){
                    $item['child'][] = array_merge($callback(),['child' => []]);
                }
                if (!empty($item['child'])){
                    self::appendChild($item['child'],$pid,$callback,$primaryKey);
                }
            }
        }

    }

    public static function list2Tree($list, $relateKey, callable $callback, $primaryKey = 'id'){

        $returnData = [];

        foreach ($list as $key => $value){

            Helper::appendChild($returnData,$value[$relateKey],function () use ($value, $callback){
                return $callback($value);
            },$primaryKey);

        }

        return $returnData;

    }

}