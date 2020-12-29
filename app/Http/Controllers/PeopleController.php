<?php

namespace App\Http\Controllers;

use App\Http\Requests\PeopleStoreRequest;
use App\Http\Resources\PeopleResource;
use App\Models\People;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PeopleController extends Controller
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

        $people = People::orderBy('id', 'DESC')
        ->where(function ($query) use ($q) {
            $query->where("full_name", "like", "%$q%")
            ->orWhere("document_number", "like", "%$q%")
            ->orWhere("email", "like", "%$q%");
        })
        ->where('id', '>', 1)
        ->paginate($paginate);

        return PeopleResource::collection($people);
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
     * @param  \App\Http\Requests\PeopleStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PeopleStoreRequest $request)
    {
        try{
            
            DB::beginTransaction();

            $data = $request->all();

            if($data['full_name'] == null || $data['full_name'] == '') $data['full_name'] = $data['name'] . " " . $data['last_name'];
            
            $people = People::updateOrCreate([
                'document_number' => $data['document_number']
            ], [
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'full_name' => $data['full_name'],
                'gender_id' => $data['gender_id'],
                'document_type_id' => $data['document_type_id'],
                'address' => $data['address'],
                'phone_number' => $data['phone_number'],
                'email' => $data['email'],
                'birthday_date' => $data['birthday_date'],
            ]);
            
            $this->saveUserLog($people);

            DB::commit();
            
            return $this->successResponse(new PeopleResource($people), Response ::HTTP_CREATED);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->errorResponse("Couldn't store data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\People  $people
     * @return \Illuminate\Http\Response
     */
    public function show(People $people)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\People  $people
     * @return \Illuminate\Http\Response
     */
    public function edit(People $people)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\People  $people
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, People $people)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\People  $people
     * @return \Illuminate\Http\Response
     */
    public function destroy(People $people)
    {
        //
    }
}
