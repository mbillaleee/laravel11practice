<?php

namespace App\Console\Commands;

use App\Services\DeepSeekService;
use Illuminate\Console\Command;

class TestDeepSeek extends Command
{
    protected $signature = 'deepseek:test {prompt=Hello}';
    protected $description = 'Test DeepSeek AI integration';

    public function handle(DeepSeekService $deepSeekService)
    {
        $prompt = $this->argument('prompt');
        
        $this->info("🧠 Testing DeepSeek AI Integration...");
        $this->line("Prompt: {$prompt}");
        $this->line(str_repeat('-', 50));
        
        // Test API key validation
        $this->info("1. Validating API key...");
        $validation = $deepSeekService->validateApiKey();
        
        if ($validation['valid']) {
            $this->info("✅ API Key: Valid");
            $this->line("   Available models: " . implode(', ', $validation['models'] ?? []));
        } else {
            $this->error("❌ API Key: Invalid - " . $validation['message']);
            return 1;
        }
        
        $this->line(str_repeat('-', 50));
        
        // Test connection
        $this->info("2. Testing API connection...");
        $connectionTest = $deepSeekService->testConnection();
        
        if ($connectionTest['success']) {
            $this->info("✅ Connection: Working");
            $this->line("   Response: {$connectionTest['response']}");
        } else {
            $this->error("❌ Connection: Failed - {$connectionTest['error']}");
            return 1;
        }
        
        $this->line(str_repeat('-', 50));
        
        // Send actual prompt
        $this->info("3. Sending test prompt...");
        $result = $deepSeekService->ask($prompt);
        
        if ($result['success']) {
            $this->info("✅ Response received successfully!");
            $this->line("");
            $this->line("Response:");
            $this->line($result['content']);
            $this->line("");
            
            if (isset($result['usage'])) {
                $this->line("Token Usage:");
                $this->line("  Prompt tokens: {$result['usage']['prompt_tokens']}");
                $this->line("  Completion tokens: {$result['usage']['completion_tokens']}");
                $this->line("  Total tokens: {$result['usage']['total_tokens']}");
            }
        } else {
            $this->error("❌ Error: {$result['error']}");
            return 1;
        }
        
        return 0;
    }
}