<?php

namespace App\Http\Controllers;

use Auth;
use App\Company;
use App\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    public function companyInfo()
    {

        $user = Auth::user();

        $company = Company::find($user->company_id);
        if ($user->id == $company->owner) {
            $users = User::where('company_id', $company->id)->get();
        } else {
            $users = [$user];
        }

        $response = ['user' => $user, 'company' => $company, 'users' => $users];
        return response()->json($response, 200);
    }



}
