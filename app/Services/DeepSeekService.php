<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class DeepSeekService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.deepseek.api_key');
        $this->baseUrl = config('services.deepseek.base_url', 'https://api.deepseek.com/v1');
        
        if (empty($this->apiKey) || $this->apiKey === 'your_actual_deepseek_api_key_here') {
            throw new Exception('DeepSeek API key is not configured. Please set DEEPSEEK_API_KEY in your .env file.');
        }
    }

    /**
     * Chat completion API call with proper data types
     */
    public function chatCompletion($messages, $model = 'deepseek-chat', $maxTokens = 2000, $temperature = 0.7)
    {
        try {
            // Validate messages format
            if (!$this->validateMessages($messages)) {
                return [
                    'success' => false,
                    'error' => 'Invalid messages format. Each message must have role and content.'
                ];
            }

            // Prepare payload with correct data types
            $payload = [
                'model' => $model,
                'messages' => $messages,
                'max_tokens' => (int) $maxTokens, // Ensure integer
                'temperature' => (float) $temperature, // Ensure float
                'stream' => false
            ];

            Log::info('DeepSeek API Request', ['payload' => $payload]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json'
            ])->timeout(60)->post($this->baseUrl . '/chat/completions', $payload);

            Log::info('DeepSeek API Response Status', ['status' => $response->status()]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('DeepSeek API Success', [
                    'model' => $data['model'] ?? $model,
                    'tokens_used' => $data['usage']['total_tokens'] ?? 0
                ]);
                
                return [
                    'success' => true,
                    'content' => $data['choices'][0]['message']['content'] ?? 'No response generated.',
                    'usage' => $data['usage'] ?? null,
                    'model' => $data['model'] ?? $model,
                    'id' => $data['id'] ?? null
                ];
            } else {
                $errorBody = $response->body();
                $errorJson = $response->json();
                
                Log::error('DeepSeek API Error', [
                    'status_code' => $response->status(),
                    'error' => $errorJson['error']['message'] ?? $errorBody,
                    'payload' => $payload
                ]);
                
                $errorMessage = $this->getErrorMessage($response->status(), $errorJson);
                
                return [
                    'success' => false,
                    'error' => $errorMessage,
                    'status_code' => $response->status(),
                    'details' => $errorJson ?? $errorBody
                ];
            }
        } catch (Exception $e) {
            Log::error('DeepSeek Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => 'Service exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Simple prompt response
     */
    public function ask($prompt, $model = 'deepseek-chat', $maxTokens = 2000, $temperature = 0.7)
    {
        // Validate prompt
        if (empty(trim($prompt))) {
            return [
                'success' => false,
                'error' => 'Prompt cannot be empty'
            ];
        }

        $messages = [
            [
                'role' => 'user',
                'content' => trim($prompt)
            ]
        ];

        return $this->chatCompletion($messages, $model, (int) $maxTokens, (float) $temperature);
    }

    /**
     * Validate messages array format
     */
    private function validateMessages($messages)
    {
        if (!is_array($messages) || empty($messages)) {
            return false;
        }

        foreach ($messages as $message) {
            if (!isset($message['role']) || !isset($message['content'])) {
                return false;
            }
            
            if (!in_array($message['role'], ['system', 'user', 'assistant'])) {
                return false;
            }
            
            if (!is_string($message['content']) || empty(trim($message['content']))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get user-friendly error message
     */
    private function getErrorMessage($statusCode, $errorJson)
    {
        switch ($statusCode) {
            case 400:
                return 'Bad request - ' . ($errorJson['error']['message'] ?? 'Check your request parameters');
            case 401:
                return 'Invalid API key - please check your DEEPSEEK_API_KEY in .env file';
            case 429:
                return 'Rate limit exceeded - please try again later';
            case 500:
                return 'DeepSeek API server error - please try again later';
            case 503:
                return 'DeepSeek API service unavailable - please try again later';
            default:
                return $errorJson['error']['message'] ?? 'API request failed with status ' . $statusCode;
        }
    }

    /**
     * Check API key validity
     */
    public function validateApiKey()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json'
            ])->timeout(15)->get($this->baseUrl . '/models');

            if ($response->successful()) {
                $models = $response->json();
                return [
                    'valid' => true,
                    'message' => 'API key is valid and working',
                    'models' => array_column($models['data'] ?? [], 'id')
                ];
            } else {
                return [
                    'valid' => false,
                    'message' => 'API key validation failed: ' . $response->status(),
                    'details' => $response->body()
                ];
            }
        } catch (Exception $e) {
            return [
                'valid' => false,
                'message' => 'Validation error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Test API connection with simple request
     */
    public function testConnection()
    {
        $testPrompt = "Hello, please respond with just 'OK' if you can read this.";
        
        $result = $this->ask($testPrompt, 'deepseek-chat', 10, 0.1);
        
        if ($result['success']) {
            return [
                'success' => true,
                'message' => 'API connection test passed successfully',
                'response' => trim($result['content'])
            ];
        } else {
            return [
                'success' => false,
                'message' => 'API connection test failed',
                'error' => $result['error']
            ];
        }
    }

    /**
     * Get available models
     */
    public function getModels()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/models');

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'models' => $data['data'] ?? []
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Failed to fetch models: ' . $response->status()
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Error fetching models: ' . $e->getMessage()
            ];
        }
    }
}