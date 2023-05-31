<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Resources\PatientsResource;
use App\Http\Resources\PatientsWithLastAppointmentResource;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PatientsWithLastAppointmentResource::collection(Patient::all());
    }

    public function test()
    {
        if (Auth::user()->hasRole('admin')) {
            return [
                'message' => 'This user has role admin'
            ];
        } else {
            Auth::user()->assignRole('admin');
            if (Auth::user()->hasRole('admin')) {
                return [
                    'message' => 'This user now has role admin'
                ];
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request)
    {
        $request->validated();

        $patient = Patient::create([
            'FullName' => $request->FullName,
            'CIN' => $request->CIN,
            'PhoneNumber' => $request->PhoneNumber,
            'Age' => $request->Age,
            'DateOfBirth' => $request->DateOfBirth,
            'Adress' => $request->Adress,
            'Password' => $request->Password,
            'Password_Confirmation' => $request->Password_Confirmation,
        ]);

        if ($request->hasFile('cin_image')) {
            $cinImage = $request->file('cin_image');
            $imageName = time() . '.' . $cinImage->getClientOriginalExtension();
            $imagePath = 'public/images/' . $imageName;
            $image = Image::make($cinImage)->resize(400, 300);
            $image->save(storage_path('app/' . $imagePath));

            $patient->cin_image = $imageName;
            $patient->save();
        }

        return new PatientsResource($patient);
    }

    /**
     * Display the specified resource.
     */
    public function show($cin)
    {
        $patient = Patient::where('CIN', $cin)->first();

        if ($patient) {
            return new PatientsResource($patient);
        } else {
            return response()->json(['message' => 'Patient not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cin)
    {
        $patient = Patient::where('CIN', $cin)->first();

        if ($patient) {
            if ($request->hasFile('cin_image')) {
                $cinImage = $request->file('cin_image');
                $imageName = time() . '.' . $cinImage->getClientOriginalExtension();
                $imagePath = 'images/' . $imageName;
                $image = Image::make($cinImage)->resize(400, 300);
                $image->save(storage_path('app/' . $imagePath));

                // Delete the old image file if needed
                if ($patient->cin_image) {
                    Storage::delete('images/' . $patient->cin_image);
                }

                $patient->cin_image = $imageName;
                $patient->save();
            }

            $patient->update($request->all());
        }

        return new PatientsResource($patient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        // Delete the patient's image file if needed
        if ($patient->cin_image) {
            Storage::delete('images/' . $patient->cin_image);
        }

        $patient->delete();

        return response()->json(['message' => 'Patient deleted successfully'], 204);
    }
}
