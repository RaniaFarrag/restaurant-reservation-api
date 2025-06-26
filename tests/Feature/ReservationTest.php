<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\Table;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_availability_returns_available_table()
    {
        Table::factory()->create(['capacity' => 4]);

        $response = $this->getJson('/api/check-availability?guests=3&from_time=2025-06-26T18:00:00&to_time=2025-06-26T20:00:00');

        $response->assertStatus(200)
                 ->assertJson([
                     'available' => true
                 ]);
    }

     public function test_reserve_table_creates_a_reservation()
    {
        $table = Table::factory()->create(['capacity' => 4]);

        $response = $this->postJson('/api/reserve-table', [
            'name' => 'Test Customer',
            'phone' => '01000000000',
            'guests' => 3,
            'from_time' => '2025-06-26T18:00:00',
            'to_time' => '2025-06-26T20:00:00',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertDatabaseHas('reservations', [
            'table_id' => $table->id,
        ]);
    }
}
