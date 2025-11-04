<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnswerRequest;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnswerController extends Controller
{

    // Retrieve all questions for the assessment.
    // @return \Illuminate\Http\JsonResponse

    public function getQuestions(): JsonResponse
    {
        try {
            // The questions table stores the question text in the 'question' column.
            $questions = Question::all(['id', 'question', 'options'])->map(function ($q) {
                return [
                    'id' => $q->id,
                    // Return 'question' key to match frontend expectations.
                    'question' => $q->question,
                    'options' => $q->options,
                ];
            });

            return response()->json($questions);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve questions.', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Could not retrieve questions.'], 500);
        }
    }


    // Store the user's answers.
    // @param  \App\Http\Requests\StoreAnswerRequest  $request
    // @return \Illuminate\Http\JsonResponse

    public function store(StoreAnswerRequest $request): JsonResponse
    {
        try {
            $answers = $request->validated()['answers'];
            $userId = Auth::id();

            $authUser = Auth::user();
            $userEmail = $authUser ? $authUser->email : null;

            // Move processing into a dedicated method to keep this method simple.
            $this->processAnswers($answers, $userId, $userEmail);

            return response()->json(['message' => 'Assessment answers saved successfully'], 201);
        } catch (\Exception $e) {
            Log::error('Failed to store answers.', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'An error occurred while saving answers.'], 500);
        }
    }

    /**
     * Process and persist answers inside a DB transaction. Kept as a separate
     * method so the public `store()` remains short and readable.
     *
     * @param array $answers
     * @param int|null $userId
     * @param string|null $userEmail
     * @return void
     */
    private function processAnswers(array $answers, $userId, $userEmail): void
    {
        DB::transaction(function () use ($answers, $userId, $userEmail) {
            $collectedWords = [];

            foreach ($answers as $answerData) {
                [$questionId, $questionText] = $this->resolveQuestion($answerData);

                if (! $questionId && ! $questionText) {
                    continue;
                }

                $attributes = ['user_id' => $userId];
                $values = ['answer' => json_encode($answerData['answer'])];

                if ($questionId) {
                    $attributes['question_id'] = $questionId;
                    $values['question_id'] = $questionId;
                }

                if ($userEmail) {
                    $values['email'] = $userEmail;
                }

                Answer::updateOrCreate($attributes, $values);

                $this->collectWordsFromAnswer($answerData, $collectedWords);
            }

            if ($userEmail && ! empty($collectedWords)) {
                $unique = array_values(array_unique($collectedWords));

                DB::table('input')->updateOrInsert(
                    ['email' => $userEmail],
                    [
                        'self_words' => json_encode($unique),
                        'concept_words' => json_encode($unique),
                    ]
                );
            }
        });
    }

    /**
     * Resolve question id and text from an incoming answer payload.
     * Returns an array: [questionId|null, questionText|null]
     */
    private function resolveQuestion(array $answerData): array
    {
        $questionId = null;
        $questionText = null;

        if (isset($answerData['question_id'])) {
            $q = Question::find($answerData['question_id']);
            if ($q) {
                $questionId = $q->id;
                $questionText = $q->question ?? $q->text ?? null;
            }
        }

        if (! $questionId && isset($answerData['question'])) {
            $questionText = $answerData['question'];
            $questionId = Question::where('question', $questionText)->value('id')
                ?: Question::where('text', $questionText)->value('id');
        }

        return [$questionId, $questionText];
    }

    /**
     * Collect non-empty tokens from the answer and append them into the collector array.
     */
    private function collectWordsFromAnswer(array $answerData, array &$collector): void
    {
        if (empty($answerData['answer'])) {
            return;
        }

        foreach ((array) $answerData['answer'] as $token) {
            $token = trim((string) $token);
            if ($token !== '') {
                $collector[] = $token;
            }
        }
    }


    // Retrieve the authenticated user's saved answers.
    // @return \Illuminate\Http\JsonResponse

    public function getUserAnswers(): JsonResponse
    {
        try {
            $userId = Auth::id();
            $authUser = Auth::user();
            $answers = Answer::where('user_id', $userId)->get();

            // Transform the data for a consistent API response. The answers table stores the
            // question as text in the 'question' column. Resolve the question's id when possible
            // so the frontend can correlate by question_id.
            $formattedAnswers = $answers->map(function ($answer) use ($authUser) {
                // Prefer explicit stored question_id. The 'question' text column is
                // deprecated and removed from the schema; rely on question_id.
                $questionId = $answer->question_id ?? null;

                return [
                    'question_id' => $questionId,
                    'answer' => json_decode($answer->answer, true),
                    'email' => $answer->email ?? ($authUser ? $authUser->email : null),
                ];
            });

            return response()->json($formattedAnswers);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user answers.', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Could not retrieve user answers.'], 500);
        }
    }
}
