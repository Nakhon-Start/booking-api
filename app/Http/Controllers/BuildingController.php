<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class BuildingController extends Controller
{
    public function CreateBuilding(Request $request)
    {
        if (!Gate::allows('is_admin')) {
            throw new Exception("Unauthenticated.");
        }

        $this->validate($request, [
            'name' => 'required|string|max:64|unique:building',
            'description' => 'required|string|max:255',

        ]);

        $building = Building::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'create_by' => Auth::user()->id
        ]);

        return response()->json([
            'Building' => $building
        ], 200);
    }

    public function GetListBuilding()
    {
        return Building::with('room')->latest('id')->get();
    }

    public function GetBuilding(Request $request)
    {
        $request->validate([

            'building_id' => 'required|integer',
        ]);
        
        return Building::with('room')->where('id' ,'=', $request['building_id'])->get();

    }

    public function SetBuilding(Request $request)
    {
        if (!Gate::allows('is_admin')) {
            throw new Exception("Unauthenticated.");
        }

        $this->validate($request, [
            'id' => 'required',
            'name' => 'required|string|max:64|unique:building',
            'description' => 'required|string|max:255',
            'is_active' => 'required|integer',
        ]);

        $id = $request['id'];

        $building = Building::find($id);

        $building->update([
            'name' => $request['name'],
            'description' => $request['description'],
            'is_active' => $request['is_active'],
            'create_by' => Auth::user()->id

        ]);

        return response()->json([

            'Building' => $building,
        ]);
    }
}
