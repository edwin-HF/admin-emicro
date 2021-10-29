<?php


namespace controller\admin;


use annotation\ApiResponseHeader;
use annotation\View;
use EMicro\Request;
use Illuminate\Database\Capsule\Manager;
use model\AdminRolePermission;
use model\AdminUserRole;
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
    public function index(Request $request){

        try {

            $page     = $request->input('page',1);
            $pageSize = $request->input('page_size',config('page_size',15));

            $roles = \model\AdminRole::query()->forPage($page,$pageSize)->get()->toArray();

            return [
                'list'  => $roles,
                'count' => \model\AdminRole::query()->count()
            ];

        }catch (\Exception $exception){
            throw $exception;
        }
    }


    /**
     * @Route(system/role/save)
     * @ApiResponseHeader()
     */
    public function saveRole(Request $request){

        try {

            $roleName = $request->input('name');
            $id       = $request->input('id');

            if (empty($roleName))
                throw new \Exception('role name can not empty');

            if (\model\AdminRole::query()->where('name',$roleName)->first())
                throw new \Exception('role existed');

            if ($id > 0){
                $adminRole = \model\AdminRole::query()->find($id);
            }else{
                $adminRole = new \model\AdminRole();
            }

            $adminRole->name = $roleName;

            if (!$adminRole->save())
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
    public function deleteRole(Request $request){

        try {

            Manager::begionTransaction();

            $id = $request->input('id');

            $role = \model\AdminRole::query()->find($id);

            if (!$role)
                throw new \Exception('role not exist,please fresh current page');

            if (!$role->delete())
                throw new \Exception('deleted failed');

            AdminUserRole::query()->where('role_id',$role->id)->delete();

            Manager::commit();

            return Response::success();

        }catch (\Exception $exception){

            Manager::callback();

            return Response::error($exception->getMessage());
        }

    }

    /**
     * @Route(system/role/permissions)
     * @ApiResponseHeader()
     */
    public function rolePermission(Request $request){

        try {

            $roleId = $request->input('id');

            $rolePermission = AdminRolePermission::query()
                ->where('role_id',$roleId)
                ->pluck('permission_id')->toArray();

            $curRole = AdminUserRole::query()->where('user_id',$_SESSION['user_id'])->pluck('role_id')->toArray();

            $query = \model\AdminPermission::query();

            if (!in_array(\model\AdminRole::ROLE_ROOT,$curRole)){
                $curRolePermissions = AdminRolePermission::query()->whereIn('role_id',$curRole)->pluck('permission_id')->toArray();
                $query->whereIn('id',$curRolePermissions);
            }

            $permissions = $query->orderByRaw('pid,sort,id')->get()->toArray();

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
    public function saveRolePermissions(Request $request){

        try {

            Manager::beginTransaction();

            $roleId     = $request->input('id');
            $permission = $request->input('permission');

            $permissionArr = explode(',',$permission);

            $curPermission = AdminRolePermission::query()->where('role_id',$roleId)->pluck('permission_id')->toArray();

            $delPermission = array_diff($curPermission,$permissionArr);
            $addPermission = array_diff($permissionArr,$curPermission);

            AdminRolePermission::query()->where('role_id',$roleId)->whereIn('permission_id',$delPermission)->delete();

            foreach ($addPermission as $permission){

                AdminRolePermission::query()->insert(
                    [
                        'role_id'       => $roleId,
                        'permission_id' => $permission
                    ]
                );


            }


            Manager::commit();

            return Response::success();

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

}