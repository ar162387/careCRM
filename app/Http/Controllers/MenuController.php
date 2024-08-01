<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu; // Adjust according to your actual model namespace

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        $parentMenus = Menu::whereNull('parent_id')->get();
        return view('menus.create', compact('parentMenus'));
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'display_order' => 'nullable|integer|min:0|max:100',
            'level' => 'required|in:admin,client',
            'status' => 'required|boolean',
        ]);

        // Create new menu
        Menu::create([
            'title' => $request->title,
            'icon' => $request->icon,
            'route' => $request->route,
            'parent_id' => $request->parent_id,
            'display_order' => $request->display_order ?? 0,
            'level' => $request->level,
            'status' => $request->status,
        ]);

        return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $parentMenus = Menu::whereNull('parent_id')->get();
        return view('menus.edit', compact('menu', 'parentMenus'));
    }

    public function update(Request $request, Menu $menu)
    {
        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menus,id',
            'display_order' => 'nullable|integer|min:0|max:100',
            'level' => 'required|in:admin,client',
            'status' => 'required|boolean',
        ]);

        // Update menu
        $menu->update([
            'title' => $request->title,
            'icon' => $request->icon,
            'route' => $request->route,
            'parent_id' => $request->parent_id,
            'display_order' => $request->display_order ?? 0,
            'level' => $request->level,
            'status' => $request->status,
        ]);

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }
}
