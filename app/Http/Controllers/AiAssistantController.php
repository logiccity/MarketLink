<?php

namespace App\Http\Controllers;

use App\Services\AiAssistantService;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    public function __construct(protected AiAssistantService $aiService)
    {
    }

    public function ask(Request $request)
    {
        $request->validate([
            'prompt' => ['required', 'string', 'max:500'],
        ]);

        $result = $this->aiService->ask($request->input('prompt'));
        $rawReply = $result['reply'] ?? '';

        $formatted = htmlspecialchars($rawReply, ENT_QUOTES, 'UTF-8');
        $formatted = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $formatted);
        $formatted = preg_replace('/\_(.*?)\_/', '<em>$1</em>', $formatted);

        $formatted = preg_replace_callback('/\[(.*?)\]\((https?:\/\/[^\s\)]+|\/[^\s\)]+)\)/', function ($matches) {
            $text = $matches[1];
            $url = htmlspecialchars_decode($matches[2]);
            return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" class="btn btn-sm btn-outline-success py-1 px-3 rounded-pill mt-1 d-inline-block text-decoration-none fw-bold" style="font-size:0.78rem;">' . $text . ' &rarr;</a>';
        }, $formatted);

        $formatted = nl2br($formatted);
        if (!empty($result['suggestions'])) {
            $formatted .= '<div class="d-flex flex-wrap gap-1 mt-3 pt-2 border-top">';
            foreach ($result['suggestions'] as $sug) {
                $safeSug = htmlspecialchars($sug, ENT_QUOTES, 'UTF-8');
                $formatted .= '<button type="button" class="btn btn-sm btn-light border py-1 px-2 rounded-pill text-start" style="font-size: 0.75rem;" onclick="sendAiPrompt(\'' . addslashes($safeSug) . '\')">💡 ' . $safeSug . '</button>';
            }
            $formatted .= '</div>';
        }

        return response()->json([
            'success' => true,
            'response' => $formatted,
            'reply' => $formatted,
            'suggestions' => $result['suggestions'] ?? [],
        ]);
    }
}
