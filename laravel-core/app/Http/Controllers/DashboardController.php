<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Remarketing;
use App\Models\FacebookPage;
use Illuminate\Http\Request;
use App\Models\FacebookMessage;
use Illuminate\Support\Facades\DB;
use App\Models\RemarketingMessages;
use App\Models\DashboardResponseTime;
use App\Models\RemarketingIntervalMessages;

class DashboardController extends Controller
{
    
    public function index()
    {
        return view('pages.dashboard.index')->with('data', []);
    }
}
