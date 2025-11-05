<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AssessmentResponseController extends Controller
{
    /**
     * Get all assessments
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssessments(): JsonResponse
    {
        try {
            $assessments = Assessment::all(['id', 'title', 'description', 'form_definition']);

            return response()->json($assessments);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve assessments.', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Could not retrieve assessments.'], 500);
        }
    }

    /**
     * Store user's assessment responses
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'responses' => 'required|array',
                'responses.*.assessment_id' => 'required|exists:assessment,id',
                'responses.*.selected_options' => 'required|array',
                'attempt_id' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $responses = $request->input('responses');
            $attemptId = $request->input('attempt_id');
            $userId = Auth::id();

            // If no attempt_id provided, generate one
            if (!$attemptId) {
                $attemptId = DB::table('assessment_responses')
                    ->where('user_id', $userId)
                    ->max('attempt_id') + 1;
            }

            DB::transaction(function () use ($responses, $userId, $attemptId) {
                foreach ($responses as $responseData) {
                    AssessmentResponse::create([
                        'user_id' => $userId,
                        'attempt_id' => $attemptId,
                        'assessment_id' => $responseData['assessment_id'],
                        'selected_options' => json_encode($responseData['selected_options']),
                    ]);
                }
            });

            return response()->json([
                'message' => 'Assessment responses saved successfully',
                'attempt_id' => $attemptId
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to store assessment responses.', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'An error occurred while saving responses.'], 500);
        }
    }

    /**
     * Get user's assessment responses
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserResponses(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $attemptId = $request->query('attempt_id');

            $query = AssessmentResponse::where('user_id', $userId)
                ->with('assessment:id,title,description');

            if ($attemptId) {
                $query->where('attempt_id', $attemptId);
            }

            $responses = $query->get();

            $formattedResponses = $responses->map(function ($response) {
                return [
                    'id' => $response->id,
                    'assessment_id' => $response->assessment_id,
                    'assessment_title' => $response->assessment->title ?? null,
                    'attempt_id' => $response->attempt_id,
                    'selected_options' => $response->selected_options,
                    'created_at' => $response->created_at,
                ];
            });

            return response()->json($formattedResponses);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user assessment responses.', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Could not retrieve responses.'], 500);
        }
    }

    /**
     * Get all attempts for the current user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserAttempts(): JsonResponse
    {
        try {
            $userId = Auth::id();

            $attempts = AssessmentResponse::where('user_id', $userId)
                ->select('attempt_id', DB::raw('MIN(created_at) as created_at'))
                ->groupBy('attempt_id')
                ->orderBy('attempt_id', 'desc')
                ->get();

            return response()->json($attempts);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user attempts.', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Could not retrieve attempts.'], 500);
        }
    }
}
