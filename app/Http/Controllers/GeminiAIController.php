<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeminiAIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('geminiai.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function generateAnawers(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'type' => 'required|string|max:100'
        ]);
        $productType = $request->input('type');
        $prompt = "{$productType}.";
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . env('GEMINI_API_KEY'), [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ]
                    ]
                ]
            ]
        ]);
        $result = $response->json();

        // dd($result);
        
        $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'No result found.';
        return view('geminiai.index', [
            'responseresult' => $text,
            'type' => $productType
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function ajaxresponseanswers(Request $request)
{
    $request->validate([
        'type' => 'required|string|max:500'
    ]);
    
    $productType = $request->input('type');
    $prompt = "{$productType}.";
    
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . env('GEMINI_API_KEY'), [
        'contents' => [
            [
                'parts' => [
                    [
                        'text' => $prompt
                    ]
                ]
            ]
        ]
    ]);
    
    $result = $response->json();
    $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'No result found.';
    
    // Return JSON response for AJAX
    return response()->json([
        'success' => true,
        'response' => $text
    ]);
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