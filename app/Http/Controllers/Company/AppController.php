<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public $companyId;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->status == 1 && Auth::user()->is_deleted != 1) {
                $this->companyId = Auth::guard('web')->user()->company_id;
                return $next($request);
            } else {
                Auth::logout();
                return redirect()->route('login');
            }
        });
    }
}
