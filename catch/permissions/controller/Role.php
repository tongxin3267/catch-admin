<?php
namespace catchAdmin\permissions\controller;

use app\Request;
use catcher\base\CatchController;
use catcher\CatchForm;
use catcher\CatchResponse;
use catcher\exceptions\FailedException;
use catcher\Tree;
use think\response\Json;

class Role extends CatchController
{
    protected $role;

    public function __construct(\catchAdmin\permissions\model\Roles $role)
    {
        $this->role = $role;
    }

  /**
   *
   * @time 2019年12月09日
   * @param Request $request
   * @return string
   */
    public function index(Request $request)
    {
      return CatchResponse::success(Tree::done($this->role->getList($request->param())));
    }

    /**
     *
     * @time 2019年12月11日
     * @throws \Exception
     * @return string
     */
    public function create()
    {}

    /**
     *
     * @time 2019年12月11日
     * @param Request $request
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function save(Request $request)
    {
        $this->role->storeBy($request->param());

        if (!empty($request->param('permissions'))) {
            $this->role->attach($request->param('permissions'));
        }
        // 添加角色
        return CatchResponse::success();
    }

    public function read($id)
    {
      $role = $this->role->findBy($id);
      $role->permissions = $role->getPermissions();
      return CatchResponse::success($role);
    }

    /**
     *
     * @time 2019年12月11日
     * @param $id
     * @throws \Exception
     * @return string
     */
    public function edit($id)
    {}

    /**
     *
     * @time 2019年12月11日
     * @param $id
     * @param Request $request
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function update($id, Request $request)
    {
        $this->role->updateBy($id, $request->param());

        $role = $this->role->findBy($id);

        $role->detach();

        if (!empty($request->param('permissions'))) {
            $role->attach($request->param('permissions'));
        }

        return CatchResponse::success();
    }

    /**
     *
     * @time 2019年12月11日
     * @param $id
     * @throws FailedException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return Json
     */
    public function delete($id)
    {
        if ($this->role->where('parent_id', $id)->find()) {
            throw new FailedException('存在子角色，无法删除');
        }
        $role = $this->role->findBy($id);
        // 删除权限
        $role->detach();
        // 删除用户关联
        $role->users()->detach();
        // 删除
        $this->role->deleteBy($id);

        return CatchResponse::success();
    }

    /**
     *
     * @time 2019年12月11日
     * @param Request $request
     * @param \catchAdmin\permissions\model\Permissions $permission
     * @return Json
     */
    public function getPermissions(Request $request, \catchAdmin\permissions\model\Permissions $permission): Json
    {
        $parentRoleHasPermissionIds = null;
        if ($request->param('parent_id')) {
            $permissions = $this->role->findBy($request->param('parent_id'))->getPermissions();
            foreach ($permissions as $_permission) {
                $parentRoleHasPermissionIds[] = $_permission->pivot->permission_id;
            }
        }

        $permissions = Tree::done($permission->getList([
            'permission_ids' => $parentRoleHasPermissionIds
        ]));

        $permissionIds = [];
        if ($request->param('role_id')) {
            $roleHasPermissions = $this->role->findBy($request->param('role_id'))->getPermissions();
            foreach ($roleHasPermissions as $_permission) {
                $permissionIds[] = $_permission->pivot->permission_id;
            }
        }

        return CatchResponse::success([
            'permissions' => $permissions,
            'hasPermissions' => $permissionIds,
        ]);
    }
}
