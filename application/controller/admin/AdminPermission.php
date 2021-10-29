<?php


namespace controller\admin;

use annotation\ApiResponseHeader;
use annotation\View;
use EMicro\Factory;
use EMicro\Request;
use logic\Permission;
use util\Helper;
use util\Response;

/**
 * @Controller
 * @Route
 */
class AdminPermission
{

    /**
     * @Route(system/permissions)
     * @View(admin/permissions/index)!after
     */
    public function index(){}

    /**
     * @Route(system/permissions/render)
     * @ApiResponseHeader()
     */
    public function menuRender(){

        $permissions = \model\AdminPermission::query()->orderByRaw('pid,sort,id')->get()->toArray();

        return Helper::list2Tree($permissions,'pid',function ($item){
            return [
                'id'     => $item['id'],
                'title'  => $item['title'],
                'unique' => $item['code'],
                'type'   => $item['type'],
                'sort'   => $item['sort'],
            ];
        });
    }

    /**
     * @Route(system/permission/save)
     * @ApiResponseHeader()
     */
    public function menuSave(Request $request){

        try {

            if ($request->input('is_add')){

                $permission = new \model\AdminPermission();
                $permission->type   = 1;
                $permission->pid    = $request->input('parent_id',0);
                $permission->sort   = \model\AdminPermission::query()->where('pid',$request->input('parent_id',0))->max('sort') + 1;

            }else{
                $permission = \model\AdminPermission::query()->find($request->input('parent_id'));
            }

            $permission->title = $request->input('title');
            $permission->code  = $request->input('unique');
            $permission->type  = $request->input('type');

            if (!$permission->save())
                throw new \Exception('save failed');

            return Response::success();

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

    /**
     * @Route(system/permission/delete)
     * @ApiResponseHeader()
     */
    public function permissionDelete(Request $request){

        try {

            $id = $request->input('id');

            $menu = \model\AdminPermission::query()->find($id);

            if (!$menu)
                throw new \Exception('menu not exist');

            \model\AdminPermission::query()->where('id',$id)->delete();

            return Response::success();

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

    /**
     * @Route(system/permissions/sort)
     * @ApiResponseHeader()
     */
    public function permissionsSort(Request $request){

        try {

            $sort = $request->input('sort');
            $id   = $request->input('id');

            $permission = \model\AdminPermission::query()->find($id);

            if (!$permission)
                throw new \Exception('permission not exist,please fresh current page');

            $permission->sort = $sort;

            if (!$permission->save())
                throw new \Exception('sort failed');

            return Response::success();

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }


}