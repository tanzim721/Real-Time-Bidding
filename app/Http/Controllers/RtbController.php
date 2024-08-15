<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RtbController extends Controller
{
    public function handleBidRequest(Request $request): JsonResponse
    {
        // Parse the incoming bid request JSON
        $bidRequest = $request->json()->all();

        // Example campaign array (you can replace this with data from a database)
        $campaigns = [
            [
                "campaignname" => "Test_Banner_13th-31st_march_Developer",
                "advertiser" => "TestGP",
                "code" => "118965F12BE33FB7E",
                "appid" => "20240313103027",
                "tld" => "https://adplaytechnology.com/",
                "creative_type" => "1",
                "creative_id" => 167629,
                "day_capping" => 0,
                "dimension" => "320x480",
                "attribute" => "rich-media",
                "url" => "https://adplaytechnology.com/",
                "billing_id" => "123456789",
                "price" => 0.1,
                "bidtype" => "CPM",
                "image_url" => "https://s3-ap-southeast-1.amazonaws.com/elasticbeanstalk-ap-southeast-1-5410920200615/CampaignFile/20240117030213/D300x250/e63324c6f222208f1dc66d3e2daaaf06.png"
            ],
            // Add more campaigns here...
        ];

        // Select the most suitable campaign
        $selectedCampaign = $this->selectCampaign($bidRequest, $campaigns);

        // Generate the response
        $response = [
            'id' => $bidRequest['id'],
            'bidid' => uniqid(),
            'seatbid' => [
                [
                    'bid' => [
                        [
                            'id' => $selectedCampaign['creative_id'],
                            'impid' => $bidRequest['imp'][0]['id'],
                            'price' => $selectedCampaign['price'],
                            'adid' => $selectedCampaign['code'],
                            'nurl' => $selectedCampaign['url'],
                            'adm' => $selectedCampaign['image_url'],
                            'adomain' => [$selectedCampaign['tld']],
                            'iurl' => $selectedCampaign['image_url'],
                            'cid' => $selectedCampaign['code'],
                            'crid' => $selectedCampaign['creative_id'],
                            'attr' => [$selectedCampaign['attribute']],
                        ]
                    ]
                ]
            ]
        ];

        // Return JSON response
        return response()->json($response);
    }

    private function selectCampaign(array $bidRequest, array $campaigns): array
    {
        // Select campaign based on dimension matching (you can expand this logic)
        foreach ($campaigns as $campaign) {
            // Example logic: Check if campaign dimension matches the bid request
            $dimension = $campaign['dimension'];
            $bannerWidth = $bidRequest['imp'][0]['banner']['w'];
            $bannerHeight = $bidRequest['imp'][0]['banner']['h'];
            if ("{$bannerWidth}x{$bannerHeight}" === $dimension) {
                return $campaign;
            }
        }

        // Fallback to the first campaign if no match found
        return $campaigns[0];
    }
}
