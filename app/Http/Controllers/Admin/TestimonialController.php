<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        // ambil testimonials lengkap dengan relasi user & product
        $testimonials = Testimonial::with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $products = Product::orderBy('name')->get();
        $users = User::orderBy('first_name')->get();

        return view('backend.testimonials.index', compact('testimonials', 'products', 'users'));
    }

    public function show(Testimonial $testimonial)
{
    $testimonial->load(['user', 'product']);

    return response()->json([
        'data' => [
            'id' => $testimonial->id,
            'user_id' => $testimonial->user_id,
            'user_name' => $testimonial->user->first_name ?? $testimonial->user->name ?? '-',
            'product_id' => $testimonial->product_id,
            'product_name' => $testimonial->product->name ?? '-',
            'rating' => (int) $testimonial->rating,
            'comment' => $testimonial->comment,
            'is_approved' => (bool) $testimonial->is_approved,
            'created_at' => $testimonial->created_at?->format('Y-m-d H:i:s'),
        ]
    ]);
}

    /**
     * Create or Update (sama seperti gaya voucher controller: kalau id ada => update)
     */
    public function store(Request $request, $id = null)
    {
        $id = $request->input('id', $id);

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'is_approved' => 'nullable|boolean',
        ]);

        $data = array_merge($validated, [
            'is_approved' => $request->boolean('is_approved', false),
        ]);

        if ($id) {
            $testimonial = Testimonial::findOrFail($id);
            $testimonial->update($data);
            $message = 'Testimonial berhasil diperbarui.';
        } else {
            $testimonial = Testimonial::create($data);
            $message = 'Testimonial baru berhasil ditambahkan.';
        }

        // reload relasi agar response lengkap
        $testimonial->load(['product', 'user']);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'id' => $testimonial->id,
                'product_id' => $testimonial->product_id,
                'product_name' => $testimonial->product->name ?? '-',
                'user_id' => $testimonial->user_id,
                'user_name' => $testimonial->user->first_name ?? '-',
                'rating' => (int) $testimonial->rating,
                'comment' => $testimonial->comment,
                'is_approved' => (bool) $testimonial->is_approved,
                'created_at' => $testimonial->created_at?->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    public function toggle(Testimonial $testimonial)
    {
        $testimonial->is_approved = ! $testimonial->is_approved;
        $testimonial->save();

        return response()->json([
            'success' => true,
            'status' => $testimonial->is_approved,
            'message' => $testimonial->is_approved ? 'Testimonial dipublikasi.' : 'Testimonial disembunyikan.',
        ]);
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return response()->json([
            'success' => true,
            'message' => 'Testimonial berhasil dihapus permanen.',
        ]);
    }
}
