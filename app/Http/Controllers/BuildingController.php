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
        ] , 200);
    }

    public function GetListBuilding() // ดูรวม
    {
        $data = Building::with(['room'])->get();

        if ($data) {
            return response()->json([
                'message' => 'view Building Success !','data' => $data
            ], 200);
        } else {
            return response()->json(['message' => 'Not view Building !'], 404);
        }
    }
    public function GetBuilding($id)  // ดูตัวเดียว
    {

        $data = Building::find($id);

        if ($data) {
            return response()->json([
                'message' => 'view Building Success !',
                'user' => $data
            ], 200);
        } else {
            return response()->json(['message' => 'Not view Building !'], 404);
        }
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
            'is_active' => 'required|boolean',
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
