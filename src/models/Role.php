<?php

namespace Heloufir\SecurityStarter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
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
        $this->table = config('security-starter.tables.roles');
    }

    /**
     * Get profiles related to this role
     *
     * @return BelongsToMany
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class, config('security-starter.tables.associations.profile_roles'), 'refRole', 'refProfile');
    }
}
