<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\QuestionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\QuestionResource;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuestionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Question::query()
            ->answered()
            ->with(['topic', 'latestAnswer.user'])
            ->latest();

        if ($request->filled('topic')) {
            $query->whereHas('topic', function ($q) use ($request) {
                $q->where('slug', $request->query('topic'))
                    ->orWhere('id', $request->query('topic'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $perPage = min((int) $request->query('per_page', 10), 50);

        return QuestionResource::collection($query->paginate($perPage));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'topic_id' => ['required', 'exists:question_topics,id'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:25'],
            'subject' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        $question = Question::create([
            ...$validated,
            'status' => QuestionStatus::Pending,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Pertanyaan Anda berhasil dikirim dan akan segera diproses oleh petugas.',
            'data' => [
                'id' => $question->id,
                'subject' => $question->subject,
                'created_at' => $question->created_at->toIso8601String(),
            ],
        ], 201);
    }
}
