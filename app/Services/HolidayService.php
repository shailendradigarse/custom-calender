<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class HolidayService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchHolidays($country, $year)
    {
        $apiKey = env('CALENDARIFIC_API_KEY'); // Store the API key in .env
        $url = "https://calendarific.com/api/v2/holidays?&api_key={$apiKey}&country={$country}&year={$year}";

        try {
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);
            return $data['response']['holidays'];
        } catch (\Exception $e) {
            Log::error("Failed to fetch holidays: " . $e->getMessage());
            return [];
        }
    }
}
