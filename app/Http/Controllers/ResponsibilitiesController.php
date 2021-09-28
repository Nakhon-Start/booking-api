<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Responsibilities;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


class ResponsibilitiesController extends Controller
{

    public function getResponsibilities()
    {
        return Responsibilities::where('user_id', '=', Auth::user()->id)
        ->select('id','user_id','building_id')
        ->get();
    }
    
    public function CreateResponsibilities(Request $request)
    {

        if (!Gate::allows('is_admin')) {
            throw new Exception("Unauthenticated.");
        }

        $request->validate([

            'building_id' => 'required|integer',
            'user_id' => 'required|integer'

        ]);

        $checker = Responsibilities::create([
            'user_id' => $request['user_id'],
            'building_id' => $request['building_id'],

        ]);

        return response()->json([
            'checker' => $checker,
            'Create By Checker' => Auth::user()->email
        ], 200);
    }

    public function GetChecker()
    {
        return Responsibilities::with('checker','building')->get();

    }

    public function GetCheckerFromBuildingID(Request $request)
    {

        if (!Gate::allows('is_admin')) {
            throw new Exception("Unauthenticated.");
        }

        $request->validate([

            'building_id' => 'required|integer'
        ]);

        $building_id = $request['building_id'];

        return Responsibilities::with('checker')
            ->where('building_id', '=', $building_id)
            ->select('user_id')
            ->get();
    }

    public function GetBuildingFormUserID(Request $request)
    {
        if (!Gate::allows('is_admin')) {
            throw new Exception("Unauthenticated.");
        }

        $request->validate([

            'user_id' => 'required|integer'
        ]);

        return Responsibilities::with('building')
            ->where('user_id', '=', $request['user_id'])
            ->select('building_id')
            ->get();
    }

    public function GetBuildingFormCheckerID()
    {
            return Responsibilities::with('building')
            ->where('user_id', '=', Auth::user()->id)
            ->select('building_id')
            ->get();
    }
}
