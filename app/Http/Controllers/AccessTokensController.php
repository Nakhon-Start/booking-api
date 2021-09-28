<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\AccessTokens;
use Illuminate\Support\Facades\Gate;

class AccessTokensController extends Controller
{
    public function onlineUser()
    {
        if (!Gate::allows('is_admin')) {
            throw new Exception("Unauthenticated.");
        }
        return AccessTokens::select('tokenable_id')->get();
    }
}
