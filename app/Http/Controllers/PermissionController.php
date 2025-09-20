<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Permission::latest()->paginate(5);

        return view('permissions.index',compact('data'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::latest()->get();

        return view('permissions.index',compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'guard_name' => 'required',
        ]);
    
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->guard_name = $request->guard_name;
        $permission->save();

    
        return redirect()->route('permissions.create')
                        ->with('success','Permission created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $permission =  Permission::findOrFail($id);
         $this->validate($request, [
            'name' => 'required',
            'guard_name' => 'required',
        ]);

         // dd($permission, $id);
    

        $permission->name = $request->name;
        $permission->guard_name = $request->guard_name;
        $permission->save();

    
        return redirect()->route('permissions.create')
                        ->with('success','Permission update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::table("permissions")->where('id',$id)->delete();
        return redirect()->route('permissions.create')
                        ->with('success','Role deleted successfully');
    }
}