<?php


namespace model;


use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model
{

    public const TYPE_MENU   = 1;
    public const TYPE_BUTTON = 2;

    public const MAP = [
        self::TYPE_MENU   => '菜单',
        self::TYPE_BUTTON => '按钮'
    ];

}