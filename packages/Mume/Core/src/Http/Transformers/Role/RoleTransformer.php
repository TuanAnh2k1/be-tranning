<?php

namespace Mume\Core\Http\Transformers\Role;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Mume\Core\Models\Role;

/**
 * Class RoleTransformer
 *
 * @package Mume\Core\Http\Transformers\Role
 */
class RoleTransformer extends TransformerAbstract
{
    /**
     * Transform data
     *
     * @param  Role  $row
     *
     * @return array
     */
    public function transform(Role $row): array
    {
        $createdByRoleId = $row->created_user ? $row->created_user['role_id'] : 0;
        return [
            'id'              => (int) $row['id'],
            'name'            => $row['name'],
            'shortcut_role'   => Role::getShortRole($row['id']),
            'description'     => $row['description'],
            'created_by'                => [
                'id'             => (int) $row['created_by_id'],
                'shortcut_role'  => Role::getShortRole($createdByRoleId),
                'text'           => $row['created_by_name'],
            ],
            'created_at'      => Carbon::parse($row['created_at'])->toDateTimeString(),
        ];
    }
}
