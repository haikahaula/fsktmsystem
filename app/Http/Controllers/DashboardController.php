<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Redirect each role to its own dashboard or route
        switch ($user->role_id) {
            case 1:
                return view('dashboard');
            case 2:
                return view('dashboard'); // Academic Head uses shared dashboard
            case 3:
                return view('dashboard'); // Academic Staff uses shared dashboard
            default:
                abort(403, 'Unauthorized');
        }
    }

}
