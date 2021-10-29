<?php


namespace controller\admin;


use annotation\ApiResponseHeader;
use annotation\View;
use EMicro\Factory;
use EMicro\Request;
use logic\Menu;
use model\AdminRolePermission;
use model\AdminUserRole;
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

        $menus = \model\AdminMenu::query()->orderByRaw('pid,sort,id')->get()->toArray();

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
    public function menuSave(Request $request){

        try {

            if ($request->input('is_add')){

                $menu = new \model\AdminMenu();
                $menu->type   = 1;
                $menu->pid    = $request->input('parent_id',0);
                $menu->sort   = \model\AdminMenu::query()->where('pid',$request->input('parent_id',0))->max('sort') + 1;

            }else{
                $menu = \model\AdminMenu::query()->find($request->input('parent_id'));
            }

            $menu->title  = $request->input('title');
            $menu->icon   = $request->input('icon');
            $menu->action = $request->input('url');
            $menu->code   = $request->input('unique');

            if (!$menu->save())
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
    public function menuDelete(Request $request){

        try {

            $id = $request->input('id');

            $menu = \model\AdminMenu::query()->find($id);

            if (!$menu)
                throw new \Exception('menu not exist');

            \model\AdminMenu::query()->where('id',$id)->delete();

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

        $roles = AdminUserRole::query()
            ->where('user_id',$_SESSION['user_id'])
            ->pluck('role_id')->toArray();

        $query = \model\AdminMenu::query();

        if (!in_array(\model\AdminRole::ROLE_ROOT,$roles)){

            $permissions = AdminRolePermission::query()
                ->whereIn('role_id',$roles)
                ->pluck('permission_id')->toArray();

            $codes = \model\AdminPermission::query()
                ->whereIn('id',$permissions)
                ->pluck('code')->toArray();

            $query->whereIn('code',$codes);
        }

        $menus = $query->orderByRaw('pid,sort,id')->get()->toArray();

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
    public function menuSort(Request $request){


        try {

            $sort = $request->input('sort');
            $id   = $request->input('id');

            $menu = \model\AdminMenu::query()->find($id);

            if (!$menu)
                throw new \Exception('menu not exist,please fresh current page');

            $menu->sort = $sort;

            if (!$menu->save())
                throw new \Exception('sort failed');

            return Response::success();

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

}