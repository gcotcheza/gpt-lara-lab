<?php

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
        ]);
    }

    public function generateResponse($prompt)
    {
        try {
            $response = $this->client->post('chat/completions', [
                'json' => [
                    'model' => 'gpt-4-1106-preview',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a sarcastic assistant.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ],
            ]);

            $decodedResponse = json_decode((string) $response->getBody(), true);

            // Using null coalescing operator to simplify the conditional check
            return $decodedResponse['choices'][0]['message']['content'] 
                ?? 'Sorry, I could not process your request.';
        } catch (\Exception $e) {
            error_log("An error occurred: " . $e->getMessage());
            return 'An error occurred while processing your request.';
        }
    }


}