<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogTest extends TestCase
{
    /**
     * Test a Home Page Is Loaded Without Errors.
     *
     * @return void
     */
    public function test_home_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test Log Vaild
     *
     * @return void
     */

    public function test_has_a_vaild_params()
    {
        $response = $this->post('log');

        $response->assertStatus(422);
    }

    /**
     * Test Log file Not Found
     *
     * @return void
     */

    public function test_log_file_not_found()
    {
        $response = $this->post('log', [
            "file" => "/temp/log.lg",
            "page" => 1
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test Log file return just 10 per page
     *
     * @return void
     */

    public function test_pagiantion_per_page()
    {
        $response = $this->post('log', [
            "file" => "/Users/fadymondy/.valet/log/mysql.log",
            "page" => 1
        ]);
        $response->assertStatus(200);
        $this->assertEquals(10, count($response->original['body']['lines']));
    }

    /**
     * Test Log file get next page
     *
     * @return void
     */

    public function test_pagiantion_next_page()
    {
        $response = $this->post('log', [
            "file" => "/Users/fadymondy/.valet/log/mysql.log",
            "page" => 3
        ]);
        $response->assertStatus(200);
        $this->assertEquals(10, count($response->original['body']['lines']));
    }
}
