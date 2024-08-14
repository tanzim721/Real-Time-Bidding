<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RtbController extends Controller
{
    public function handleBidRequest(Request $request)
    {
        $bidRequest = json_decode($request->getContent(), true);

        if (!$this->isValidBidRequest($bidRequest)) {
            return response()->json(['error' => 'Invalid bid request'], 400);
        }

        $campaign = $this->selectCampaign($bidRequest);

        if (!$campaign) {
            return response()->json(['error' => 'No suitable campaign found'], 204);
        }

        $response = $this->generateBannerResponse($campaign);

        return response()->json($response);
    }

    private function isValidBidRequest($bidRequest)
    {
        return isset($bidRequest['id']) && isset($bidRequest['imp']) && isset($bidRequest['device']);
    }

    private function selectCampaign($bidRequest)
    {
        $campaigns = $this->getCampaigns();

        $selectedCampaign = null;

        foreach ($campaigns as $campaign) {
            if ($this->isCampaignCompatible($campaign, $bidRequest)) {
                if (!$selectedCampaign || $campaign['price'] > $selectedCampaign['price']) {
                    $selectedCampaign = $campaign;
                }
            }
        }

        return $selectedCampaign;
    }

    private function isCampaignCompatible($campaign, $bidRequest)
    {
        $device = $bidRequest['device'];
        $geo = $device['geo'];

        return $campaign['country'] === $geo['country'] && $campaign['hs_os'] === $device['os'];
    }

    private function generateBannerResponse($campaign)
    {
        return [
            'campaignname' => $campaign['campaignname'],
            'advertiser' => $campaign['advertiser'],
            'creative_id' => $campaign['creative_id'],
            'image_url' => $campaign['image_url'],
            'url' => $campaign['url'],
            'price' => $campaign['price'],
            'ad_id' => $campaign['code'],
        ];
    }

    private function getCampaigns()
    {
        return [
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
                "image_url" => "https://s3-ap-southeast-1.amazonaws.com/elasticbeanstalk-ap-southeast-1-5410920200615/CampaignFile/20240117030213/D300x250/e63324c6f222208f1dc66d3e2daaaf06.png",
                "hs_os" => "Android,iOS,Desktop",
                "country" => "Bangladesh",
                "device_make" => "No Filter"
            ]
        ];
    }
}
