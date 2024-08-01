<?php

namespace App\Http\Controllers;
use App\Models\SensorData;

use Illuminate\Http\Request;

class SensorDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $dataFileIds = SensorData::select('data_file_id')->distinct()->get();
        return view('sensor_data.index', compact('dataFileIds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('sensor_data.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'data_file_id' => 'required',
            'X' => 'required',
            'Y' => 'required',
            'Z' => 'required',
        ]);

        SensorData::create($validated);

        return redirect()->route('sensor_data.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return view('sensor_data.show', compact('sensorData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return view('sensor_data.edit', compact('sensorData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validated = $request->validate([
            'data_file_id' => 'required',
            'X' => 'required',
            'Y' => 'required',
            'Z' => 'required',
        ]);

        $sensorData->update($validated);

        return redirect()->route('sensor_data.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $sensorData->delete();

        return redirect()->route('sensor_data.index');
    }

    public function generatePlot(Request $request)
{
    $fileId = $request->input('file_id');
    $column = $request->input('column');

    $sensorData = SensorData::select($column)->where('data_file_id', $fileId)->get();

    return view('sensor_data.plot', compact('sensorData', 'column'));
}

public function generateTimeDomainPlot(Request $request)
    {
        $fileId = $request->input('file_id');
        $sensorData = SensorData::where('data_file_id', $fileId)->get();

        return view('sensor_data.timedomain', [
            'sensorData' => $sensorData,
            'column' => 'combined'
        ]);
    }

    public function generateFrequencyDomainPlot(Request $request)
    {
        $fileId = $request->input('file_id');
        $sensorData = SensorData::where('data_file_id', $fileId)->get();

        return view('sensor_data.freqdomain', [
            'sensorData' => $sensorData,
            'column' => 'combined'
        ]);
    }

}
