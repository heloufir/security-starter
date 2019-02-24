<?php

namespace Heloufir\SecurityStarter\Core;


use Illuminate\Support\Facades\DB;

trait UserServices
{

    /**
     * Attach profiles to the user
     *
     * @param int $user
     *      The user's id
     * @param array $profiles
     *      The profiles list, contains only profiles id
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    public function attacheProfilesToUser(int $user, array $profiles)
    {
        DB::table(config('security-starter.tables.associations.user_profiles'))
            ->where('refUser', $user)
            ->delete();
        foreach ($profiles as $profile) {
            DB::table(config('security-starter.tables.associations.user_profiles'))
                ->insert([
                    'refUser' => $user,
                    'refProfile' => $profile
                ]);
        }
    }

}