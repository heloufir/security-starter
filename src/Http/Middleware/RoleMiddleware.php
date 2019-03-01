<?php

namespace Heloufir\SecurityStarter\Http\Middleware;

use Closure;
use Illuminate\Support\Collection;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param array $roles
     *      The roles list if exists
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if ($request->user() == null) {
            return response()->json(['error' => 'unauthorized', 'message' => 'The request does not contains token'], 401);
        }
        $this->hasRole($request->user(), $roles);
        return $next($request);
    }

    /**
     * Check if the user has a list of roles, based on a type (any or all)
     *
     * @param $user
     *      The user object
     * @param array $roles
     *      The roles array
     *
     * @return bool
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    private function hasRole($user, array $roles): bool
    {
        $roles = collect($roles);
        switch ($roles->first()) {
            case 'any':
                $roles->forget(0);
                return $this->any($user, $roles);
            case 'all':
                $roles->forget(0);
                return $this->all($user, $roles);
            default:
                return $this->any($user, $roles);
        }
    }

    /**
     * Check if the user has any role of a collection of roles
     *
     * @param $user
     *      The user object
     * @param Collection $roles
     *      The roles collection
     *
     * @return bool
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    private function any($user, Collection $roles): bool
    {
        $result = false;
        foreach ($user->profiles as $profile) {
            $result = $result || $profile->roles->pluck('code')->intersect($roles)->count() != 0;
        }
        return $result;
    }

    /**
     * Check if the user has all roles of a collection of roles
     *
     * @param $user
     *      The user object
     * @param Collection $roles
     *      The roles collection
     *
     * @return bool
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    private function all($user, Collection $roles): bool
    {
        $results = collect();
        foreach ($user->profiles as $profile) {
            $intersection = $profile->roles->pluck('code')->intersect($roles);
            foreach ($intersection as $item) {
                if (!$results->contains($item)) {
                    $results->push($item);
                }
            }
        }
        dd($results->count() == $roles->count());
        return $results->count() == $roles->count();
    }
}
