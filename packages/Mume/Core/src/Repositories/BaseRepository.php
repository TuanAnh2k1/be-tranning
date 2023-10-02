<?php

namespace Mume\Core\Repositories;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Mume\Core\Common\CommonConst;
use Mume\Core\Helpers\AuthHelper;
use Mume\Core\Models\Role;
use Mume\Core\Models\User;
use Mume\Core\Repositories\Interfaces\BaseRepositoryInterface;
use Mume\Table\Models\Table;

/**
 * Class BaseRepository
 *
 * @package Mume\Core\Repositories
 */
class BaseRepository implements BaseRepositoryInterface
{
    public const DEFAULT_RECORDS_PER_PAGE = 25;

    /**
     * @var
     */
    protected $model;

    /**
     * @var array
     */
    protected array $searchKeys = ['code'];

    /**
     * @var array|string[]
     */
    protected array $supportedJsonColumns = [];

    /**
     * @var array|string[]
     */
    protected array $supportedFilteringColumns = ['status'];

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->model = $this->setModel();
    }

    /**
     * @return mixed
     * @throws BindingResolutionException
     */
    public function setModel()
    {
        return $this->model = app()->make($this->model);
    }

    /**
     * @param                  $id
     * @param  array|string[]  $columns
     *
     * @return mixed
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param  null            $limit
     * @param  array|string[]  $columns
     *
     * @return mixed
     */
    public function paginate($limit = null, array $columns = ['*'])
    {
        $limit = is_null($limit) ? self::DEFAULT_RECORDS_PER_PAGE : intval($limit);

        return $this->model->paginate($limit);
    }

    /**
     * @param  array  $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        $attributes = $this->stripAllFields($attributes);

        return $this->model->create($attributes);
    }

    /**
     * @param  array | int  $ids
     * @param  array        $attributes
     *
     * @return mixed
     */
    public function update($ids, array $attributes)
    {
        $attributes = $this->stripAllFields($attributes);
        if (is_array($ids)) {
            return $this->model->whereIn('id', $ids)->update($attributes);
        }

        $object = $this->model->findOrFail($ids);
        $object->fill($attributes);
        $object->save();

        return $object;
    }

    /**
     * @param         $id
     * @param  array  $softDeleteData
     * @param  bool   $isSoftDelete
     *
     * @return void
     */
    public function delete($id, array $softDeleteData = [], bool $isSoftDelete = true)
    {
    }

    public function stripAllFields($fields)
    {
        foreach ($fields as $key => $value) {
            if (is_array($value)) {
                $fields[$key] = $this->stripAllFields($value);
            } else {
                if (is_string($value)) {
                    $fields[$key] = strip_tags($value);
                }
            }
        }

        return $fields;
    }

    /**
     * Trả về tất cả bản ghi chưa bị xóa
     *
     * @param  string[]  $columns
     *
     * @return mixed
     */
    public function findAll($columns = ['*'])
    {
        return $this->model::select($columns)->where(['deleted_at' => null])->get();
    }

    public function permanentlyDelete($id)
    {
    }

    /**
     * Lấy ra danh sách theo người dùng đăng nhập tương ứng
     *
     * @return mixed
     * @throws Exception
     */
    public function findByLoggedInUser()
    {
        $loggedInUser = AuthHelper::loggedInUser();

        return $this->findByUserId($loggedInUser->id);
    }

    /**
     * Lấy ra danh sách theo user id tương ứng
     *
     * @param  int  $userId
     *
     * @return mixed
     * @throws Exception
     */
    public function findByUserId(int $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            throw new Exception("Không tìm thấy người dùng có ID = $userId trong hệ thống");
        }

        $role   = $user->role;
        $roleId = $role->id;
        switch ($roleId) {
            case Role::ADMIN_ROLE:
                return $this->findByAdmin();
            case Role::MANAGER_ROLE:
                return $this->findByManager($userId);
            case Role::STAFF_ROLE:
                return $this->findByStaff($userId);
            default:
                return $this->findByGuess();
        }
    }

    /**
     * Tìm kiếm danh sách theo role admin
     *
     * @return Model|mixed
     */
    public function findByAdmin()
    {
        return $this->model;
    }

    /**
     * Tìm kiếm danh sách theo role manager
     *
     * @param  int  $userId
     *
     * @return Model|mixed
     */
    public function findByManager(int $userId)
    {
        return $this->model;
    }

    /**
     * Tìm kiếm danh sách theo role nhân viên
     *
     * @param  int  $userId
     *
     * @return mixed
     */
    public function findByStaff(int $userId)
    {
        return $this->model->where('created_by_id', $userId);
    }

    /**
     * Tìm kiếm danh sách theo role khách hàng
     *
     * @return Model|mixed
     */
    public function findByGuess()
    {
        return $this->model;
    }

    /**
     * Trả về danh sách theo điều kiện
     *
     * @param  array     $conditions
     * @param  string[]  $relations
     * @param  string[]  $columns
     * @param  int|null  $userId
     *
     * @return mixed
     * @throws Exception
     */
    public function findByConditions(array $conditions = [], array $relations = [], int $userId = null, array $columns = ['*'])
    {
        if ($userId) {
            $collection = $this->findByUserId($userId);
        } else {
            $collection = $this->findByLoggedInUser();
        }

        // Apply eagle loading condition
        $collection = $this->with($collection, $conditions, $relations);

        // Apply search condition
        $collection = $this->applySearch($collection, $conditions);

        // Apply filter by condition
        $collection = $this->applyFilters($collection, $conditions);

        // Apply sort by condition
        $collection = $this->applySorts($collection, $conditions);

        // Get all data
        if (empty($conditions['page_size'])) {
            $conditions['page_size'] = $collection->count();
        }

        // Apply pagination by condition
        return $this->applyPagination($collection, $conditions, $columns);
    }

    /**
     * Áp dụng eager loading trước khi lấy dữ liệu
     *
     * @param         $collection
     * @param  array  $conditions
     * @param  array  $relations
     *
     * @return mixed
     */
    protected function with($collection, array $conditions = [], array $relations = [])
    {
        $allRelations = [...$relations];
        if (!empty($conditions['include'])) {
            $allRelations = array_unique(array_merge(explode(CommonConst::COMMA_SEPARATOR, $conditions['include']), $allRelations));
        }


        $model = $this->model->getModel();
        foreach ($allRelations as $index => $relation) {
            if (!method_exists($model, $relation)) {
                unset($allRelations[$index]);
            }
        }

        if (empty($allRelations)) return $collection;

        return $collection->with($allRelations);
    }

    /**
     * Áp dụng tìm kiếm theo điều kiện với key 'query' trong mảng conditions
     *
     * @param         $collection
     * @param  array  $conditions
     *
     * @return mixed
     */
    protected function applySearch($collection, array $conditions = [])
    {
        if (empty($conditions['query'])) return $collection;

        $query      = trim($conditions['query']);
        return $collection->where(function ($data) use ($query) {
            foreach ($this->searchKeys as $index => $key) {
                if ($index === 0) {
                    $data->where($key, 'like', '%'.$query.'%');
                } else {
                    $data->orWhere($key, 'like', '%'.$query.'%');
                }
            }
        });
    }

    /**
     * Áp dụng lọc theo điều kiện với key 'filters' trong mảng conditions
     *
     * @param         $collection
     * @param  array  $conditions
     *
     * @return mixed
     */
    protected function applyFilters($collection, array $conditions = [])
    {
        foreach ($conditions as $field => $values) {
            if (!isset($values) || (!in_array($field, $this->supportedFilteringColumns) && !in_array($field, $this->supportedJsonColumns))) {
                continue;
            }

            $values = array_map('trim', explode(',', $values));
            if (in_array($field, $this->supportedFilteringColumns)) {
                $collection->whereIn("$field", $values);
            }

            if (in_array($field, $this->supportedJsonColumns)) {
                $counter = 1;
                foreach ($values as $value) {
                    $value = "$value";
                    if ($counter > 1) {
                        $collection->orWhereJsonContains("$field", $value);
                    } else {
                        $collection->whereJsonContains("$field", $value);
                    }

                    $counter++;
                }
            }
        }

        return $collection;
    }

    /**
     * Sắp xếp theo điều kiện với key 'sorts' trong mảng conditions
     *
     * @param         $collection
     * @param  array  $conditions
     *
     * @return mixed
     */
    protected function applySorts($collection, array $conditions = [])
    {
        $supportedSortingColumns = ['latest_update_at'];
        if (empty($conditions['sort'])) {
            return $collection->orderBy('latest_update_at', 'desc');
        }

        $sorts = explode(',', $conditions['sort']);
        foreach ($sorts as $sortData) {
            $order  = ($sortData[0] == '-') ? 'desc' : 'asc';
            $column = str_replace(['-', '+', ' '], '', $sortData);
            if (in_array($column, $supportedSortingColumns)) {
                $collection->orderBy("$column", $order);
            }
        }

        return $collection;
    }

    /**
     * Phân trang theo điều kiện lọc với key 'page_size' và 'page' trong mảng conditions
     *
     * @param         $collection
     * @param  array  $conditions
     * @param  array  $columns
     *
     * @return mixed
     */
    protected function applyPagination($collection, array $conditions = [], array $columns = ['*'])
    {
        $pageSize = isset($conditions['page_size']) ? intval($conditions['page_size']) : self::DEFAULT_RECORDS_PER_PAGE;
        $page     = isset($conditions['page']) ? intval($conditions['page']) : 1;

        return $collection->paginate($pageSize, $columns, 'page', $page);
    }

    /**
     * @param  string  $key
     *
     * @return int
     */
    public function nextId(string $key = 'id'): int
    {
        return $this->model->max($key) + 1;
    }
}
