<?php

namespace Heloufir\SecurityStarter\Core;


use Heloufir\SecurityStarter\Models\Profile;

trait UserProfiles
{

    /**
     * Get profiles related to the user
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    public function profiles()
    {
        return $this->belongsToMany(Profile::class, config('security-starter.tables.associations.user_profiles'), 'refUser', 'refProfile');
    }

}
