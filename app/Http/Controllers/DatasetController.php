<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Dataset;
use App\Models\ClimateData;
use App\Models\VegetationData;
use App\Models\VulnerabilityThreshold;
use App\Models\VulnerabilityAssessment;
use App\Models\Flora;
use App\Models\ObservationReport;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DatasetController extends Controller
{
    // Upload Climate
    public function showUploadClimate()
    {
        return view('researcher.upload_climate');
    }

    public function uploadClimate(Request $request)
    {
        $request->validate([
            'dataset_name' => 'required|string|max:150',
            'source_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'csv_file' => 'required|file|mimes:csv,txt|max:4096',
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->store('datasets/climate');

        // Create Dataset Metadata
        $dataset = Dataset::create([
            'uploaded_by' => Auth::user()->user_id,
            'dataset_name' => $request->dataset_name,
            'dataset_type' => 'Climate',
            'source_name' => $request->source_name,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'description' => $request->description,
            'upload_status' => 'Validated',
        ]);

        // Parse CSV
        $path = storage_path('app/private/' . $filePath);
        if (!file_exists($path)) {
            $path = storage_path('app/' . $filePath); // fallback depending on laravel version storage path config
        }

        $fileHandle = fopen($path, 'r');
        $headers = fgetcsv($fileHandle);
        
        // Sanitize headers
        $headers = array_map(function($h) {
            return trim(strtolower(str_replace(['"', "'", "\xef\xbb\xbf"], '', $h)));
        }, $headers);

        $rowCount = 0;
        while (($row = fgetcsv($fileHandle)) !== false) {
            if (count($headers) !== count($row)) {
                continue;
            }

            $data = array_combine($headers, $row);

            $regionName = isset($data['region_name']) ? trim($data['region_name']) : null;
            if (!$regionName) continue;

            $region = Region::where('region_name', $regionName)->first();
            if (!$region) continue;

            ClimateData::create([
                'dataset_id' => $dataset->dataset_id,
                'region_id' => $region->region_id,
                'record_date' => isset($data['record_date']) ? trim($data['record_date']) : now()->toDateString(),
                'temperature_celsius' => isset($data['temperature_celsius']) ? floatval($data['temperature_celsius']) : null,
                'rainfall_mm' => isset($data['rainfall_mm']) ? floatval($data['rainfall_mm']) : null,
                'humidity_percent' => isset($data['humidity_percent']) ? floatval($data['humidity_percent']) : null,
                'drought_index' => isset($data['drought_index']) ? floatval($data['drought_index']) : null,
                'flood_risk_level' => isset($data['flood_risk_level']) ? trim($data['flood_risk_level']) : null,
            ]);
            $rowCount++;
        }
        fclose($fileHandle);

        $user = Auth::user();
        $user->increment('upload_count');
        $user->last_upload_date = now()->toDateString();
        $user->save();

        return redirect()->route('researcher.dashboard')->with('success', "Climate dataset uploaded successfully. Ingested {$rowCount} records.");
    }

}
