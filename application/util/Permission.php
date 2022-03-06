<?php


namespace util;


use model\AdminRoles;
use model\AdminRolePermissions;
use model\AdminUserRoles;

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

            $roles = AdminUserRoles::query()
                ->getList(
                    [
                        ['user_id', '=', $_SESSION['user_id']]
                    ]
                );

            $roles = Helper::arrField($roles, 'role_id');

            if (in_array(AdminRoles::ROLE_ROOT, $roles)){
                $this->isRoot = true;
            }

            $permissions = AdminRolePermissions::query()
                ->getList(
                    [
                        ['role_id', 'IN', $roles]
                    ]
                );

            $permissions = Helper::arrField($permissions, 'permission_id');

            $codes = \model\AdminPermissions::query()
                ->getList(
                    [
                        ['id', 'IN', $permissions]
                    ]
                );

            $this->codes = Helper::arrField($codes, 'code');

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