<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->get();

        return view('backend.vouchers.index', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $id = $request->input('id');

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('vouchers', 'code')->ignore($id)],
            'type' => 'required|in:percentage,fixed',
            'value' => [
                'required',
                'numeric',
                'min:0',
                Rule::when($request->type === 'percentage', ['max:100']),
            ],
            'min_order_amount' => 'required|numeric|min:0',
            'max_usage' => 'nullable|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $data = array_merge($validated, [
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (!$id) {
            $data['used_count'] = 0; // hanya buat voucher baru
            $voucher = Voucher::create($data);
            $message = 'Voucher baru berhasil dibuat.';
        } else {
            $voucher = Voucher::findOrFail($id);
            $voucher->update($data);
            $message = 'Voucher berhasil diperbarui.';
        }

        // pastikan JSON selalu lengkap (tidak ada undefined)
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'type' => $voucher->type,
                'value' => $voucher->value,
                'min_order_amount' => $voucher->min_order_amount,
                'max_usage' => $voucher->max_usage,
                'used_count' => $voucher->used_count,
                'start_date' => optional($voucher->start_date)->format('Y-m-d H:i:s'),
                'end_date' => optional($voucher->end_date)->format('Y-m-d H:i:s'),
                'is_active' => $voucher->is_active,
            ],
        ]);
    }

    public function show(Voucher $voucher)
{
    return response()->json([
        'success' => true,
        'data' => [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'type' => $voucher->type,
            'value' => $voucher->value,
            'min_order_amount' => $voucher->min_order_amount,
            'max_usage' => $voucher->max_usage,
            'used_count' => $voucher->used_count,
            'start_date' => optional($voucher->start_date)->format('Y-m-d H:i:s'),
            'end_date' => optional($voucher->end_date)->format('Y-m-d H:i:s'),
            'is_active' => $voucher->is_active,
        ],
    ]);
}


    public function toggle(Voucher $voucher)
    {
        $voucher->is_active = ! $voucher->is_active;
        $voucher->save();

        return response()->json([
            'success' => true,
            'status' => $voucher->is_active,
            'message' => $voucher->is_active
                ? 'Voucher berhasil diaktifkan kembali.'
                : 'Voucher berhasil dinonaktifkan.',
        ]);
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil dihapus permanen.',
        ]);
    }
}
