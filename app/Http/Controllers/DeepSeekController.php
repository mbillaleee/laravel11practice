<?php

namespace App\Http\Controllers;

use App\Services\DeepSeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeepSeekController extends Controller
{
    protected $deepSeekService;

    public function __construct(DeepSeekService $deepSeekService)
    {
        $this->deepSeekService = $deepSeekService;
    }

    /**
     * Show chat interface
     */
    public function chat()
    {
        $apiStatus = $this->deepSeekService->validateApiKey();
        
        return view('deepseek.chat', [
            'apiStatus' => $apiStatus
        ]);
    }

    /**
     * Handle chat messages (AJAX)
     */
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:5000|min:1',
            'model' => 'sometimes|string|in:deepseek-chat,deepseek-coder',
            'temperature' => 'sometimes|numeric|min:0|max:2',
            'max_tokens' => 'sometimes|integer|min:1|max:8000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed: ' . $validator->errors()->first()
            ], 422);
        }

        $message = trim($request->input('message'));
        $model = $request->input('model', 'deepseek-chat');
        $temperature = (float) $request->input('temperature', 0.7);
        $maxTokens = (int) $request->input('max_tokens', 2000);

        $result = $this->deepSeekService->ask($message, $model, $maxTokens, $temperature);

        return response()->json($result);
    }

    /**
     * Check API status
     */
    public function checkApiStatus()
    {
        $result = $this->deepSeekService->validateApiKey();
        
        return response()->json($result);
    }

    /**
     * Test API connection
     */
    public function testConnection()
    {
        $result = $this->deepSeekService->testConnection();
        
        return response()->json($result);
    }

    /**
     * Get available models
     */
    public function getModels()
    {
        $result = $this->deepSeekService->getModels();
        
        return response()->json($result);
    }

    /**
     * Debug information
     */
    public function debugInfo()
    {
        $apiKey = config('services.deepseek.api_key');
        $baseUrl = config('services.deepseek.base_url');
        
        $debugInfo = [
            'api_key_configured' => !empty($apiKey) && $apiKey !== 'your_actual_deepseek_api_key_here',
            'api_key_prefix' => substr($apiKey, 0, 10) . '...',
            'base_url' => $baseUrl,
            'environment' => app()->environment(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'timestamp' => now()->toDateTimeString()
        ];
        
        return response()->json($debugInfo);
    }
}