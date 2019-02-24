<?php

namespace Heloufir\SecurityStarter\Http\Controllers;

use App\Http\Controllers\Controller;
use Heloufir\SecurityStarter\Core\Paginator;
use Heloufir\SecurityStarter\Models\Role;
use Heloufir\SecurityStarter\Rules\RoleExists;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
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
        $query = Role::query();
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
                'unique:' . config('security-starter.tables.roles') . ',code'
            ],
            'designation' => [
                'required',
                'max:255'
            ]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(collect($validator->getMessageBag())->flatten()->toArray(), 403);
        }
        $role = new Role();
        $role->code = $request->get('code');
        $role->designation = $request->get('designation');
        $role->save();
        return response()->json(Role::where('id', $role->id)->first(), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *      The role id
     *
     * @return JsonResponse
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    public function show($id): JsonResponse
    {
        $query = Role::query();
        $query->where('id', $id);
        return response()->json($query->first(), $query->count() == 0 ? 404 : 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     *      The request object
     * @param  int $id
     *      The role id
     *
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $rules = [
            'code' => [
                'required',
                'max:255',
                new RoleExists($id),
                'unique:' . config('security-starter.tables.roles') . ',code,' . $id
            ],
            'designation' => [
                'required',
                'max:255'
            ]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(collect($validator->getMessageBag())->flatten()->toArray(), 403);
        }
        $role = Role::where('id', $id)->first();
        $role->code = $request->get('code');
        $role->designation = $request->get('designation');
        $role->save();
        return response()->json(Role::where('id', $role->id)->first(), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     *      The request object
     * @param  int $id
     *      The role id
     *
     * @return JsonResponse
     *
     * @author EL OUFIR Hatim <eloufirhatim@gmail.com>
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $rules = [
            'id' => [
                new RoleExists($id)
            ]
        ];
        $request->request->add(['id' => $id]);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(collect($validator->getMessageBag())->flatten()->toArray(), 403);
        }
        return response()->json(Role::where('id', $id)->delete(), 200);
    }
}
