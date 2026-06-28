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
                'temperature_score' => $latestAssessment ? floatval($latestAssessment->temperature_score) : null,
                'rainfall_score' => $latestAssessment ? floatval($latestAssessment->rainfall_score) : null,
                'ndvi_score' => $latestAssessment ? floatval($latestAssessment->ndvi_score) : null,
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

    // Public Search Registry
    public function publicSearch(Request $request)
    {
        $query = $request->input('q');
        
        $regions = collect();
        $flora = collect();
        
        if ($query) {
            $regions = Region::with(['assessments' => function($q) {
                $q->latest();
            }])->where('region_name', 'like', "%{$query}%")
               ->orWhere('county', 'like', "%{$query}%")
               ->orWhere('ecosystem_type', 'like', "%{$query}%")
               ->get();
               
            $flora = Flora::where('scientific_name', 'like', "%{$query}%")
               ->orWhere('common_name', 'like', "%{$query}%")
               ->orWhere('species_type', 'like', "%{$query}%")
               ->orWhere('conservation_status', 'like', "%{$query}%")
               ->orWhere('habitat_type', 'like', "%{$query}%")
               ->get();
        }
        
        return view('public.search', compact('regions', 'flora', 'query'));
    }

    // Show submit observation page
    public function showSubmitObservation()
    {
        $registeredFlora = Flora::select('flora_id', 'scientific_name', 'common_name')->get();
        $user = auth()->user();
        $myObservations = ObservationReport::where('public_id', $user->user_id)->latest()->get();
        return view('public.submit_observation', compact('registeredFlora', 'myObservations'));
    }

    // Delete observation report
    public function deleteObservation($observationId)
    {
        $observation = ObservationReport::findOrFail($observationId);
        
        // Ensure the logged-in user owns the report
        if ($observation->public_id !== auth()->user()->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete physical files if they exist
        if ($observation->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($observation->image_path);
        }
        if ($observation->csv_path) {
            \Illuminate\Support\Facades\Storage::delete($observation->csv_path);
        }

        $observation->delete();

        return redirect()->back()->with('success', 'Observation report has been successfully deleted.');
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

    // Edit region details view
    public function editRegion($regionId)
    {
        $region = Region::findOrFail($regionId);
        return view('researcher.edit_region', compact('region'));
    }

    // Process region updates
    public function updateRegion(Request $request, $regionId)
    {
        $region = Region::findOrFail($regionId);

        $request->validate([
            'region_name' => 'required|string|max:150',
            'county' => 'required|string|max:150',
            'ecosystem_type' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
        ]);

        $region->update($request->only([
            'region_name',
            'county',
            'ecosystem_type',
            'latitude',
            'longitude',
            'description',
        ]));

        return redirect()->route('researcher.dashboard')->with('success', "Region '{$region->region_name}' updated successfully.");
    }

    // Compare regions side-by-side
    public function showCompare(Request $request)
    {
        $regions = Region::all();
        
        $regionAId = $request->query('region_a');
        $regionBId = $request->query('region_b');

        $regionA = null;
        $regionB = null;

        if ($regionAId) {
            $regionA = Region::with(['assessments' => function($q) {
                $q->latest();
            }])->find($regionAId);
        }

        if ($regionBId) {
            $regionB = Region::with(['assessments' => function($q) {
                $q->latest();
            }])->find($regionBId);
        }

        return view('researcher.compare', compact('regions', 'regionA', 'regionB', 'regionAId', 'regionBId'));
    }

    // Export Vulnerability Assessments as CSV
    public function exportAssessments()
    {
        $assessments = VulnerabilityAssessment::with(['region', 'generator'])->latest()->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=ecosystem_vulnerability_assessments.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Assessment ID', 'Region Name', 'County', 'Ecosystem Type', 'Temperature Score', 'Rainfall Score', 'NDVI Score', 'Overall Score', 'Vulnerability Level', 'Interpretation', 'Generated By', 'Created At'];

        $callback = function() use($assessments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($assessments as $ast) {
                fputcsv($file, [
                    $ast->assessment_id,
                    $ast->region ? $ast->region->region_name : 'N/A',
                    $ast->region ? $ast->region->county : 'N/A',
                    $ast->region ? $ast->region->ecosystem_type : 'N/A',
                    $ast->temperature_score,
                    $ast->rainfall_score,
                    $ast->ndvi_score,
                    $ast->overall_score,
                    $ast->vulnerability_level,
                    $ast->interpretation,
                    $ast->generator ? $ast->generator->full_name : 'N/A',
                    $ast->created_at ? $ast->created_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Export Observations Queue as CSV
    public function exportObservations(Request $request)
    {
        $query = ObservationReport::with(['observer', 'reviewer', 'flora']);

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function($q) use ($search) {
                $q->where('flora_name', 'like', $search)
                  ->orWhere('location', 'like', $search)
                  ->orWhereHas('observer', function($o) use ($search) {
                      $o->where('full_name', 'like', $search);
                  });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('level') && $request->level !== 'all') {
            $level = $request->level;
            $query->whereHas('flora', function($q) use ($level) {
                $q->where('vulnerability_level', $level);
            });
        }

        $observations = $query->latest()->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=public_flora_observations.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Observation ID', 'Flora Name', 'Location Details', 'Description', 'Date Observed', 'Submission Date', 'Report Status', 'Observer Name', 'Observer Email', 'Reviewer Name', 'Review Comment'];

        $callback = function() use($observations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($observations as $obs) {
                fputcsv($file, [
                    $obs->observation_id,
                    $obs->flora_name,
                    $obs->location,
                    $obs->description,
                    $obs->date_observed ? $obs->date_observed->format('Y-m-d') : 'N/A',
                    $obs->submission_date ? $obs->submission_date->format('Y-m-d H:i:s') : 'N/A',
                    $obs->status,
                    $obs->observer ? $obs->observer->full_name : 'N/A',
                    $obs->observer ? $obs->observer->email : 'N/A',
                    $obs->reviewer ? $obs->reviewer->full_name : 'N/A',
                    $obs->review_comment,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Reports Manager index view
    public function showReports()
    {
        $reports = Report::with('creator')->latest()->get();
        return view('researcher.reports', compact('reports'));
    }

    // View specific report in JSON for modal display
    public function viewReport($reportId)
    {
        $report = Report::with('creator')->findOrFail($reportId);
        return response()->json([
            'report' => $report,
            'formatted_date' => $report->created_at ? $report->created_at->format('Y-m-d H:i:s') : 'N/A'
        ]);
    }

    // Generate analytical report
    public function generateReport(Request $request)
    {
        $request->validate([
            'report_title' => 'required|string|max:150',
            'report_type' => 'required|in:vulnerability_summary,observations_summary',
        ]);

        $content = '';

        if ($request->report_type === 'vulnerability_summary') {
            $assessments = VulnerabilityAssessment::with('region')->latest()->get();
            $totalAssessments = $assessments->count();
            $highCount = $assessments->where('vulnerability_level', 'High')->count();
            $modCount = $assessments->where('vulnerability_level', 'Moderate')->count();
            $lowCount = $assessments->where('vulnerability_level', 'Low')->count();

            $content = "<h2>Ecosystem Vulnerability Analysis Summary</h2>";
            $content .= "<p>This analytical report compiles the latest regional vulnerability evaluations executed by the research department.</p>";
            $content .= "<h3>Executive Metrics</h3>";
            $content .= "<ul>";
            $content .= "<li><strong>Total Assessments Run:</strong> {$totalAssessments}</li>";
            $content .= "<li><strong>High Vulnerability Ecosystems:</strong> {$highCount}</li>";
            $content .= "<li><strong>Moderate Vulnerability Ecosystems:</strong> {$modCount}</li>";
            $content .= "<li><strong>Low Vulnerability Ecosystems:</strong> {$lowCount}</li>";
            $content .= "</ul>";

            $content .= "<h3>Detailed Regional Vulnerability Breakdown</h3>";
            $content .= "<table style='width:100%; border-collapse:collapse; margin-top:15px; font-size:13px;'>";
            $content .= "<thead><tr style='background:#f4f6f4; text-align:left; border-bottom:2px solid #ddd;'>";
            $content .= "<th style='padding:8px;'>Region Name</th><th style='padding:8px;'>County</th><th style='padding:8px;'>Ecosystem</th><th style='padding:8px;'>Overall Score</th><th style='padding:8px;'>Vulnerability Level</th>";
            $content .= "</tr></thead><tbody>";

            if ($assessments->isEmpty()) {
                $content .= "<tr><td colspan='5' style='padding:8px; text-align:center; font-style:italic;'>No vulnerability assessment records found.</td></tr>";
            } else {
                foreach ($assessments as $ast) {
                    $rName = $ast->region ? $ast->region->region_name : 'N/A';
                    $rCounty = $ast->region ? $ast->region->county : 'N/A';
                    $rEco = $ast->region ? $ast->region->ecosystem_type : 'N/A';
                    $content .= "<tr style='border-bottom:1px solid #eee;'>";
                    $content .= "<td style='padding:8px;'><strong>{$rName}</strong></td>";
                    $content .= "<td style='padding:8px;'>{$rCounty}</td>";
                    $content .= "<td style='padding:8px;'>{$rEco}</td>";
                    $content .= "<td style='padding:8px;'>{$ast->overall_score}%</td>";
                    $content .= "<td style='padding:8px; font-weight:bold;'>{$ast->vulnerability_level}</td>";
                    $content .= "</tr>";
                }
            }
            $content .= "</tbody></table>";

        } else {
            $totalObs = ObservationReport::count();
            $pendingCount = ObservationReport::where('status', 'Pending')->count();
            $appCount = ObservationReport::where('status', 'Approved')->count();
            $rejCount = ObservationReport::where('status', 'Rejected')->count();

            $content = "<h2>Public Flora Observations Queue Summary</h2>";
            $content .= "<p>This report details public flora submissions and the current researcher audit workflow statistics.</p>";
            $content .= "<h3>Executive Metrics</h3>";
            $content .= "<ul>";
            $content .= "<li><strong>Total Observations Submitted:</strong> {$totalObs}</li>";
            $content .= "<li><strong>Pending Review:</strong> {$pendingCount}</li>";
            $content .= "<li><strong>Approved Records:</strong> {$appCount}</li>";
            $content .= "<li><strong>Rejected Submissions:</strong> {$rejCount}</li>";
            $content .= "</ul>";

            $content .= "<h3>Observation Records Log</h3>";
            $content .= "<table style='width:100%; border-collapse:collapse; margin-top:15px; font-size:13px;'>";
            $content .= "<thead><tr style='background:#f4f6f4; text-align:left; border-bottom:2px solid #ddd;'>";
            $content .= "<th style='padding:8px;'>Flora Name</th><th style='padding:8px;'>Location</th><th style='padding:8px;'>Submission Date</th><th style='padding:8px;'>Status</th><th style='padding:8px;'>Reviewer</th>";
            $content .= "</tr></thead><tbody>";

            $observations = ObservationReport::with(['reviewer'])->latest()->take(30)->get();

            if ($observations->isEmpty()) {
                $content .= "<tr><td colspan='5' style='padding:8px; text-align:center; font-style:italic;'>No observation records found.</td></tr>";
            } else {
                foreach ($observations as $obs) {
                    $revName = $obs->reviewer ? $obs->reviewer->full_name : 'Pending';
                    $subDate = $obs->submission_date ? $obs->submission_date->format('Y-m-d') : 'N/A';
                    $content .= "<tr style='border-bottom:1px solid #eee;'>";
                    $content .= "<td style='padding:8px;'><strong>{$obs->flora_name}</strong></td>";
                    $content .= "<td style='padding:8px;'>{$obs->location}</td>";
                    $content .= "<td style='padding:8px;'>{$subDate}</td>";
                    $content .= "<td style='padding:8px; font-weight:bold;'>{$obs->status}</td>";
                    $content .= "<td style='padding:8px;'>{$revName}</td>";
                    $content .= "</tr>";
                }
            }
            $content .= "</tbody></table>";
        }

        Report::create([
            'generated_by' => Auth::user()->user_id,
            'report_title' => $request->report_title,
            'report_type' => $request->report_type === 'vulnerability_summary' ? 'Ecosystem Vulnerability Summary' : 'Public Observations Summary',
            'content' => $content
        ]);

        return redirect()->route('researcher.reports')->with('success', "Analytical report '{$request->report_title}' generated successfully.");
    }
}
