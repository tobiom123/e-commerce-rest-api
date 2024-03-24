<?php

namespace Tests\Unit\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{

    public function test_all_items_can_be_listed(): void
    {
        $itemCount = 5;
        Item::factory()->count($itemCount)->create();

        $this->assertDatabaseCount('items', $itemCount);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/items');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'price',
                        'stock',
                        'created_at',
                        'updated_at'
                    ],
                ],
            ]);
    }

}
