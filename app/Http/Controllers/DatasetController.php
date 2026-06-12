<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\Dataset;
use App\Models\ClimateData;
use App\Models\VegetationData;
use App\Models\VulnerabilityThreshold;
use App\Models\VulnerabilityAssessment;
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

        return redirect()->route('researcher.dashboard')->with('success', "Vegetation dataset uploaded successfully. Ingested {$rowCount} records.");
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
        $tempScore = min(100, max(0, abs($climateAvg->avg_temp - 20) * 10)); 

        // 2. Rainfall score: Inverse of rainfall (lower rainfall increases vulnerability, max ref 200mm)
        $rainScore = min(100, max(0, (1 - ($climateAvg->avg_rain / 200)) * 100));

        // 3. NDVI score: Inverse of NDVI (lower NDVI means poorer canopy density, max NDVI ref 0.8)
        $ndviScore = min(100, max(0, (1 - ($vegAvg->avg_ndvi / 0.8)) * 100));

        // Overall Vulnerability index
        $overallScore = round(($tempScore + $rainScore + $ndviScore) / 3, 2);

        // Map scores against Admin thresholds
        $threshold = VulnerabilityThreshold::first();
        if (!$threshold) {
            // Default Fallback
            $threshold = VulnerabilityThreshold::create([
                'threshold_name' => 'Fallback Ranges',
                'low_min' => 0, 'low_max' => 30,
                'moderate_min' => 31, 'moderate_max' => 60,
                'high_min' => 61, 'high_max' => 100
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
            'temperature_score' => $tempScore,
            'rainfall_score' => $rainScore,
            'ndvi_score' => $ndviScore,
            'overall_score' => $overallScore,
            'vulnerability_level' => $level,
            'interpretation' => $interpretation,
        ]);

        return redirect()->route('researcher.dashboard')->with('success', "Vulnerability assessment completed. Region vulnerability rating is {$level} (Score: {$overallScore}%).");
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
}
