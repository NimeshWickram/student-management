<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;

class BotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $userMessage = $request->input('message');

        // Build context
        $studentCount = Student::count();
        $teacherCount = Teacher::count();
        $subjectCount = Subject::count();
        $subjects = Subject::pluck('name')->implode(', ');

        $systemPrompt = "You are a helpful and friendly AI assistant for the CodeXpress student management system. "
            . "Use the following context to answer the user's questions:\n"
            . "- There are currently $studentCount students registered.\n"
            . "- There are currently $teacherCount teachers active.\n"
            . "- There are $subjectCount subjects available: $subjects.\n"
            . "If the user asks about something outside this context, let them know you primarily assist with CodeXpress data. "
            . "Keep your answers concise and professional.";

        $url = 'https://integrate.api.nvidia.com/v1/chat/completions';
        $apiKey = config('services.nvidia.key');
        
        // I'll use the user specified model but they can change it via .env
        $model = config('services.nvidia.model', 'nvidia/nemotron-3-content-safety'); 
        if (!$apiKey) {
            return response()->json(['reply' => 'Bot configuration is incomplete. API key is missing.'], 500);
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json'
            ])->post($url, [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage]
                ],
                'max_tokens' => 512,
                'temperature' => 0.20,
                'top_p' => 0.70,
                'stream' => false
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? "I'm sorry, I couldn't generate a response.";
                return response()->json(['reply' => $reply]);
            } else {
                return response()->json(['reply' => 'Error communicating with the AI service: ' . $response->body()], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['reply' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
