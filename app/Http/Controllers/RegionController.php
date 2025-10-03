<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class RegionController extends Controller
{
    public function provinces(Request $request)
    {
        $query = Province::select('id', 'name')->orderBy('name');

        if ($request->filled('q')) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->q) . '%']);
        }

        return $query->get();
    }

    public function regencies(Request $request, $provinceId)
    {
        $query = Regency::where('province_id', $provinceId)
            ->select('id', 'name')
            ->orderBy('name');

        if ($request->filled('q')) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->q) . '%']);
        }

        return $query->get();
    }

    public function districts(Request $request, $regencyId)
    {
        $query = District::where('regency_id', $regencyId)
            ->select('id', 'name')
            ->orderBy('name');

        if ($request->filled('q')) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->q) . '%']);
        }

        return $query->get();
    }

    public function villages(Request $request, $districtId)
    {
        $query = Village::where('district_id', $districtId)
            ->select('id', 'name')
            ->orderBy('name');

        if ($request->filled('q')) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->q) . '%']);
        }

        return $query->get();
    }
}
