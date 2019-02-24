<?php

namespace Heloufir\SecurityStarter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Profile extends Model
{
    public $primaryKey = 'id';
    public $timestamps = true;

    public $fillable = [
        'code',
        'designation'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('security-starter.tables.profiles');
    }

    /**
     * Get roles related to this profile
     *
     * @return BelongsToMany
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, config('security-starter.tables.associations.profile_roles'), 'refProfile', 'refRole');
    }

    /**
     * Get users related to this profile
     *
     * @return BelongsToMany
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('auth.providers.users.model'), config('security-starter.tables.associations.user_profiles'), 'refProfile', 'refUser');
    }
}
