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
        $path = Storage::path($filePath);
        if (!file_exists($path)) {
            $path = storage_path('app/private/' . $filePath);
            if (!file_exists($path)) {
                $path = storage_path('app/' . $filePath);
            }
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
        $path = Storage::path($filePath);
        if (!file_exists($path)) {
            $path = storage_path('app/private/' . $filePath);
            if (!file_exists($path)) {
                $path = storage_path('app/' . $filePath);
            }
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
        $path = Storage::path($filePath);
        if (!file_exists($path)) {
            $path = storage_path('app/private/' . $filePath);
            if (!file_exists($path)) {
                $path = storage_path('app/' . $filePath);
            }
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

    // Flora Registry Actions
    public function showManageFlora()
    {
        $regions = Region::all();
        return view('researcher.manage_flora', compact('regions'));
    }

    public function createFlora(Request $request)
    {
        $request->validate([
            'scientific_name' => 'required|string|max:150|unique:flora,scientific_name',
            'common_name' => 'nullable|string|max:150',
            'region_id' => 'required|exists:regions,region_id',
            'species_type' => 'nullable|string|max:100',
            'conservation_status' => 'nullable|string|max:50',
            'habitat_type' => 'nullable|string|max:100',
            'vulnerability_level' => 'required|in:Low,Moderate,High',
        ]);

        Flora::create([
            'scientific_name' => $request->scientific_name,
            'common_name' => $request->common_name,
            'region_id' => $request->region_id,
            'species_type' => $request->species_type,
            'conservation_status' => $request->conservation_status,
            'habitat_type' => $request->habitat_type,
            'vulnerability_level' => $request->vulnerability_level,
        ]);

        return redirect()->route('researcher.dashboard')->with('success', "Flora species '{$request->scientific_name}' has been successfully added to the registry.");
    }

    // Vulnerability Analysis Console
    public function showAnalysis()
    {
        $regions = Region::all();
        $climateDatasets = Dataset::where('dataset_type', 'Climate')->where('upload_status', 'Validated')->get();
        $vegDatasets = Dataset::where('dataset_type', 'Vegetation')->where('upload_status', 'Validated')->get();

        return view('researcher.analysis', compact('regions', 'climateDatasets', 'vegDatasets'));
    }

    public function runAnalysis(Request $request)
    {
        $request->validate([
            'region_id' => 'required|exists:regions,region_id',
            'climate_dataset_id' => 'required|exists:datasets,dataset_id',
            'vegetation_dataset_id' => 'required|exists:datasets,dataset_id',
        ]);

        $regionId = $request->region_id;
        $climateId = $request->climate_dataset_id;
        $vegId = $request->vegetation_dataset_id;

        // Retrieve average calculations
        $climateAvg = ClimateData::where('dataset_id', $climateId)
            ->where('region_id', $regionId)
            ->selectRaw('AVG(temperature_celsius) as avg_temp, AVG(rainfall_mm) as avg_rain')
            ->first();

        $vegAvg = VegetationData::where('dataset_id', $vegId)
            ->where('region_id', $regionId)
            ->selectRaw('AVG(ndvi_value) as avg_ndvi')
            ->first();

        if (is_null($climateAvg->avg_temp) || is_null($vegAvg->avg_ndvi)) {
            return back()->withErrors(['error' => 'The selected datasets do not contain matching records for the selected region. Please upload datasets matching this region first.']);
        }

        // Calculation algorithms
        // 1. Temperature score: Deviation from ideal 20°C (higher deviations increase vulnerability)
        $temperatureScore = min(100, max(0, abs($climateAvg->avg_temp - 20) * 10));

        // 2. Rainfall score: Inverse of rainfall (lower rainfall increases vulnerability, max ref 200mm)
        $rainScore = min(100, max(0, (1 - ($climateAvg->avg_rain / 200)) * 100));

        // 3. NDVI score: Inverse of NDVI (lower NDVI means poorer canopy density, max NDVI ref 0.8)
        $ndviScore = min(100, max(0, (1 - ($vegAvg->avg_ndvi / 0.8)) * 100));

        // Overall Vulnerability index
        $overallScore = round(($temperatureScore + $rainScore + $ndviScore) / 3, 2);

        // Map scores against Admin thresholds
        $threshold = VulnerabilityThreshold::first();
        if (!$threshold) {
            // Default Fallback
            $threshold = VulnerabilityThreshold::create([
                'threshold_name' => 'Fallback Ranges',
                'low_min' => 0,
                'low_max' => 30,
                'moderate_min' => 31,
                'moderate_max' => 60,
                'high_min' => 61,
                'high_max' => 100
            ]);
        }

        $level = 'Low';
        if ($overallScore >= $threshold->high_min) {
            $level = 'High';
        } elseif ($overallScore >= $threshold->moderate_min) {
            $level = 'Moderate';
        }

        $interpretation = "Vulnerability index computed as {$overallScore}% based on average Temperature (" . round($climateAvg->avg_temp, 1) . "°C), average Monthly Rainfall (" . round($climateAvg->avg_rain, 1) . "mm), and average NDVI vegetation cover index (" . round($vegAvg->avg_ndvi, 3) . ").";

        // Save Assessment
        VulnerabilityAssessment::create([
            'region_id' => $regionId,
            'climate_dataset_id' => $climateId,
            'vegetation_dataset_id' => $vegId,
            'threshold_id' => $threshold->threshold_id,
            'generated_by' => Auth::user()->user_id,
            'temperature_score' => $temperatureScore,
            'rainfall_score' => $rainScore,
            'ndvi_score' => $ndviScore,
            'overall_score' => $overallScore,
            'vulnerability_level' => $level,
            'interpretation' => $interpretation,
        ]);

        return redirect()->route('researcher.dashboard')->with('success', "Vulnerability assessment completed. Region vulnerability rating is {$level} (Score: {$overallScore}%).");
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

    // Dynamic Map Data JSON API
    public function getVulnerabilityData()
    {
        $regions = Region::all();
        $data = [];

        foreach ($regions as $region) {
            $latestAssessment = VulnerabilityAssessment::where('region_id', $region->region_id)
                ->latest()
                ->first();

            $data[] = [
                'region_id' => $region->region_id,
                'region_name' => $region->region_name,
                'latitude' => floatval($region->latitude),
                'longitude' => floatval($region->longitude),
                'county' => $region->county,
                'ecosystem_type' => $region->ecosystem_type,
                'vulnerability_level' => $latestAssessment ? $latestAssessment->vulnerability_level : 'Not Assessed',
                'overall_score' => $latestAssessment ? floatval($latestAssessment->overall_score) : null,
                'interpretation' => $latestAssessment ? $latestAssessment->interpretation : 'This region has not been assessed yet. Datasets must be uploaded and calculated by researchers.',
            ];
        }

        return response()->json($data);
    }

    // Fetch detailed region profile for map popup modal click
    public function getRegionDetails($regionId)
    {
        $region = Region::findOrFail($regionId);

        // Get latest climate records
        $climate = ClimateData::where('region_id', $regionId)
            ->latest('record_date')
            ->take(5)
            ->get();

        // Get latest vegetation records
        $vegetation = VegetationData::where('region_id', $regionId)
            ->latest('record_date')
            ->take(5)
            ->get();

        // Get flora species in this region
        $flora = Flora::where('region_id', $regionId)->get();

        // Get assessments history
        $assessments = VulnerabilityAssessment::where('region_id', $regionId)
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'region' => $region,
            'climate' => $climate,
            'vegetation' => $vegetation,
            'flora' => $flora,
            'assessments' => $assessments,
        ]);
    }

    // Submit a public observation report with CSV and image
    public function submitObservation(Request $request)
    {
        $request->validate([
            'flora_id' => 'nullable|exists:flora,flora_id',
            'flora_name_custom' => 'required_without:flora_id|nullable|string|max:255',
            'location' => 'required|string|max:255',
            'date_observed' => 'required|date|before_or_equal:today',
            'description' => 'required|string',
            'image_file' => 'required|file|image|max:4096',
            'csv_file' => 'required|file|mimes:csv,txt|max:4096',
            'temperature_celsius' => 'nullable|numeric|between:-10,60',
            'rainfall_mm' => 'nullable|numeric|between:0,5000',
            'humidity_percent' => 'nullable|numeric|between:0,100',
            'drought_index' => 'nullable|numeric|between:0,10',
            'ndvi_value' => 'nullable|numeric|between:-1,1',
            'vegetation_cover_percent' => 'nullable|numeric|between:0,100',
            'vegetation_condition' => 'nullable|string|max:50',
        ]);

        $imageFile = $request->file('image_file');
        $imagePath = $imageFile->store('observations/images', 'public');

        $csvFile = $request->file('csv_file');
        $csvPath = $csvFile->store('observations/csvs');

        $floraId = $request->flora_id;
        if ($floraId) {
            $flora = Flora::find($floraId);
            $floraName = $flora->scientific_name;
        } else {
            $floraName = $request->flora_name_custom;
        }

        ObservationReport::create([
            'public_id' => Auth::user()->user_id,
            'flora_id' => $floraId,
            'flora_name' => $floraName,
            'location' => $request->location,
            'description' => $request->description,
            'image_path' => $imagePath,
            'csv_path' => $csvPath,
            'temperature_celsius' => $request->temperature_celsius,
            'rainfall_mm' => $request->rainfall_mm,
            'humidity_percent' => $request->humidity_percent,
            'drought_index' => $request->drought_index,
            'ndvi_value' => $request->ndvi_value,
            'vegetation_cover_percent' => $request->vegetation_cover_percent,
            'vegetation_condition' => $request->vegetation_condition,
            'date_observed' => $request->date_observed,
            'submission_date' => now(),
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Observation report submitted successfully for review.');
    }

    // Get dynamic observation details, including parsed CSV preview
    public function getObservationDetails($observationId)
    {
        $observation = ObservationReport::with(['observer', 'reviewer', 'flora'])->findOrFail($observationId);

        $csvData = [];
        if ($observation->csv_path && Storage::exists($observation->csv_path)) {
            $csvContent = Storage::get($observation->csv_path);
            $fileHandle = fopen('php://temp', 'r+');
            if ($fileHandle !== false) {
                fwrite($fileHandle, $csvContent);
                rewind($fileHandle);
                $headers = fgetcsv($fileHandle);
                $rows = [];
                $count = 0;
                while (($row = fgetcsv($fileHandle)) !== false && $count < 50) {
                    $rows[] = $row;
                    $count++;
                }
                fclose($fileHandle);
                $csvData = [
                    'headers' => $headers,
                    'rows' => $rows,
                ];
            }
        }

        return response()->json([
            'observation' => [
                'observation_id' => $observation->observation_id,
                'flora_name' => $observation->flora_name,
                'location' => $observation->location,
                'description' => $observation->description,
                'image_url' => $observation->image_path ? asset('storage/' . $observation->image_path) : null,
                'temperature_celsius' => $observation->temperature_celsius,
                'rainfall_mm' => $observation->rainfall_mm,
                'humidity_percent' => $observation->humidity_percent,
                'drought_index' => $observation->drought_index,
                'ndvi_value' => $observation->ndvi_value,
                'vegetation_cover_percent' => $observation->vegetation_cover_percent,
                'vegetation_condition' => $observation->vegetation_condition,
                'date_observed' => $observation->date_observed ? $observation->date_observed->format('Y-m-d') : null,
                'submission_date' => $observation->submission_date ? $observation->submission_date->format('Y-m-d H:i:s') : null,
                'status' => $observation->status,
                'review_comment' => $observation->review_comment,
                'observer' => $observation->observer ? [
                    'full_name' => $observation->observer->full_name,
                    'email' => $observation->observer->email,
                    'phone_number' => $observation->observer->phone_number,
                ] : null,
                'reviewer' => $observation->reviewer ? [
                    'full_name' => $observation->reviewer->full_name,
                ] : null,
            ],
            'csv_data' => $csvData,
        ]);
    }

    // Review an observation (approve or reject)
    public function reviewObservation(Request $request, $observationId)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'review_comment' => 'nullable|string|max:1000',
        ]);

        $observation = ObservationReport::findOrFail($observationId);
        $observation->update([
            'status' => $request->status,
            'review_comment' => $request->review_comment,
            'researcher_id' => Auth::user()->user_id,
        ]);

        return redirect()->back()->with('success', "Observation report has been successfully reviewed and marked as {$request->status}.");
    }
}
