<?php

namespace Heloufir\SecurityStarter\Http\Controllers;

use App\Http\Controllers\Controller;
use Heloufir\SecurityStarter\Core\Paginator;
use Heloufir\SecurityStarter\Models\Profile;
use Heloufir\SecurityStarter\Rules\ProfileExists;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use Paginator;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *      The request object
     *
     * @return JsonResponse
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    public function index(Request $request): JsonResponse
    {
        $query = Profile::query();
        $query->with(['roles']);
        return response()->json(self::paginate($query, $request), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *      The request object
     *
     * @return JsonResponse
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    public function store(Request $request): JsonResponse
    {
        $rules = [
            'code' => [
                'required',
                'max:255',
                'unique:' . config('security-starter.tables.profiles') . ',code'
            ],
            'designation' => [
                'required',
                'max:255'
            ],
            'roles' => [
                'array'
            ]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(collect($validator->getMessageBag())->flatten()->toArray(), 403);
        }
        $profile = new Profile();
        $profile->code = $request->get('code');
        $profile->designation = $request->get('designation');
        $profile->save();
        if ($request->has('roles')) {
            foreach ($request->get('roles') as $role) {
                DB::table(config('security-starter.tables.associations.profile_roles'))
                    ->insert([
                        'refProfile' => $profile->id,
                        'refRole' => $role
                    ]);
            }
        }
        return response()->json(Profile::where('id', $profile->id)->with(['roles'])->first(), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *      The profile id
     *
     * @return JsonResponse
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    public function show($id): JsonResponse
    {
        $query = Profile::query();
        $query->where('id', $id);
        $query->with(['roles']);
        return response()->json($query->first(), $query->count() == 0 ? 404 : 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *      The request object
     * @param  int $id
     *      The profile id
     *
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $rules = [
            'code' => [
                'required',
                'max:255',
                new ProfileExists($id),
                'unique:' . config('security-starter.tables.profiles') . ',code,' . $id
            ],
            'designation' => [
                'required',
                'max:255'
            ],
            'roles' => [
                'array'
            ]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(collect($validator->getMessageBag())->flatten()->toArray(), 403);
        }
        $profile = Profile::where('id', $id)->first();
        $profile->code = $request->get('code');
        $profile->designation = $request->get('designation');
        $profile->save();
        DB::table(config('security-starter.tables.associations.profile_roles'))
            ->where('refProfile', $id)
            ->delete();
        if ($request->has('roles')) {
            foreach ($request->get('roles') as $role) {
                DB::table(config('security-starter.tables.associations.profile_roles'))
                    ->insert([
                        'refProfile' => $profile->id,
                        'refRole' => $role
                    ]);
            }
        }
        return response()->json(Profile::where('id', $profile->id)->with(['roles'])->first(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     *      The request object
     * @param  int $id
     *      The profile id
     *
     * @return JsonResponse
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $rules = [
            'id' => [
                new ProfileExists($id)
            ]
        ];
        $request->request->add(['id' => $id]);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(collect($validator->getMessageBag())->flatten()->toArray(), 403);
        }
        DB::table(config('security-starter.tables.associations.profile_roles'))
            ->where('refProfile', $id)
            ->delete();
        return response()->json(Profile::where('id', $id)->delete(), 200);
    }
}
