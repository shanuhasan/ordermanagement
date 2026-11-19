<?php

namespace App\Http\Controllers\Company;

use App\Models\Party;
use App\Models\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public $companyId;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = Auth::user();

            if ($user->status == 1 && $user->is_deleted != 1) {
                $this->companyId = $user->company_id;
                return $next($request);
            }

            Auth::logout();
            return redirect()->route('login');
        });
    }

    /**
     * Find an employee/contractor/master of the logged-in company, or 404.
     * Prevents opening another company's records by guessing a GUID.
     */
    protected function findEmployee($guid): Employee
    {
        $employee = Employee::findByGuidAndCompanyId($guid, $this->companyId);
        abort_if(empty($employee), 404);

        return $employee;
    }

    /**
     * Find a party of the logged-in company, or 404.
     */
    protected function findParty($guid): Party
    {
        $party = Party::findByGuidAndCompanyId($guid, $this->companyId);
        abort_if(empty($party), 404);

        return $party;
    }
}
