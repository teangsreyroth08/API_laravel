<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Prescription;
use Illuminate\Support\Facades\Http;

class PrintController extends Controller
{
    //====================Global variable====================
    private $JS_BASE_URL;
    private $JS_USERNAME;
    private $JS_PASSWORD;
    private $JS_TEMPLATE;

    public function __construct()
    {
        $this->JS_BASE_URL   = env('JS_BASE_URL', 'https://tongmeng.jsreportonline.net');
        $this->JS_USERNAME   = env('JS_USERNAME', 'tongmeng016@gmail.com');
        $this->JS_PASSWORD   = env('JS_PASSWORD', 'tongmeng016@gmail.com');
        $this->JS_TEMPLATE   = env('JS_TEMPLATE', '/Lifeless-prescription/main');
    }

    public function printPrescription($prescript_id = 0)
    {
        try {
            // Fetch prescription data
            $data = $this->getData($prescript_id);

            // Ensure data is valid before proceeding
            if (isset($data['error'])) {
                return response()->json(['error' => $data['error']], 500);
            }

            // Prepare request body for JSReport
            $body = [
                "template" => [
                    "name" => $this->JS_TEMPLATE,
                ],
                "data" => $data,
            ];

            // Send request to JSReport
            $response = Http::withOptions(['verify' => false])
                ->withBasicAuth($this->JS_USERNAME, $this->JS_PASSWORD)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($this->JS_BASE_URL . '/api/report', $body);

            // Check if the request was successful
            if ($response->failed()) {
                return response()->json(['error' => 'Failed to generate report'], 500);
            }

            // Save the generated PDF
            $filePath = storage_path('app/pdfs/' . date('YmdHis') . '_' . $prescript_id . '_prescription.pdf');
            file_put_contents($filePath, $response->body());

            return [
                'file_base64' => base64_encode($response->body()),
                'error' => '',
            ];
        } catch (\Exception $e) {
            // Handle the exception
            return [
                'file_base64' => '',
                'error' => $e->getMessage(),
            ];
        }
    }

    public static function getData($prescription_id = 0)
    {
        try {
            $prescription = Prescription::with(['doctor', 'patient', 'details.medicine'])
                ->findOrFail($prescription_id);

            return [
                'created_at' => \Carbon\Carbon::parse($prescription->date)->format('Y-m-d'),
                'diagnosis' => $prescription->diagnosis,
                'notes' => $prescription->notes,
                'age' =>$prescription->age ?? ' ',
                'height'=>$prescription->height ?? ' ',
                'weight'=>$prescription->weight ?? ' ',
                'patient' => [
                    'id' => $prescription->patient->patient_id ?? null,
                    'name' => $prescription->patient->name ?? 'Unknown',
                    'gender'=>$prescription->patient->gender ?? ' ',
                    'contact'=>$prescription->patient->contact ?? ' ',
                    'blood_group'=>$prescription->patient->blood_type ?? ' ',
                ],
                'doctor' => [
                    'id' => $prescription->doctor->id ?? null,
                    'name' => $prescription->doctor->user->name ?? 'Unknown',
                    'specialist' => $prescription->doctor->specialization->name ?? 'Unknown',
                    'contact'=>$prescription->doctor->contact ?? ' ',

                ],
                'medicines' => $prescription->details->map(fn($detail) => [
                    'name' => $detail->medicine->item_name ?? 'Unknown',
                    'dosage' => $detail->dosage ?? ' ',
                    'frequency' => $detail->frequency ?? ' ',
                    'duration' => $detail->duration ?? ' ',
                ]),

            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
}
