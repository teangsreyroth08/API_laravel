<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;


class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {

        try {
            $perPage = $request->get('per_page', 10);

            $items = Prescription::query()
            ->with(['doctor.user:id,name','doctor.specialization:id,name','patient','details.medicine:id,item_name'])
            ->paginate($perPage);


            return response()->json([
                'data'    => $items,
                'message' => 'List all of medical records'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }

    }

    public function listByPatient(Request $request)
    {

        try {
            $patient_id = $request->patient_id;

            $data = Prescription::where('patient_id', $patient_id)
            ->with(['doctor.user:id,name','doctor.specialization:id,name','patient','details.medicine:id,item_name'])
            ->orderBy('created_at', 'desc')
            ->get();

            return response()->json([
                'data'    => $data,
                'message' => 'List all'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function listPatientByDoctor(Request $request)
    {

        try {
            $doctor_id = $request->doctor_id;
            $limit     = $request->limit;
            $patients = Patient::with('medicalRecords')
            ->leftJoin('prescriptions', 'patients.id', '=', 'prescriptions.patient_id')
            ->where('prescriptions.doctor_id', $doctor_id)
            ->leftJoin('doctors', 'doctors.id', '=', 'prescriptions.doctor_id')
            ->select('doctors.id as doctor_id', 'patients.*')
            ->distinct()
            ->limit($limit??5)
            ->get();

            return response()->json([
                'data'    => $patients,
                'message' => 'List all patients'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }


    public function create(Request $request)
    {
        try {
            $request->validate([
                'patient_id'           => 'required|integer',
                'doctor_id'            => 'required|integer',
                'details'              => 'required|array',
                'details.*.medicine_id' => 'required|integer',
                'details.*.dosage'     => 'required',
                'details.*.frequency'  => 'required',
                'details.*.duration'   => 'required',
                'date'                 => 'required|date',
                'age'                  => 'required|integer',
                'blood_pressure'       => 'nullable|string',
                'weight'               => 'nullable|numeric',
                'height'               => 'nullable|numeric',
                'diagnosis'            => 'nullable|string',
                'treatment'            => 'nullable|string',
                'next_appointment_date'=> 'nullable|date',
            ]);

            $prescription = new Prescription();
            $prescription->consultation_id = 1; // Assuming consultation_id is fixed for now
            $prescription->patient_id = $request->patient_id;
            $prescription->doctor_id = $request->doctor_id;
            $prescription->created_by = Auth::user()->id;

            // Assigning additional fields from request
            $prescription->date = $request->date;
            $prescription->age = $request->age;
            $prescription->blood_pressure = $request->blood_pressure;
            $prescription->weight = $request->weight;
            $prescription->height = $request->height;
            $prescription->diagnosis = $request->diagnosis;
            $prescription->treatment = $request->treatment;
            $prescription->next_appointment_date = $request->next_appointment_date;

            if ($request->notes) {
                $prescription->notes = $request->notes;
            }

            $prescription->save();

            if ($prescription->details) {
                foreach ($request->details as $detail) {
                    $prescription->details()->create($detail);
                }
            }

            $data = Prescription::with(['doctor.user:id,name', 'doctor.specialization:id,name', 'patient', 'details.medicine:id,item_name'])->findOrFail($prescription->id);

            return response()->json([
                'data'    => $data,
                'message' => 'Medical record created successfully'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(
                [
                    'status'  => 'fail',
                    'message' => 'Something went wrong: ' . $e->getMessage()
                ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'patient_id'           => 'required|integer',
                'doctor_id'            => 'required|integer',
                'details'              => 'required|array',
                'details.*.medicine_id' => 'required|integer',
                'details.*.dosage'     => 'required',
                'details.*.frequency'  => 'required',
                'details.*.duration'   => 'required',
                'date'                 => 'required|date',
                'age'                  => 'required|integer',
                'blood_pressure'       => 'nullable|string',
                'weight'               => 'nullable|numeric',
                'height'               => 'nullable|numeric',
                'diagnosis'            => 'nullable|string',
                'treatment'            => 'nullable|string',
                'next_appointment_date'=> 'nullable|date',
            ]);

            $prescription = Prescription::findOrFail($id);
            $prescription->patient_id = $request->patient_id;
            $prescription->doctor_id = $request->doctor_id;
            $prescription->created_by = Auth::user()->id;

            // Updating additional fields from request
            $prescription->date = $request->date;
            $prescription->age = $request->age;
            $prescription->blood_pressure = $request->blood_pressure;
            $prescription->weight = $request->weight;
            $prescription->height = $request->height;
            $prescription->diagnosis = $request->diagnosis;
            $prescription->treatment = $request->treatment;
            $prescription->next_appointment_date = $request->next_appointment_date;

            if ($request->has('notes')) {
                $prescription->notes = $request->notes;
            }

            $prescription->save();

            // Update prescription details
            $prescription->details()->delete(); // Remove old details
            foreach ($request->details as $detail) {
                $prescription->details()->create($detail);
            }

            $data = Prescription::with(['doctor.user:id,name', 'doctor.specialization:id,name', 'patient', 'details'])->findOrFail($prescription->id);

            return response()->json([
                'data'    => $data,
                'message' => 'Prescription updated successfully'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getById($id)
    {
        try {
            $prescription = Prescription::with(['doctor.user:id,name', 'doctor.specialization:id,name', 'patient', 'details.medicine:id,item_name'])->findOrFail($id);

            return response()->json([
                'data'    => $prescription,
                'message' => 'Prescription retrieved successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $prescription = Prescription::findOrFail($id);

            $prescription->details()->delete();

            $prescription->delete();

            return response()->json([
                'message' => 'Prescription deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }


    private function __generateInvoiceID(){
        $lastInvoice = Prescription::latest()->first();

        if ($lastInvoice) {
            $lastInvoiceId = preg_replace('/[^0-9]/', '', $lastInvoice->invoice_number);
            $newId = str_pad((int)$lastInvoiceId + 1, 10, '0', STR_PAD_LEFT);
        } else {
            $newId = '0000000001';
        }

        return $newId;
    }

    public function search(Request $request)
    {
        try {

            $byContact    = $request->contact??null;
            $byName       = $request->name??null;
            $byIdCard     = $request->id_card??null;
            $byPatientId  = $request->patient_id??null;

            $patient = Patient::with([
                'medicalRecords.doctor.user:id,name',
                'medicalRecords.doctor.specialization:id,name',
                'medicalRecords.patient',
                'medicalRecords.details.medicine:id,item_name',
            ]);

            if ($byContact){
                $patient = $patient->where('contact', 'like', "%$byContact%");
            }
            if ($byName){
                $patient = $patient->where('name','like', "%$byName%");
            }
            if ($byIdCard){
                $patient = $patient->where('id_card', 'like', "%$byIdCard%");
            }
            if ($byPatientId){
                $patient = $patient->where('patient_id', 'like', "%$byPatientId%");
            }

            $patient = $patient->get();

            return response()->json(
                [
                    'status'  => 'success',
                    'data'    => $patient,
                    'message' => 'Patient found successfully'
                ]
            , 201);

        } catch (QueryException $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }


}
