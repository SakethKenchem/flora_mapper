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
        $headers = array_map(function ($h) {
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

    // Upload Vegetation
    public function showUploadVegetation()
    {
        return view('researcher.upload_vegetation');
    }

    public function uploadVegetation(Request $request)
    {
        $request->validate([
            'dataset_name' => 'required|string|max:150',
            'source_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'csv_file' => 'required|file|mimes:csv,txt|max:4096',
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->store('datasets/vegetation');

        // Create Dataset Metadata
        $dataset = Dataset::create([
            'uploaded_by' => Auth::user()->user_id,
            'dataset_name' => $request->dataset_name,
            'dataset_type' => 'Vegetation',
            'source_name' => $request->source_name,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'description' => $request->description,
            'upload_status' => 'Validated',
        ]);

        // Parse CSV
        $path = storage_path('app/private/' . $filePath);
        if (!file_exists($path)) {
            $path = storage_path('app/' . $filePath);
        }

        $fileHandle = fopen($path, 'r');
        $headers = fgetcsv($fileHandle);

        $headers = array_map(function ($h) {
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

            VegetationData::create([
                'dataset_id' => $dataset->dataset_id,
                'region_id' => $region->region_id,
                'record_date' => isset($data['record_date']) ? trim($data['record_date']) : now()->toDateString(),
                'ndvi_value' => isset($data['ndvi_value']) ? floatval($data['ndvi_value']) : 0.000,
                'vegetation_cover_percent' => isset($data['vegetation_cover_percent']) ? floatval($data['vegetation_cover_percent']) : null,
                'vegetation_condition' => isset($data['vegetation_condition']) ? trim($data['vegetation_condition']) : null,
                'data_source' => isset($data['data_source']) ? trim($data['data_source']) : $request->source_name,
            ]);
            $rowCount++;
        }
        fclose($fileHandle);

        $user = Auth::user();
        $user->increment('upload_count');
        $user->last_upload_date = now()->toDateString();
        $user->save();

        return redirect()->route('researcher.dashboard')->with('success', "Vegetation dataset uploaded successfully. Ingested {$rowCount} records.");
    }

    // Upload Flora
    public function showUploadFlora()
    {
        return view('researcher.upload_flora');
    }

    public function uploadFlora(Request $request)
    {
        $request->validate([
            'dataset_name' => 'required|string|max:150',
            'source_name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'csv_file' => 'required|file|mimes:csv,txt|max:4096',
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->store('datasets/flora');

        // Create Dataset Metadata
        $dataset = Dataset::create([
            'uploaded_by' => Auth::user()->user_id,
            'dataset_name' => $request->dataset_name,
            'dataset_type' => 'Flora',
            'source_name' => $request->source_name,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'description' => $request->description,
            'upload_status' => 'Validated',
        ]);

        // Parse CSV
        $path = storage_path('app/private/' . $filePath);
        if (!file_exists($path)) {
            $path = storage_path('app/' . $filePath);
        }

        $fileHandle = fopen($path, 'r');
        $headers = fgetcsv($fileHandle);

        $headers = array_map(function ($h) {
            return trim(strtolower(str_replace(['"', "'", "\xef\xbb\xbf"], '', $h)));
        }, $headers);

        $rowCount = 0;
        while (($row = fgetcsv($fileHandle)) !== false) {
            if (count($headers) !== count($row)) {
                continue;
            }

            $data = array_combine($headers, $row);

            $scientificName = isset($data['scientific_name']) ? trim($data['scientific_name']) : null;
            if (!$scientificName) {
                continue;
            }

            $regionName = isset($data['region_name']) ? trim($data['region_name']) : null;
            $region = null;
            if ($regionName) {
                $region = Region::where('region_name', $regionName)->first();
            }

            Flora::create([
                'dataset_id' => $dataset->dataset_id,
                'region_id' => $region ? $region->region_id : null,
                'scientific_name' => $scientificName,
                'common_name' => isset($data['common_name']) ? trim($data['common_name']) : null,
                'species_type' => isset($data['species_type']) ? trim($data['species_type']) : null,
                'conservation_status' => isset($data['conservation_status']) ? trim($data['conservation_status']) : null,
                'habitat_type' => isset($data['habitat_type']) ? trim($data['habitat_type']) : null,
                'vulnerability_level' => isset($data['vulnerability_level']) ? trim($data['vulnerability_level']) : null,
            ]);
            $rowCount++;
        }
        fclose($fileHandle);

        $user = Auth::user();
        $user->increment('upload_count');
        $user->last_upload_date = now()->toDateString();
        $user->save();

        return redirect()->route('researcher.dashboard')->with('success', "Flora dataset uploaded successfully. Ingested {$rowCount} records.");
    }

    // Delete all uploaded datasets by the researcher
    public function deleteAllUploads()
    {
        $user = Auth::user();
        $datasets = Dataset::where('uploaded_by', $user->user_id)->get();

        foreach ($datasets as $dataset) {
            // Delete physical files
            if ($dataset->file_path && \Illuminate\Support\Facades\Storage::exists($dataset->file_path)) {
                \Illuminate\Support\Facades\Storage::delete($dataset->file_path);
            }
            $dataset->delete();
        }

        // Reset researcher stats
        $user->upload_count = 0;
        $user->last_upload_date = null;
        $user->save();

        return redirect()->back()->with('success', 'All uploaded datasets and associated records have been successfully deleted.');
    }
}
