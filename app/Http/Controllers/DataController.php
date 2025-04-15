<?php

namespace App\Http\Controllers;

use App\Models\referals;
use App\Models\statuses;
use App\Models\penalties;
use App\Models\violation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:discipline')->only(['getViolations', 'getPenalties', 'getReferals', 'getStatus']);
    }

    public function getViolations(Request $request)
    {
        return response()->json(['violation_data' => violation::all()]);
    }

    public function getPenalties(Request $request)
    {
        return response()->json(['penalties_data' => penalties::all()]);
    }

    public function getReferals(Request $request)
    {
        return response()->json(['referals_data' => referals::all()]);
    }

    public function getStatus(Request $request)
    {
        return response()->json(['status_data' => statuses::all()]);
    }
}
