<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Hash;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('primaryAddress')->paginate(10);
        return view('backend.users.index', compact('users')); // folder blade disesuaikan
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('backend.users.form'); // folder blade disesuaikan
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();

        // Hash password sebelum disimpan
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        // Simpan alamat user
        UserAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'address1'    => $request->address1,
                'postcode'    => $request->postcode,
                'province_id' => $request->province_id,
                'regency_id'  => $request->regency_id,
                'district_id' => $request->district_id,
                'village_id'  => $request->village_id,
                'province_name' => $request->input('province_id') ? Province::find($request->input('province_id'))->name : null,
                'regency_name'  => $request->input('regency_id') ? Regency::find($request->input('regency_id'))->name : null,
                'district_name' => $request->input('district_id') ? District::find($request->input('district_id'))->name : null,
                'village_name'  => $request->input('village_id') ? Village::find($request->input('village_id'))->name : null
            ]
        );

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('backend.users.form', compact('user')); // folder blade disesuaikan
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        // Password hanya diupdate jika diisi
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        // Update atau buat alamat user
        UserAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'address1'    => $request->address1,
                'postcode'    => $request->postcode,
                'province_id' => $request->province_id,
                'regency_id'  => $request->regency_id,
                'district_id' => $request->district_id,
                'village_id'  => $request->village_id,
                'province_name' => $request->input('province_id') ? Province::find($request->input('province_id'))->name : null,
                'regency_name'  => $request->input('regency_id') ? Regency::find($request->input('regency_id'))->name : null,
                'district_name' => $request->input('district_id') ? District::find($request->input('district_id'))->name : null,
                'village_name'  => $request->input('village_id') ? Village::find($request->input('village_id'))->name : null
            ]
        );

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
