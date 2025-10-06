<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenAIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('openai.index');
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $request->message],
                ],
            ]);

            if ($response->failed()) {
                return response()->json([
                    'reply' => '⚠️ OpenAI API request failed: ' . $response->body()
                ]);
            }

            $result = $response->json();

            if (isset($result['choices'][0]['message']['content'])) {
                $reply = $result['choices'][0]['message']['content'];
            } else {
                $reply = '⚠️ No content in OpenAI response.';
            }

        } catch (\Exception $e) {
            $reply = '⚠️ Exception: ' . $e->getMessage();
        }

        return response()->json(['reply' => $reply]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}