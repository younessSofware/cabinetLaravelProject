<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Resources\PatientsResource;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Event\Code\Test;
use Spatie\Permission\Middlewares\RoleMiddleware;

class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return PatientsResource::collection(
           Patient::all()
       );

    }


    public function test()
    {
        if (Auth::user()->hasRole('admin')) {
            return [
                'message'=>'this user has role admin'
            ];
        } else {
            Auth::user()->assignRole('admin');
            if (Auth::user()->hasRole('admin')){
                return [
                    'message'=>'this user has now role admin'
                ];
            }

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request)
    {
       $request ->validated($request->all());

       $patient = Patient::create([
           'FullName' =>$request->FullName,
           'CIN' => $request->CIN,
           'PhoneNumber' => $request->PhoneNumber,
           'Age' => $request->Age,
           'DateOfBirth' => $request->DateOfBirth,
           'Adress' => $request->Adress,
           'Password' => $request->Password,
           'Password_Confirmation' => $request->Password_Confirmation,
       ]);

       return new PatientsResource($patient);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return new PatientsResource($patient);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
       $patient->update($request->all());

       return new PatientsResource($patient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response(null,204);
    }
}
