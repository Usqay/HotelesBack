<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q = request()->query('q');
        $paginate = request()->query('paginate') != null ? request()->query('paginate') : 15;

        $users = User::orderBy('id', 'DESC')
            ->where(function ($query) use ($q) {
                $query->where("name", "like", "%$q%")
                    ->orWhere("email", "like", "%$q%");
            });

        if(!auth()->user() || !auth()->user()->hasRole('Staff')){
            $users->where('id', '!=', 1);
        }

        return UserResource::collection($users->paginate($paginate));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        try {

            DB::beginTransaction();

            $data = $request->all();

            $birthday_date = Carbon::parse($data['birthday_date']);
            $data['birthday_date']=$birthday_date->format('Y-m-d');

            $data['full_name'] = $data['name'] . ' ' . $data['last_name'];

            $people = People::create($data);

            $user = User::create([
                'name' => $data['full_name'],
                'email' => $data['email'],
                'people_id' => $people->id,
                'password' => Hash::make($data['password']),
                'email_verified_at' => date('Y-m-d H:i:s'),
            ]);

            $role = Role::findOrFail($data['role_id']);

            $user->assignRole($role->name);

            $this->saveUserLog($user);

            DB::commit();

            return $this->successResponse(new UserResource($user), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if(!auth()->user() || !auth()->user()->hasRole('Staff')){
           if($user->id == 1){
               return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);
           }
        }

        return $this->successResponse(new UserResource($user));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserUpdateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {

            DB::beginTransaction();

            $data = $request->all();

            $data['full_name'] = $data['name'] . ' ' . $data['last_name'];

            if ($data['birthday_date']) {
                $data['birthday_date'] = explode('T', $data['birthday_date'])[0];
            }

            $people = People::findOrFail($user->people_id);

            $people->fill($data);

            $people->update();

            if ($data['password'] != null) {
                $user->update([
                    'name' => $data['full_name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);
            } else {
                $user->update([
                    'name' => $data['full_name'],
                    'email' => $data['email'],
                ]);
            }

            $role = Role::findOrFail($data['role_id']);

            $user->assignRole($role->name);

            $this->saveUserLog($user, 'update');

            DB::commit();

            return $this->successResponse(new UserResource($user), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse("Couldn't update data" . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        $user = User::withTrashed()->findOrFail($user);

        if ($user->trashed()) {
            $user->restore();
            $this->saveUserLog($user, 'restore');
        } else {
            $user->delete();
            $this->saveUserLog($user, 'delete');
        }

        return $this->successResponse(new UserResource($user));
    }
}
