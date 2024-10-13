<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    #[Test]
    public function can_see_home_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
