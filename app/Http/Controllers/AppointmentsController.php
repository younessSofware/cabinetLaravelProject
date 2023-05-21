<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentsWithPatientResource;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/appointments",
     *     summary="Get all appointments",
     *     tags={"Appointments"},
     *     description="Retrieve a list of all appointments",
     *     security={{"sanctum":{}}},
     *     operationId="getAppointments",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Schema(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="date", type="string", format="date-time"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *
     *                 )
     *             )
     *         )
     *     ),
     * )
     */

    public function index()
    {
        return AppointmentsWithPatientResource::collection(
            Appointment::all()
        );
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

    /**
     * @OA\Post(
     *     path="/api/appointments",
     *     tags={"Appointments"},
     *     summary="Create an appointment",
     *     description="Create a new appointment",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Appointment details",
     *         @OA\JsonContent(
     *             required={"patient_id", "start_time", "end_time", "reason"},
     *             @OA\Property(property="patient_id", type="integer", example="1"),
     *             @OA\Property(property="start_time", type="string", format="date-time", example="2023-05-14 09:00:00"),
     *             @OA\Property(property="end_time", type="string", format="date-time", example="2023-05-14 10:00:00"),
     *             @OA\Property(property="reason", type="string", example="Routine checkup")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Schema(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="date", type="string", format="date-time"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid input")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function store(StoreAppointmentRequest $request)
    {
        $request->validated($request->all());

        $availableSlots = Appointment::where(function($query) use ($request) {
            $query->whereBetween('start_time', [$request['start_time'], $request['end_time']])
                ->orWhereBetween('end_time', [$request['start_time'], $request['end_time']])
                ->orWhere(function($query) use ($request) {
                    $query->where('start_time', '<', $request['start_time'])
                        ->where('end_time', '>', $request['end_time']);
                });
        })->count();

        if ($availableSlots > 0) {
            return response()->json(['error' => 'There is already an appointment scheduled during this time.'], 409);
        }

        $appointment = Appointment::create([
            'patient_id'=>$request->patient_id,
            'start_time'=>$request->start_time,
            'end_time'=>$request->end_time,
            'reason'=>$request->reason,
        ]);
        return new AppointmentResource($appointment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment);
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

    /**
     * @OA\Put(
     *     path="/api/appointments/{appointment}",
     *     tags={"Appointments"},
     *     summary="Update an appointment",
     *     description="Update an existing appointment",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="appointment",
     *         in="path",
     *         required=true,
     *         description="Appointment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Appointment details",
     *         @OA\JsonContent(
     *             required={"patient_id", "start_time", "end_time", "reason"},
     *             @OA\Property(property="patient_id", type="integer", example="1"),
     *             @OA\Property(property="start_time", type="string", format="date-time", example="2023-05-14 09:00:00"),
     *             @OA\Property(property="end_time", type="string", format="date-time", example="2023-05-14 10:00:00"),
     *             @OA\Property(property="reason", type="string", example="Updated reason")
     *         )
     *     ),@OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Schema(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="date", type="string", format="date-time"),
     *                     @OA\Property(property="description", type="string"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid input")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function update(Request $request, Appointment $appointment)
    {
        $appointment->update($request->all());

        return new AppointmentResource($appointment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response(null,204);
    }
}
