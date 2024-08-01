<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        / $devices = Device::all(); // Fetch all devices (adjust as per your application logic)

        return view('devices.index', compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('devices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'serial_number' => 'required|string|unique:devices,serial_number',
            'description' => 'nullable|string',
        ]);

        Device::create($request->all());

        return redirect()->route('devices.index')
            ->with('success', 'Device created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        return view('devices.show', compact('device'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        return view('devices.edit', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        $request->validate([
            'serial_number' => 'required|string|unique:devices,serial_number,' . $device->id,
            'description' => 'nullable|string',
        ]);

        $device->update($request->all());

        return redirect()->route('devices.index')
            ->with('success', 'Device updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()->route('devices.index')
            ->with('success', 'Device deleted successfully.');
    }
}
