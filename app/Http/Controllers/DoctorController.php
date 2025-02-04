<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// class DoctorController extends Controller
// {
//     //
// }

namespace App\Http\Controllers;
use App\Models\User;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

// class DoctorController extends Controller
// {
//     // List all doctors
//     public function index()
//     {
//         $doctors = Doctor::with(['user', 'specialization'])->get();
//         return response()->json($doctors, 200);
//     }

//     // Store new doctor
//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'user_id' => 'required|exists:users,id|unique:doctors,user_id',
//             'specialization_id' => 'required|exists:specializations,id',
//             'contact' => 'required|string|max:255',
//             'availability' => 'boolean'
//         ]);

//         try {
//             $doctor = Doctor::create($validated);
//             return response()->json(['message' => 'Doctor created successfully.', 'data' => $doctor], 201);
//         } catch (QueryException $e) {
//             return response()->json(['status' => 'fail', 'message' => 'Database error: ' . $e->getMessage()], 500);
//         }
//     }

//     // Get doctor by ID
//     public function show($id)
//     {
//         $doctor = Doctor::with(['user', 'specialization'])->find($id);
//         if (!$doctor) {
//             return response()->json(['status' => 'fail', 'message' => 'Doctor not found.'], 404);
//         }
//         return response()->json(['status' => 'success', 'data' => $doctor], 200);
//     }

//     // Update doctor details
//     public function update(Request $request, $id)
//     {
//         $validated = $request->validate([
//             'specialization_id' => 'exists:specializations,id',
//             'contact' => 'string|max:255',
//             'availability' => 'boolean'
//         ]);

//         try {
//             $doctor = Doctor::findOrFail($id);
//             $doctor->update($validated);
//             return response()->json(['message' => 'Doctor updated successfully.', 'data' => $doctor], 200);
//         } catch (QueryException $e) {
//             return response()->json(['status' => 'fail', 'message' => 'Database error: ' . $e->getMessage()], 500);
//         }
//     }

//     // Delete doctor
//     public function destroy($id)
//     {
//         try {
//             $doctor = Doctor::findOrFail($id);
//             $doctor->delete();
//             return response()->json(['message' => 'Doctor deleted successfully.'], 200);
//         } catch (\Exception $e) {
//             return response()->json(['status' => 'fail', 'message' => 'Something went wrong: ' . $e->getMessage()], 500);
//         }
//     }

//     // Search for doctors
//     public function search(Request $request)
//     {
//         $query = $request->input('name');

//         $doctors = Doctor::whereHas('user', function ($q) use ($query) {
//             $q->where('name', 'LIKE', "%$query%");
//         })->get();

//         return response()->json(['status' => 'success', 'data' => $doctors], 200);
//     }
// }

class DoctorController extends Controller
{
    public function show($id)
    {
        $doctor = Doctor::with('user', 'specialization')->find($id);

        if (!$doctor) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Doctor not found.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $doctor
        ], 200);
    }
    public function index()
    {
        $doctors = Doctor::with('user', 'specialization')->get();

        return response()->json([
            'status' => 'success',
            'data' => $doctors
        ], 200);
    }
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6', 
            'specialization_id' => 'required|exists:specializations,id',
            'contact' => 'required|string|max:15',
            'availability' => 'required|boolean',
        ]);

        // Create the user (linked to doctor)
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Ensure password is hashed
            'role_id' => 2, // Assuming 2 is the Doctor role
            'is_active' => 1, 
        ]);

        // Create the doctor record
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'specialization_id' => $validated['specialization_id'],
            'contact' => $validated['contact'],
            'availability' => $validated['availability'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Doctor created successfully.',
            'doctor' => $doctor,
            'user' => $user, // Return user details as well
        ], 201);
    }
}