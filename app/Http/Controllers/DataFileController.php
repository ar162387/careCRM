<?php

namespace App\Http\Controllers;

use App\Models\DataFile;
use App\Models\Device;
use App\Models\Factory;
use App\Models\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use DataTables;

class DataFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $files = DataFile::all();
        $factories = Factory::all();
        $devices = Device::all();
        $inspections = Inspection::where('taken_up', false)->get();
        return view('admin.data.index', compact('files', 'factories', 'devices', 'inspections'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataFile $dataFile)
    {
        $factories = Factory::all();
        $devices = Device::all();
        $inspections = Inspection::all();
        return view('admin.data.edit', compact('dataFile', 'factories', 'devices', 'inspections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataFile $dataFile)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:2048',
            'site_id' => 'required|exists:sites,id',
            'device_serial' => 'required|string|exists:devices,serial_number', // Ensure device is registered
            'inspection_id' => 'required|exists:inspections,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $device = Device::where('serial_number', $request->input('device_serial'))->first();

        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('data_files', $fileName, 'public');

            $dataFile->file_name = $fileName;
            $dataFile->file_path = $filePath;
            $dataFile->component_id = $request->input('component_id');
            $dataFile->site_id = $request->input('site_id');
            $dataFile->device_id = $device->id;
            $dataFile->inspection_id = $request->input('inspection_id');

            $dataFile->save();

            return redirect(route('data.index'))->with('success', 'Data updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataFile $dataFile)
    {
        $filePath = $dataFile->file_path;

        // Check if the file exists and delete it
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        // Delete the database record
        $dataFile->delete();

        // Return a response indicating the file was deleted
        return response()->json(['message' => 'File deleted successfully.'], 200);
    }

    public function download(DataFile $dataFile)
    {
        $filePath = $dataFile->file_path;

        // Check if the file exists
        if (Storage::disk('public')->exists($filePath))
        {
            // Return the file as a download response
            return Storage::disk('public')->download($filePath, $dataFile->file_name);
        }
        else {
            // Return an error response if the file does not exist
            return response()->json(['message' => 'File not found.'], 404);
        }
    }

    public function upload(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:2048',
            'site_id' => 'required|exists:sites,id',
            'device_serial' => 'required|string|exists:devices,serial_number', // Ensure device is registered
            'inspection_id' => 'required|exists:inspections,id',
        ]);

        $device = Device::where('serial_number', $request->input('device_serial'))->first();
        if (!$device) {
            return response()->json(['message' => 'Device is not registered at system.'], 404);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('data_files', $fileName, 'public');

            // Store file metadata in the database
            DataFile::create([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'component_id' => $request->input('component_id'),
                'site_id' => $request->input('site_id'),
                'device_id' => $device->id,
                'inspection_id' => $request->input('inspection_id'),
            ]);

            return response()->json(['message' => 'File uploaded successfully'], 200);
        }

        return response()->json(['message' => 'Invalid file upload'], 400);
    }

    public function replace(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:data_files,id',
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $dataFile = DataFile::find($request->input('id'));
        $oldFile = $dataFile->file_path;

        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('data_files', $fileName, 'public');

            $dataFile->update([
                'file_name' => $fileName,
                'file_path' => $filePath,
            ]);

            // Check if the old file exists and delete it
            if (Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }

            return response()->json(['message' => 'File replaced successfully.', 'data' => $dataFile], 201);
        }
        else {
            return response()->json(['message' => 'Invalid file upload.'], 400);
        }
    }
}
