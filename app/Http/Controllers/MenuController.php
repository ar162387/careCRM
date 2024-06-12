<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::with("parent")->get();
        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentMenus = (new Menu())->parentsOnly();
        return view('admin.menus.create', compact('parentMenus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate(request(),[
            'title' => 'required',
        ]);

        $menu = new Menu();
        $menu->title = $request['title'];
        $menu->icon = $request['icon'];
        $menu->route = $request['route'];
        $menu->parent_id = $request['parent_id'];
        $menu->display_order = $request['display_order'];
        $menu->level = $request['level'];
        $menu->status = $request['status'];
        $menu->save();

        return redirect()->route('menus.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        //
    }
}
