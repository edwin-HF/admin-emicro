<?php


namespace controller\admin;

use annotation\ApiResponseHeader;
use annotation\View;
use EMicro\Request;
use model\AdminPermissions;
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

        $permissions = \model\AdminPermissions::query()
            ->orderBy('pid, sort, id')
            ->select();

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
    public function menuSave(){

        try {

            $parentId = Request::input('parent_id',0);

            $saveData['title'] = Request::input('title');
            $saveData['code']  = Request::input('unique');
            $saveData['type']  = Request::input('type');

            if (Request::input('is_add')){

                $res = AdminPermissions::query()->insert(
                    array_merge($saveData,[
                        'type' => 1,
                        'pid'  => $parentId,
                        'sort' => AdminPermissions::query()->where([['pid', '=', $parentId]])->max('sort') + 1
                    ])
                );

            }else{
                $res = AdminPermissions::query()->where([['id', '=', $parentId]])->update($saveData);
            }

            if (!$res)
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
    public function permissionDelete(){

        try {

            $id = Request::input('id');

            $menu = \model\AdminPermissions::query()->getInfo([['id', '=', $id]]);

            if (!$menu)
                throw new \Exception('menu not exist');

            \model\AdminPermissions::query()->where([['id', '=', $id]])->delete();

            return Response::success();

        }catch (\Exception $exception){
            return Response::error($exception->getMessage());
        }

    }

    /**
     * @Route(system/permissions/sort)
     * @ApiResponseHeader()
     */
    public function permissionsSort(){

        try {

            $sort = Request::input('sort');
            $id   = Request::input('id');

            $permission = \model\AdminPermissions::query()->getInfo([['id', '=', $id]]);

            if (!$permission)
                throw new \Exception('permission not exist,please fresh current page');

            $res = AdminPermissions::query()->where([['id', '=', $id]])->update(
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