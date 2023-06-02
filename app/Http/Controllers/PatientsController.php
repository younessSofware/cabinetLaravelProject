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
use App\Helpers\PDFHelper;

class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $query =  $req->cin;
        $skip = $req->skip;
        $take = $req->take;
        // Retrieve all users with the secretary role

        if (!empty($query)) {
            $pt = Patient::where('CIN', 'like', $query . '%');
            return ['data' => 
            PatientsWithLastAppointmentResource::collection($pt->skip($skip)->take($take)->get()),
            'total' => $pt->count()];
        }
        return ['data' => 
        PatientsWithLastAppointmentResource::collection(Patient::skip($skip)->take($take)->get()),
        'total' => Patient::count()];;
    }

    public function generateReportPDF($patientId)
    {
        // Get the patient data and appointments
        $patient = Patient::findOrFail($patientId);
        $appointments = $patient->appointments;

        // Load the HTML template
        $html = view('patient_report', compact('patient', 'appointments'))->render();

        // Generate the PDF using the helper function
        $pdfUrl = PDFHelper::generatePDF($html, 'patient_report.pdf');

        // Return the JSON response with the PDF URL
        return response()->json([
            'status' => 'Request was successful.',
            'message' => null,
            'data' => [
                'pdf_url' => $pdfUrl,
            ],
        ]);
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
            $patient->cin_image = $request->cin_image->store('images','public');
            $patient->save();
        }

        return new PatientsResource($patient);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $patient = Patient::with('appointments')->find($id);

        if ($patient) {
            return new PatientsResource($patient);
        } else {
            return response()->json(['message' => 'Patient not found'], 404);
        }
    }

    public function modify(Request $request, $cin)
    {
        return $this->update($request, $cin);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cin)
    {
        $patient = Patient::where('CIN', $cin)->first();

        if ($patient) {

            $patient->update($request->all());
            
            if ($request->hasFile('cin_image')) {
                Storage::delete('images/' . $patient->cin_image);
                // Delete the old image file if needed
                $patient->cin_image = $request->cin_image->store('images','public');
                $patient->save();
            }
        }

        return new PatientsResource($patient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $patient = Patient::where('id', $id)->first();
        // Delete the patient's image file if needed
        if ($patient->cin_image) {
            Storage::delete('images/' . $patient->cin_image);
        }

        $patient->delete();

        return response()->json(['message' => 'Patient deleted successfully'], 204);
    }
}
