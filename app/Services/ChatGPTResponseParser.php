<?php

namespace App\Services;

use Log;

class ChatGPTResponseParser
{
    public function parseResponse($response)
    {

        // Define patterns for each intent
        $patterns = [
            'fetch_user' => '/user.*named\s+(\w+)/i',
            'update_record' => '/(update|change|modify)\s+(\w+)/i',
            // Add more patterns as needed
        ];
Log::info('ChatGPT Response:', ['response' => $response]);

        // Check each pattern
        foreach ($patterns as $intent => $pattern) {
            if (preg_match($pattern, $response, $matches)) {
                return [
                    'intent' => $intent,
                    'parameters' => array_slice($matches, 2) // All matches except the full pattern match
                ];
            }
        }

        // Default response if no intent is identified
        return [
            'intent' => 'unknown',
            'parameters' => []
        ];
    }
}
