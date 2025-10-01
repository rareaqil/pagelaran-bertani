<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'whatsapp' => 'required',
            'instagram' => 'required',
            'message' => 'required',
        ]);

        $data = $request->all();

        // Email hardcode
        $to = "pagelaranbertani@gmail.com";

        Mail::to($to)->send(new ContactMail($data));

        return back()->with('success', 'Pesan berhasil dikirim!');
    }
}