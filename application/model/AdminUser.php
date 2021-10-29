<?php


namespace model;


class AdminUser extends \Illuminate\Database\Eloquent\Model
{

    public const STATUS_NORMAL = 1;
    public const STATUS_FREEZE = 2;

    public const MAP = [
        self::STATUS_NORMAL => '正常',
        self::STATUS_FREEZE => '冻结'
    ];

}