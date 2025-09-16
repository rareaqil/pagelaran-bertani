<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockMovementControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user admin sesuai data Anda
        $this->admin = User::factory()->create([
            'first_name' => 'Admin User',
            'email' => 'admin@example.com',
            // password asli tidak penting di sini karena kita pakai actingAs
        ]);

        // Buat product sesuai data Anda
        $this->product = Product::factory()->create([
            'name' => 'Product Pertama',
            'description' => 'Ini adalah Product Pertama kita',
            'price' => 1000000,
            'stock' => 10,
            'image' => 'http://localhost:8000/storage/photos/2/s.png',
            'sku' => 'SKU-ICKKB7NQ',
        ]);
    }

    /** @test */
    public function it_can_list_stock_movements()
    {
        $this->actingAs($this->admin);

        $response = $this->getJson(route('stock.index'));

        $response->assertStatus(200)
                 ->assertJsonStructure([ '*' => ['id','product_id','type','quantity'] ]);
    }

    /** @test */
    public function it_can_hold_stock()
    {
        $this->actingAs($this->admin);

        $payload = [
            'product_id' => $this->product->id,
            'quantity'   => 2,
        ];

        $response = $this->postJson(route('stock.hold'), $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Stok di-hold',
                 ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type'       => 'hold',
            'quantity'   => 2,
        ]);
    }

    /** @test */
    public function it_can_confirm_payment_and_reduce_stock()
    {
        $this->actingAs($this->admin);

        $hold = StockMovement::factory()->create([
            'product_id' => $this->product->id,
            'type' => 'hold',
            'quantity' => 2,
        ]);

        $response = $this->postJson(route('stock.confirmPayment', $hold->id));
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Pembayaran sukses, stok dikurangi',
                 ]);

        $this->assertDatabaseHas('stock_movements', [
            'id' => $hold->id,
            'type' => 'out',
        ]);

        $this->product->refresh();
        $this->assertEquals(8, $this->product->stock);
    }

    /** @test */
    public function it_can_cancel_hold()
    {
        $this->actingAs($this->admin);

        $hold = StockMovement::factory()->create([
            'product_id' => $this->product->id,
            'type' => 'hold',
            'quantity' => 1,
        ]);

        $response = $this->postJson(route('stock.cancelHold', $hold->id));
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Hold dibatalkan',
                 ]);

        $this->assertDatabaseHas('stock_movements', [
            'id' => $hold->id,
            'type' => 'reversal',
        ]);
    }

    /** @test */
    public function it_can_add_stock()
    {
        $this->actingAs($this->admin);

        $payload = [
            'product_id' => $this->product->id,
            'quantity'   => 3,
        ];

        $response = $this->postJson(route('stock.add'), $payload);
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Stok berhasil ditambahkan',
                 ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $this->product->id,
            'type' => 'in',
            'quantity' => 3,
        ]);

        $this->product->refresh();
        $this->assertEquals(13, $this->product->stock);
    }
}
