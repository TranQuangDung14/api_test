<?php

namespace App\Http\Controllers;

use App\Models\Districts;
use App\Models\Provinces;
use App\Models\Wards;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    
    public function getProvinces()
    {
        // dd('dđ');
        $provinces = Provinces::all();
        return response()->json($provinces);
    }

    public function getDistricts($provinceId)
    {
        $districts = Districts::where('province_code', $provinceId)->get();
        return response()->json($districts);
    }

    public function getWards($districtId)
    {
        $wards = Wards::where('district_code', $districtId)->get();
        return response()->json($wards);
    }
}
