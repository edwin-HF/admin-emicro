<?php


namespace controller\admin;


use annotation\ApiResponseHeader;
use annotation\View;
use EMicro\Request;
use model\AdminMenus;
use model\AdminRolePermissions;
use model\AdminUserRoles;
use util\Helper;
use util\Response;

/**
 * @Controller
 * @Route
 */
class AdminMenu
{

    /**
     * @Route(system/menus)
     * @View(admin/menus/index)
     */
    public function menuList(){}

    /**
     * @Route(system/menus/render)
     * @ApiResponseHeader()
     */
    public function menuRender(){

        $menus = \model\AdminMenus::query()->orderBy('pid, sort, id')->select();

        return Helper::list2Tree($menus,'pid',function ($item){
            return [
                'id'     => $item['id'],
                'title'  => $item['title'],
                'unique' => $item['code'],
                'icon'   => $item['icon'],
                'path'   => $item['action'],
                'sort'   => $item['sort'],
            ];
        });

    }

    /**
     * @Route(system/menus/save)
     * @ApiResponseHeader()
     */
    public function menuSave(){

        try {

            $parentId = Request::input('parent_id',0);

            $saveData['title'] = Request::input('title');
            $saveData['icon']  = Request::input('icon');
            $saveData['action'] = Request::input('url');
            $saveData['code']   = Request::input('unique');

            if (Request::input('is_add')){

                $res = AdminMenus::query()->insert(
                    array_merge($saveData,
                        [
                            'type' => 1,
                            'pid'  => $parentId,
                            'sort' => \model\AdminMenus::query()->where([['pid','=',$parentId]])->max('sort') + 1
                        ]
                    )
                );

            }else{
                $res = \model\AdminMenus::query()->where([['id','=',$parentId]])
                    ->update($saveData);
            }

            if (!$res)
                throw new \Exception('save failed');

            return Response::success();

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

    /**
     * @Route(system/menu/delete)
     * @ApiResponseHeader()
     */
    public function menuDelete(){

        try {

            $id = Request::input('id');

            $menu = \model\AdminMenus::query()->getInfo([['id', '=', $id]]);

            if (!$menu)
                throw new \Exception('menu not exist');

            \model\AdminMenus::query()->where([['id', '=', $id]])->delete();

            return Response::success();

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

    /**
     * @ApiResponseHeader()
     * @Route(menus)
     */
    public function menus(){

        $roles = AdminUserRoles::query()
            ->getList(
                [
                    ['user_id', '=', $_SESSION['user_id']]
                ]
            );

        $roles = Helper::arrField($roles,'role_id');

        $query = \model\AdminMenus::query();

        if (!in_array(\model\AdminRoles::ROLE_ROOT, $roles)){

            $permissions = AdminRolePermissions::query()->fetchSql(false)
                ->getList(
                    [
                        ['role_id', 'in', $roles]
                    ]
                );


            $codes = \model\AdminPermissions::query()
                ->getList(
                    [
                        ['id', 'IN', Helper::arrField($permissions,'permission_id')]
                    ]
                );

            $query->where(
                [
                    ['code', 'IN', Helper::arrField($codes, 'code')]
                ]
            );

        }

        $menus = $query->orderBy('pid,sort,id')->select();

        return Helper::list2Tree($menus,'pid',function ($item){
            return [
                'id'     => $item['id'],
                'title'  => $item['title'],
                'unique' => $item['code'],
                'icon'   => $item['icon'],
                'path'   => $item['action'],
                'sort'   => $item['sort'],
            ];
        });

    }

    /**
     * @Route(system/menus/sort)
     * @ApiResponseHeader()
     */
    public function menuSort(){

        try {

            $sort = Request::input('sort');
            $id   = Request::input('id');

            $menu = \model\AdminMenus::query()->getInfo([['id', '=', $id]]);

            if (!$menu)
                throw new \Exception('menu not exist,please fresh current page');

            $res = AdminMenus::query()->where([['id', '=', $id]])
                ->update(
                    [
                        'sort' => $sort
                    ]
                );

            if (!$res)
                throw new \Exception('sort failed');

            return Response::success();

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

}