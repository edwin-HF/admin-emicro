<?php


namespace controller\admin;


use annotation\ApiResponseHeader;
use annotation\View;
use Edv\Orm\DB;
use EMicro\Request;
use model\AdminRolePermissions;
use model\AdminUserRoles;
use model\AdminUsers;
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
    public function attempt(){

        try {

            $username = Request::input('username','');
            $password = Request::input('password','');

            if (empty($username) || empty($password))
                throw new \Exception('请输入用户名和密码');

            $user = \model\AdminUsers::query()->getInfo(
                [
                    ['username', '=', $username]
                ]
            );

            if (!$user)
                throw new \Exception('用户不存在');

            if ($user['password'] != Helper::password($password, $user['salt']))
                throw new \Exception('密码错误');

            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id']  = $user['id'];

            return Response::success();

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

    /**
     * @ApiResponseHeader()
     */
    public function changePassword(){

        try {

            $oldPassword = Request::input('old_password','');
            $newPassword = Request::input('new_password','');

            if (empty($oldPassword) || empty($newPassword))
                throw new \Exception('密码不能为空');

            $user = \model\AdminUsers::query()->getInfo(
                [
                    ['id', '=', $_SESSION['user_id']]
                ]
            );

            if (!$user)
                throw new \Exception('user not found');

            if ($user->password != Helper::password($oldPassword,$user->salt))
                throw new \Exception('old password err');

            $salt = Helper::random(4);

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
    public function userList(){

        try {

            $page = Request::input('page',1);
            $pageSize = Request::input('page_size',config('system.page_size',15));

            $users = \model\AdminUsers::query()
                ->forPage($page,$pageSize)
                ->select();

            $userRole = AdminUserRoles::query()
                ->getList(
                    [
                        ['user_id', 'IN', Helper::arrField($users, 'id')]
                    ],
                    'ur.*, r.name',
                    [
                        'alias'  => 'ur',
                        'relate' => [
                            ['admin_roles r', 'ur.role_id = r.id']
                        ]
                    ]
                );

            return [
                'list'      => $users,
                'count'     => \model\AdminUsers::query()->count(),
                'roleGroup' => Helper::arrMapGroup($userRole,'user_id'),
                'roles'     => Helper::arrMap(\model\AdminRoles::query()->getList(), 'id')
            ];

        }catch (\Exception $exception){
            return  [];
        }

    }

    /**
     * @Route(system/user/save)
     * @ApiResponseHeader()
     */
    public function saveUser(){

        try {

            DB::beginTransaction();

            $id       = Request::input('id');
            $username = Request::input('username');
            $password = Request::input('password');
            $status   = Request::input('status');
            $roles    = Request::input('roles');


            if (!empty($password)){
                $salt = Helper::random(4);
                $saveData['salt']     = $salt;
                $saveData['password'] = Helper::password($password, $salt);
            }

            $saveData['username'] = $username;
            $saveData['status']   = $status;

            if ($id > 0){
                $userId = $id;
                \model\AdminUsers::query()->where([['id', '=', $id]])->update($saveData);
            }else{
                $userId = AdminUsers::query()->insert($saveData);
            }

            AdminUserRoles::query()->where([['user_id', '=', $userId]])->delete();

            $roleArr = explode(',',$roles);

            foreach ($roleArr as $role) {

                AdminUserRoles::query()->insert(
                    [
                        'user_id' => $userId,
                        'role_id' => $role
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

    /**
     * @Route(system/user/delete)
     * @ApiResponseHeader()
     */
    public function userDelete(){

        try {

            DB::beginTransaction();

            $id = Request::input('id');

            $user = \model\AdminUsers::query()->getInfo([['id', '=', $id]]);

            if (!$user)
                throw new \Exception('user not found,please fresh current page');

            AdminUsers::query()->where([['id', '=', $id]])->delete();

            AdminUserRoles::query()->where([['user_id', '=', $id]])->delete();

            DB::commit();

            return Response::success();

        }catch (\Exception $exception){
            DB::rollBack();

            return Response::error($exception->getMessage());
        }

    }

    /**
     * @Route(system/user/permissions)
     * @ApiResponseHeader()
     */
    public function permissions(){

        try {

            $roles = AdminUserRoles::query()->getList(
                [
                    ['user_id', '=', $_SESSION['user_id']]
                ]
            );

            $roles = Helper::arrField($roles, 'role_id');

            $permissions = AdminRolePermissions::query()->getList(
                [
                    ['role_id', 'IN', $roles]
                ]
            );

            $codes = \model\AdminPermissions::query()->getList(
                [
                    ['id', 'IN', Helper::arrField($permissions,'permission_id')]
                ]
            );

            return Response::success(
                [
                    'is_root' => (in_array(\model\AdminRoles::ROLE_ROOT, $roles) ? 1 : 0),
                    'permissions' => Helper::arrField($codes, 'code')
                ]
            );

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

}