<?php


namespace controller\admin;


use annotation\ApiResponseHeader;
use annotation\View;
use EMicro\Request;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Str;
use model\AdminRolePermission;
use model\AdminUserRole;
use util\Helper;
use util\Response;


/**
 * @Controller
 * @Route
 */
class AdminUser
{

    /**
     * @View(:login)!after
     */
    public function login(){}


    /**
     * @ApiResponseHeader()
     */
    public function attempt(Request $request){

        try {

            $username = $request->input('username','');
            $password = $request->input('password','');

            if (empty($username) || empty($password))
                throw new \Exception('请输入用户名和密码');

            $user = \model\AdminUser::query()->where('username', $username)->first();

            if (!$user)
                throw new \Exception('用户不存在');

            if ($user->password != Helper::password($password,$user->salt))
                throw new \Exception('密码错误');

            $_SESSION['username'] = $user->username;
            $_SESSION['user_id']  = $user->id;

            return Response::success();

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

    /**
     * @ApiResponseHeader()
     */
    public function changePassword(Request $request){

        try {

            $oldPassword = $request->input('old_password','');
            $newPassword = $request->input('new_password','');

            if (empty($oldPassword) || empty($newPassword))
                throw new \Exception('密码不能为空');

            $user = \model\AdminUser::query()->find($_SESSION['user_id']);

            if (!$user)
                throw new \Exception('user not found');

            if ($user->password != Helper::password($oldPassword,$user->salt))
                throw new \Exception('old password err');

            $salt = Str::random(4);

            $user->password = Helper::password($newPassword,$salt);
            $user->salt     = $salt;

            if (!$user->save())
                throw new \Exception('change err');

            return Response::success();

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

    public function logout(){
        session_destroy();
        header('Location:/');
        die();
    }


    /**
     * @Route(system/users)
     * @View(admin/users/index)!after
     */
    public function userList(Request $request){

        try {

            $page = $request->input('page',1);
            $pageSize = $request->input('page_size',config('system.page_size',15));

            $users = \model\AdminUser::query()
                ->forPage($page,$pageSize)
                ->get();

            $userRole = AdminUserRole::query()
                ->select('admin_user_roles.*','admin_roles.name')
                ->leftJoin('admin_roles','admin_user_roles.role_id','=','admin_roles.id')
                ->whereIn('user_id',$users->pluck('id'))
                ->get()->toArray();

            return [
                'list'      => $users->toArray(),
                'count'     => \model\AdminUser::query()->count(),
                'roleGroup' => Helper::arrMapGroup($userRole,'user_id'),
                'roles'     => Helper::arrMap(\model\AdminRole::query()->get()->toArray(),'id')
            ];

        }catch (\Exception $exception){
            return  [];
        }

    }

    /**
     * @Route(system/user/save)
     * @ApiResponseHeader()
     */
    public function saveUser(Request $request){

        try {

            Manager::beginTransaction();

            $id       = $request->input('id');
            $username = $request->input('username');
            $password = $request->input('password');
            $status   = $request->input('status');
            $roles    = $request->input('roles');

            if ($id > 0){
                $user = \model\AdminUser::query()->find($id);
            }else{
                $user = new \model\AdminUser();
            }

            if (!empty($password)){
                $salt = Str::random(4);
                $user->salt = $salt;
                $user->password = Helper::password($password,$salt);
            }

            $user->username = $username;
            $user->status   = $status;

            if (!$user->save())
                throw new \Exception('user saved err');

            AdminUserRole::query()->where('user_id',$user->id)->delete();

            $roleArr = explode(',',$roles);

            foreach ($roleArr as $role) {

                AdminUserRole::query()->insert(
                    [
                        'user_id' => $user->id,
                        'role_id' => $role
                    ]
                );

            }

            Manager::commit();

            return Response::success();

        }catch (\Exception $exception){
            Manager::rollback();
            return Response::error($exception->getMessage());
        }

    }

    /**
     * @Route(system/user/delete)
     * @ApiResponseHeader()
     */
    public function userDelete(Request $request){

        try {

            Manager::beginTransaction();

            $id = $request->input('id');

            $user = \model\AdminUser::query()->find($id);

            if (!$user)
                throw new \Exception('user not found,please fresh current page');

            $user->delete();

            AdminUserRole::query()->where('user_id',$user->id)->delete();

            Manager::commit();

            return Response::success();

        }catch (\Exception $exception){
            Manager::rollback();

            return Response::error($exception->getMessage());
        }

    }

    /**
     * @Route(system/user/permissions)
     * @ApiResponseHeader()
     */
    public function permissions(){

        try {

            $roles = AdminUserRole::query()
                ->where('user_id',$_SESSION['user_id'])
                ->pluck('role_id')->toArray();

            $permissions = AdminRolePermission::query()
                ->whereIn('role_id',$roles)
                ->pluck('permission_id')->toArray();

            $codes = \model\AdminPermission::query()
                ->whereIn('id',$permissions)
                ->pluck('code')->toArray();

            return Response::success(
                [
                    'is_root' => (in_array(\model\AdminRole::ROLE_ROOT,$roles) ? 1 : 0),
                    'permissions' => $codes
                ]
            );

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

}