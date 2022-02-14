<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\UserPermission
 *
 * @property int $id
 * @property int $permissions_id Permiso que se le brinda al usuario
 * @property int $users_id Usuario al que se le brinda el permiso
 * @property int $created_users_id Responsable de la asociacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereCreatedUsersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission wherePermissionsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereUsersId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Permission|null $permission
 * @method static \Illuminate\Database\Query\Builder|UserPermission onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|UserPermission withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UserPermission withoutTrashed()
 */
class UserPermission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "permissions_id",
        "users_id",
        "created_users_id",
    ];

    public function permission()
    {
        return $this->hasOne(Permission::class, 'id', 'permissions_id');
    }
}
