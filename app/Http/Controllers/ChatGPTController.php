<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\OpenAIService;
use App\Models\ConversationLogs;
use App\Models\ConversationHistory;
use App\Http\Requests\ChatGPTRequest;
use App\Services\ChatGPTResponseParser;

class ChatGPTController extends Controller
{
    protected $openAIService;


    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function chat(Request $request)
    {
        $prompt = $request->input('prompt');

        // $response = $this->openAIService->generateResponse($prompt);
        try {
            $response = $this->openAIService->generateResponse($prompt);
            // Save conversation history
            ConversationLogs::create([
                // 'user_id' => $userId,
                'user_message' => $prompt,
                'bot_response' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json([
            'response' => $response,
        ]);
    }

    protected function fetchUserData($userName)
    {
        $users = User::wildCardSearch($userName)->get();

        if ($users->isEmpty()) {
            return "No user found with the name '$userName'.";
        }

        return $users->toArray();
    }

    protected function parseUserPrompt($userInput)
    {
        if (str_contains(strtolower($userInput), 'user details')) {
            return ['intent' => 'fetch_user', 'query' => $userInput];
        }

        // Add more conditions for different intents

        return ['intent' => 'unknown', 'query' => $userInput];
    }

    protected function performActionBasedOnIntent($parsedRequest)
{
    switch ($parsedRequest['intent']) {
        case 'fetch_user':
            // Extracting a hypothetical username from the query
            $userName = $this->extractUserNameFromQuery($parsedRequest['query']);
            return $this->fetchUserData($userName);
        // Add more cases for other intents
    }

    return null;
}

// Helper method to extract a username from the query
protected function extractUserNameFromQuery($query)
{
    // Implement logic to extract username
    // This is a placeholder example
    preg_match('/user details of (\w+)/', $query, $matches);
    return $matches[1] ?? 'default';
}

// // Assuming you have a method to fetch user data
// protected function fetchUserData($userName)
// {
//     // Fetch user data from your database
//     // This is a placeholder example
//     return User::where('name', $userName)->first();
// }

protected function preparePromptForChatGPTWithData($data)
{
    if ($data) {
        // Assuming $data is a User model instance
        return "I found a user named {$data->name} with email {$data->email}. How can I assist you further with this user?";
    }

    return "I couldn't find any information based on your request. Could you please provide more specific details?";
}


}
