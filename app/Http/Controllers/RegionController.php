<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class RegionController extends Controller
{
    public function provinces()
    {
        return Province::select('id','name')->orderBy('name')->get();
    }

    public function regencies($provinceId)
    {
        return Regency::where('province_id', $provinceId)
            ->select('id','name')
            ->orderBy('name')
            ->get();
    }

    public function districts($regencyId)
    {
        return District::where('regency_id', $regencyId)
            ->select('id','name')
            ->orderBy('name')
            ->get();
    }

    public function villages($districtId)
    {
        return Village::where('district_id', $districtId)
            ->select('id','name')
            ->orderBy('name')
            ->get();
    }
}

