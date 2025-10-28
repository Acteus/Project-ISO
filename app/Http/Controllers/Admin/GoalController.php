<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GoalController extends Controller
{
    /**
     * Display a listing of goals
     */
    public function index()
    {
        $goals = Goal::orderBy('priority', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.goals.index', compact('goals'));
    }

    /**
     * Show the form for creating a new goal
     */
    public function create()
    {
        return view('admin.goals.create');
    }

    /**
     * Store a newly created goal
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'metric_type' => 'required|string|in:satisfaction,compliance,responses,safety,wellbeing,learner_needs,success',
            'target_value' => 'required|numeric|min:0|max:100',
            'target_date' => 'nullable|date|after:today',
            'priority' => 'required|integer|min:1|max:4',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        Goal::create($request->all());

        return redirect()->route('admin.goals.index')
                        ->with('success', 'Goal created successfully.');
    }

    /**
     * Display the specified goal
     */
    public function show(Goal $goal)
    {
        return view('admin.goals.show', compact('goal'));
    }

    /**
     * Show the form for editing the specified goal
     */
    public function edit(Goal $goal)
    {
        return view('admin.goals.edit', compact('goal'));
    }

    /**
     * Update the specified goal
     */
    public function update(Request $request, Goal $goal)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'metric_type' => 'required|string|in:satisfaction,compliance,responses,safety,wellbeing,learner_needs,success',
            'target_value' => 'required|numeric|min:0|max:100',
            'target_date' => 'nullable|date',
            'status' => 'required|string|in:active,achieved,expired,cancelled',
            'priority' => 'required|integer|min:1|max:4',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $goal->update($request->all());

        return redirect()->route('admin.goals.index')
                        ->with('success', 'Goal updated successfully.');
    }

    /**
     * Remove the specified goal
     */
    public function destroy(Goal $goal)
    {
        $goal->delete();

        return redirect()->route('admin.goals.index')
                        ->with('success', 'Goal deleted successfully.');
    }

    /**
     * Update goal progress
     */
    public function updateProgress(Request $request, Goal $goal)
    {
        $validator = Validator::make($request->all(), [
            'current_value' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $goal->updateProgress($request->current_value, $request->notes);

        return response()->json([
            'success' => true,
            'message' => 'Goal progress updated successfully.',
            'goal' => $goal
        ]);
    }

    /**
     * Get goals data for API
     */
    public function apiIndex(Request $request)
    {
        $query = Goal::query();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by metric type
        if ($request->has('metric_type') && $request->metric_type !== 'all') {
            $query->where('metric_type', $request->metric_type);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        $goals = $query->orderBy('priority', 'desc')
                      ->orderBy('created_at', 'desc')
                      ->get();

        return response()->json([
            'success' => true,
            'data' => $goals
        ]);
    }
}
