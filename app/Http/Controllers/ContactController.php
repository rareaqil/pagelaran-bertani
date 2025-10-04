<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');

        return view('frontend.contact-us', compact('settings'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'whatsapp' => 'required',
            'instagram' => 'required',
            'message' => 'required',
        ]);

        $data = $request->all();

        $to = Setting::where('key', 'contact_email')->value('value');

        if (empty($to)) {
            $to = ""; 
        }

        Mail::to($to)->send(new ContactMail($data));

        return back()->with('success', 'Pesan berhasil dikirim!');
    }

}