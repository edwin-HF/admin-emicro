<?php


namespace controller\admin;


use annotation\ApiResponseHeader;
use annotation\View;
use Edv\Orm\DB;
use EMicro\Request;
use model\AdminRolePermissions;
use model\AdminRoles;
use model\AdminUserRoles;
use util\Helper;
use util\Response;

/**
 * @Controller
 * @Route
 */
class AdminRole
{

    /**
     * @Route(system/roles)
     * @View(admin/roles/index)!after
     */
    public function index(){

        try {

            $page     = Request::input('page',1);
            $pageSize = Request::input('page_size',config('page_size',15));

            $roles = \model\AdminRoles::query()->forPage($page, $pageSize)->select();

            return [
                'list'  => $roles,
                'count' => \model\AdminRoles::query()->count()
            ];

        }catch (\Exception $exception){
            throw $exception;
        }
    }


    /**
     * @Route(system/role/save)
     * @ApiResponseHeader()
     */
    public function saveRole(){

        try {

            $roleName = Request::input('name');
            $id       = Request::input('id');

            if (empty($roleName))
                throw new \Exception('role name can not empty');

            if (\model\AdminRoles::query()->getInfo([['name', '=', $roleName]]))
                throw new \Exception('role existed');

            $adminRole = \model\AdminRoles::query();

            if ($id > 0){
                $res = $adminRole->where([['id', '=', $id]])->update(
                    [
                        'name' => $roleName
                    ]
                );
            }else{
                $res = $adminRole->insert(
                    [
                        'name' => $roleName
                    ]
                );
            }

            if (!$res)
                throw new \Exception('operate failed');

            return Response::success();

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

    /**
     * @Route(system/role/delete)
     * @ApiResponseHeader()
     */
    public function deleteRole(){

        try {

            DB::beginTransaction();

            $id = Request::input('id');

            $role = \model\AdminRoles::query()->getInfo([['id', '=', $id]]);

            if (!$role)
                throw new \Exception('role not exist,please fresh current page');

            $res = AdminRoles::query()->where([['id', '=', $id]])->delete();

            if (!$res)
                throw new \Exception('deleted failed');

            AdminUserRoles::query()->where([['role_id', '=', $id]])->delete();

            DB::commit();

            return Response::success();

        }catch (\Exception $exception){

            DB::rollBack();

            return Response::error($exception->getMessage());
        }

    }

    /**
     * @Route(system/role/permissions)
     * @ApiResponseHeader()
     */
    public function rolePermission(){

        try {

            $roleId = Request::input('id');

            $rolePermission = AdminRolePermissions::query()
                ->getList([['role_id','=',$roleId]]);

            $rolePermission = Helper::arrField($rolePermission, 'permission_id');

            $curRole = AdminUserRoles::query()
                ->getList([['user_id', '=', $_SESSION['user_id']]]);

            $curRole = Helper::arrField($curRole, 'role_id');

            $query = \model\AdminPermissions::query();

            if (!in_array(\model\AdminRoles::ROLE_ROOT, $curRole)){
                $curRolePermissions = AdminRolePermissions::query()
                    ->getList('role_id','IN',$curRole);

                $curRolePermissions = Helper::arrField($curRolePermissions, 'permission_id');

                $query->where([['id', 'IN', $curRolePermissions]]);

            }

            $permissions = $query->orderBy('pid, sort, id')->select();

            $returnData = [];

            foreach ($permissions as $key => $value){

                Helper::appendChild($returnData,$value['pid'],function () use ($value , $rolePermission){
                    return [
                        'id'     => $value['id'],
                        'title'  => $value['title'],
                        'unique' => $value['code'],
                        'type'   => $value['type'],
                        'sort'   => $value['sort'],
                        'checked' => (in_array($value['id'],$rolePermission) ? 1 : 0)
                    ];
                });

            }

            return $returnData;

        }catch (\Exception $exception){
            return [];
        }

    }

    /**
     * @Route(system/role/permission/save)
     * @ApiResponseHeader()
     */
    public function saveRolePermissions(){

        try {

            DB::beginTransaction();

            $roleId     = Request::input('id');
            $permission = Request::input('permission');

            $permissionArr = explode(',',$permission);

            $curPermission = AdminRolePermissions::query()
                ->getList([['role_id', '=', $roleId]]);

            $curPermission = Helper::arrField($curPermission, 'permission_id');

            $delPermission = array_diff($curPermission,$permissionArr);
            $addPermission = array_diff($permissionArr,$curPermission);

            AdminRolePermissions::query()
                ->where(
                    [
                        ['role_id', '=', $roleId],
                        ['permission_id', 'IN', $delPermission]
                    ]
                )
                ->delete();

            foreach ($addPermission as $permission){

                AdminRolePermissions::query()->insert(
                    [
                        'role_id'       => $roleId,
                        'permission_id' => $permission
                    ]
                );

            }

            DB::commit();

            return Response::success();

        }catch (\Exception $exception){
            DB::rollBack();
            return Response::error($exception->getMessage());
        }

    }

}