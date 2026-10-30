<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirects_to_default_locale(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(config('app.fallback_locale') . '/');
        $response->assertStatus(301);
    }
}
