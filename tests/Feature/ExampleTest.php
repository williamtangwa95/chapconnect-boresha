<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_about_page_returns_successful_response(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSeeText('About Us');
    }

    public function test_about_page_swahili_translation(): void
    {
        $this->get(route('lang.switch', 'sw'));
        $response = $this->get('/about');

        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->getContent(), 'Kuhusu Sisi'));
        $this->assertTrue(str_contains($response->getContent(), 'Dhamira Yetu'));
        $this->assertTrue(str_contains($response->getContent(), 'Maono Yetu'));
        $this->assertTrue(str_contains($response->getContent(), 'Sisi ni Nani'));
        $this->assertTrue(str_contains($response->getContent(), 'Hakuna makato ya udalali'));
    }
}
