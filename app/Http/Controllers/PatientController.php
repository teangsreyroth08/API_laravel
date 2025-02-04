<?php

namespace App\Http\Controllers;

use App\Enum\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $perPage = $request->get('per_page', 10);

            $patients = Patient::query()
            ->with(['user'])
            ->paginate($perPage);

            return response()->json([
                'data'    => $patients,
                'message' => 'List all of patients'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(
            [
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);        }
    }



    public function create(Request $request)
    {
        try {
            $request->validate([
                'name'      => 'required|max:255',
                'age'       => 'required|numeric|integer',
                'gender'    => 'required',
                'contact'   => 'required|unique:users,phone_number',
                'id_card'   => 'min:9|max:9|unique:patients,id_card',
                'email'     => 'required|email|unique:patients,email',
                'date_of_birth' => 'required|date',
                'passport'  => 'nullable',
                'address'   => 'nullable',
                'emergency_contact_name'  => 'nullable',
                'emergency_contact_phone' => 'nullable',
                'blood_type'   => 'nullable',
                'chronic_conditions' => 'nullable',
                'current_medications' => 'nullable',
                'previous_surgeries' => 'nullable',
                'family_medical_history' => 'nullable',
                'preferred_doctor' => 'nullable',
                'insurance_provider' => 'nullable',
                'insurance_policy_number' => 'nullable',
                'billing_address' => 'nullable'
            ]);

            $patient_id = $this->__generatePatientID();

            $user = User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => Hash::make($request->contact),
                'role_id'       => RoleEnum::Patient,
                'phone_number'  => $request->contact,
                'address'       => $request->address,
                'is_active'     => 1
            ]);

            $patient = Patient::create([
                'user_id'    => $user->id,
                'patient_id' => $patient_id,
                'name'       => $request->name,
                'age'        => $request->age,
                'gender'     => $request->gender,
                'contact'    => $request->contact,
                'id_card'    => $request->id_card ?? '',
                'allergies'  => $request->allergies ?? 'n/a',
                'created_by' => Auth::user()->id,
                'date_of_birth' => $request->date_of_birth,
                'email' => $request->email,
                'passport' => $request->passport?? '',
                'address' => $request->address?? '',
                'emergency_contact_name' => $request->emergency_contact_name?? '',
                'emergency_contact_phone' => $request->emergency_contact_phone?? '',
                'blood_type' => $request->blood_type?? '',
                'chronic_conditions' => $request->chronic_conditions?? '',
                'current_medications' => $request->current_medications?? '',
                'previous_surgeries' => $request->previous_surgeries?? '',
                'family_medical_history' => $request->family_medical_history?? '',
                'preferred_doctor' => $request->preferred_doctor?? '',
                'insurance_provider' => $request->insurance_provider?? '',
                'insurance_policy_number' => $request->insurance_policy_number?? '',
                'billing_address' => $request->billing_address?? ''
            ]);

            return response()->json([
                'status'  => 'success',
                'data' => $patient,
                'message' => 'Patient registered successfully'
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $patient = Patient::findOrFail($id);
            $user = User::findOrFail($patient->user_id);

            $request->validate([
                'name'      => 'required|max:255',
                'age'       => 'required|numeric|integer',
                'gender'    => 'required',
                'contact'   => 'required|unique:users,phone_number,' . $user->id,
                'email'     => 'required|email|unique:users,email,' . $user->id,
                'date_of_birth' => 'required|date',
                'passport'  => 'nullable',
                'address'   => 'nullable',
                'emergency_contact_name'  => 'nullable',
                'emergency_contact_phone' => 'nullable',
                'blood_type'   => 'nullable',
                'chronic_conditions' => 'nullable',
                'current_medications' => 'nullable',
                'previous_surgeries' => 'nullable',
                'family_medical_history' => 'nullable',
                'preferred_doctor' => 'nullable',
                'insurance_provider' => 'nullable',
                'insurance_policy_number' => 'nullable',
                'billing_address' => 'nullable'
            ]);

            $patient->update($request->all());
            $user->update([
                'name'         => $request->name,
                'email'        => $request->email,
                'phone_number' => $request->contact,
                'address'      => $request->address
            ]);

            return response()->json([
                'status'  => 'success',
                'data' => $patient,
                'message' => 'Patient updated successfully'
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'fail',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }




    public function getById($id = 0)
    {
        try {

            $patient = Patient::with(['user'])->findOrfail($id);


            if(!$patient){
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Patient not found'
                ], 500);
            }

            return response()->json(
                [
                    'status'  => 'success',
                    'data'    => $patient,
                    'message' => ' Get patient by id successfully'
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



    /**
     * Remove the specified resource from storage.
     */
    public function delete($id = 0)
    {
        try {

            $patient = Patient::with(['user'])->findOrfail($id);

            if(!$patient){
                return response()->json([
                    'status'  => 'fail',
                    'message' => 'Patient not found'
                ], Response::HTTP_BAD_REQUEST);
            }
            $patient->delete();
            User::findOrfail($patient->user_id)->delete();


            return response()->json(
                [
                    'status'  => 'success',
                    'data'    => $patient,
                    'message' => ' User Deleted successfully'
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

    public function search(Request $request)
    {
        try {

            $byContact    = $request->contact??null;
            $byName       = $request->name??null;
            $byIdCard     = $request->id_card??null;
            $byPatientId  = $request->patient_id??null;

            $patient = Patient::with(['user']);

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


    private function __generatePatientID(){
        $lastPatient = Patient::latest()->first();

        if ($lastPatient) {
            $lastPatientId = preg_replace('/[^0-9]/', '', $lastPatient->patient_id);
            $newId = 'P' . str_pad((int)$lastPatientId + 1, 7, '0', STR_PAD_LEFT);
        } else {
            $newId = 'P0000001';
        }

        return $newId;
    }
}
