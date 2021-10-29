<?php


namespace util;


use model\AdminRole;
use model\AdminRolePermission;
use model\AdminUserRole;

class Permission
{

    private static array $permission = [];
    private static ?Permission $instance = null;

    private bool $isRoot = false;
    private array $codes = [];

    private function __construct(){}
    private function __clone(){}

    private function init(){

        if (!self::$permission){

            $roles = AdminUserRole::query()
                ->where('user_id',$_SESSION['user_id'])
                ->pluck('role_id')->toArray();

            if (in_array(AdminRole::ROLE_ROOT,$roles)){
                $this->isRoot = true;
            }

            $permissions = AdminRolePermission::query()
                ->whereIn('role_id',$roles)
                ->pluck('permission_id')->toArray();

            $codes = \model\AdminPermission::query()
                ->whereIn('id',$permissions)
                ->pluck('code')->toArray();

            $this->codes = $codes;

        }

        return $this;
    }

    public static function getInstance(){

        if (self::$instance)
            return self::$instance;

        return (new self())->init();
    }

    public static function allow($code){

        $permission = self::getInstance();

        if ($permission->isRoot)
            return true;

        return in_array($code,$permission->codes);

    }

    public static function isRoot($roleId){
        return $roleId == 0;
    }

}