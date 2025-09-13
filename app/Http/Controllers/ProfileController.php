<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\UserAddress;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

         UserAddress::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'address1'    => $request->input('address1'),
                'postcode'    => $request->input('postcode'),
                'province_id' => $request->input('province_id'),
                'regency_id'  => $request->input('regency_id'),
                'district_id' => $request->input('district_id'),
                'village_id'  => $request->input('village_id'),
                'province_name' => $request->input('province_id') ? Province::find($request->input('province_id'))->name : null,
                'regency_name'  => $request->input('regency_id') ? Regency::find($request->input('regency_id'))->name : null,
                'district_name' => $request->input('district_id') ? District::find($request->input('district_id'))->name : null,
                'village_name'  => $request->input('village_id') ? Village::find($request->input('village_id'))->name : null
            ]
        );

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
