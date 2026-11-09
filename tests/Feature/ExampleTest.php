<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_homepage_displays_the_library_catalog(): void
    {
        $response = $this->get('/');

        $response->assertOk()->assertViewIs('welcome');
    }
}
