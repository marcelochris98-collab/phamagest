<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_guests_see_the_public_landing_page(): void
    {
        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertSeeText('Votre activité pharmaceutique,')
            ->assertSee(route('register'))
            ->assertSee(route('login'));
    }
}
