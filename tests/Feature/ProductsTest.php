<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    public function test_products_from_cache()
    {
        $response = $this->get('/api/products');
        $response->assertStatus(200)->assertJson(['message'=>'Fetched from Database']);
        $response = $this->get('/api/products');
        $response->assertStatus(200)->assertJson(['message'=>'Fetched from Redis']);
    }
}
