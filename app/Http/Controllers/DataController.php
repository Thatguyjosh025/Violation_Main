<?php

namespace App\Http\Controllers;

use App\Models\referals;
use App\Models\statuses;
use App\Models\penalties;
use App\Models\violation;
use App\Models\priorityrisk;
use Illuminate\Http\Request;
use App\Models\sessionstatus;
use App\Models\guidanceservice;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:discipline,counselor'])->only([
            'getViolations', 'getPenalties', 'getReferals', 'getStatus', 'getcounselingstatus'
        ]);    
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
    public function getcounselingstatus(Request $request)
    {
        return response()->json(['counselingstatus_data' => sessionstatus::all()]);
    }
    public function getpriorityrisk(Request $request)
    {
        return response()->json(['priorityrisk_data' => priorityrisk::all()]);
    }
    public function getguidanceservice(Request $request)
    {
        return response()->json(['guidanceservice_data' => guidanceservice::all()]);
    }

}
