<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Factory; // Add this line
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sites = Site::with('factory')->get();
        return view('admin.sites.index', compact('sites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $factories = Factory::all(); // Add this line
        return view('admin.sites.create', compact('factories')); // Add this line
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'factory_id' => 'required|exists:factories,id',
        ]);

        Site::create([
            'title' => $request->input('title'),
            'factory_id' => $request->input('factory_id'),
        ]);

        return redirect()->route('sites.index')->with('success', 'Site created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site)
    {
        $factories = Factory::all(); // Add this line
        return view('admin.sites.edit', compact('site', 'factories')); // Add this line
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'factory_id' => 'required|exists:factories,id',
        ]);

        $site->update([
            'title' => $request->input('title'),
            'factory_id' => $request->input('factory_id'),
        ]);

        return redirect()->route('sites.index')->with('success', 'Site updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {
        $site->delete();
        return redirect()->route('sites.index')->with('success', 'Site deleted successfully.');
    }

    public function fetch(Request $request)
    {
        if($request->input('id'))
        {
            $data = Site::where('id', $request->input('id'))
                ->with(['components'])
                ->first();

            if($data) return response()->json($data, 200);
            else return response()->json(['message' => 'Site is not registered in the system.'], 404);
        }
        else
        {
            $data = Site::with(['components'])
                ->get();

            if($data) return response()->json($data, 200);
            else return response()->json(['message' => 'No sites registered in the system.'], 404);
        }
    }
}
