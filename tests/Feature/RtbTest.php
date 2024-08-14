<?php

namespace Tests\Feature;

use Tests\TestCase;

class RtbTest extends TestCase
{
    public function testBidRequestHandling()
    {
        $bidRequest = '{
            "id": "myB92gUhMdC5DUxndq3yAg",
            "imp": [{"id": "1", "banner": {"w": 320, "h": 50}}]
            ...
        }';

        $response = $this->postJson('/rtb', json_decode($bidRequest, true));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'campaignname',
                'advertiser',
                'creative_id',
                'image_url',
                'url',
                'price',
                'ad_id',
            ]);
    }
}
