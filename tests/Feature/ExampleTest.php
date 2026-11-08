<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_home_address_sends_guests_to_the_login_page(): void
    {
        $this->get('/')->assertRedirect(route('login'));

        $this->get('/login')->assertOk();
    }
}
