<?php

namespace Tests\Unit\Models;

use App\Models\Goal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use RefreshDatabase;

    public function test_goal_factory_creates_goal()
    {
        $goal = Goal::factory()->create();

        $this->assertDatabaseHas('goals', [
            'id' => $goal->id,
            'name' => $goal->name,
        ]);
    }

    public function test_goal_can_be_active()
    {
        $goal = Goal::factory()->active()->create();

        $this->assertEquals('active', $goal->status);
        $this->assertTrue($goal->target_date->isFuture());
    }

    public function test_goal_can_be_achieved()
    {
        $goal = Goal::factory()->achieved()->create();

        $this->assertEquals('achieved', $goal->status);
        $this->assertGreaterThanOrEqual($goal->target_value, $goal->current_value);
    }

    public function test_goal_can_be_overdue()
    {
        $goal = Goal::factory()->overdue()->create();

        $this->assertEquals('active', $goal->status);
        $this->assertTrue($goal->target_date->isPast());
        $this->assertLessThan($goal->target_value, $goal->current_value);
    }

    public function test_goal_progress_percentage_calculation()
    {
        $goal = Goal::factory()->create([
            'target_value' => 100.0,
            'current_value' => 75.0,
        ]);

        $this->assertEquals(75.0, $goal->progress_percentage);
    }

    public function test_goal_is_achieved_when_current_meets_target()
    {
        $goal = Goal::factory()->create([
            'target_value' => 100.0,
            'current_value' => 100.0,
        ]);

        $this->assertTrue($goal->is_achieved);
    }

    public function test_goal_is_overdue_when_past_date_and_not_achieved()
    {
        $goal = Goal::factory()->create([
            'target_value' => 100.0,
            'current_value' => 50.0,
            'target_date' => now()->subDays(10),
            'status' => 'active',
        ]);

        $this->assertTrue($goal->is_overdue);
    }

    public function test_goal_priority_label()
    {
        $goal = Goal::factory()->create(['priority' => 1]);
        $this->assertEquals('Low', $goal->priority_label);

        $goal = Goal::factory()->create(['priority' => 2]);
        $this->assertEquals('Medium', $goal->priority_label);

        $goal = Goal::factory()->create(['priority' => 3]);
        $this->assertEquals('High', $goal->priority_label);

        $goal = Goal::factory()->create(['priority' => 4]);
        $this->assertEquals('Critical', $goal->priority_label);
    }

    public function test_goal_status_label()
    {
        $goal = Goal::factory()->create(['status' => 'active']);
        $this->assertEquals('Active', $goal->status_label);

        $goal = Goal::factory()->create(['status' => 'achieved']);
        $this->assertEquals('Achieved', $goal->status_label);

        $goal = Goal::factory()->create(['status' => 'expired']);
        $this->assertEquals('Expired', $goal->status_label);
    }

    public function test_goal_update_progress_adds_to_history()
    {
        $goal = Goal::factory()->create([
            'current_value' => 50.0,
            'progress_history' => [],
        ]);

        $goal->updateProgress(75.0, 'Made progress');

        $this->assertEquals(75.0, $goal->current_value);
        $this->assertCount(1, $goal->progress_history);
        $this->assertEquals(50.0, $goal->progress_history[0]['old_value']);
        $this->assertEquals(75.0, $goal->progress_history[0]['new_value']);
    }

    public function test_goal_status_changes_to_achieved_when_target_met()
    {
        $goal = Goal::factory()->create([
            'target_value' => 100.0,
            'current_value' => 90.0,
            'status' => 'active',
        ]);

        $goal->updateProgress(100.0);

        $this->assertEquals('achieved', $goal->status);
    }

    public function test_goal_scopes()
    {
        // Clear any existing goals
        Goal::query()->delete();

        // Create goals with explicit dates to avoid timing issues
        $activeGoal = Goal::factory()->create([
            'status' => 'active',
            'target_date' => now()->addMonths(6), // Definitely in the future
            'target_value' => 100.0,
            'current_value' => 50.0,
        ]);

        $achievedGoal = Goal::factory()->create([
            'status' => 'achieved',
            'target_date' => now()->subMonths(3), // In the past
            'target_value' => 100.0,
            'current_value' => 110.0, // Exceeds target
        ]);

        $overdueGoal = Goal::factory()->create([
            'status' => 'active',
            'target_date' => now()->subWeeks(2), // In the past
            'target_value' => 100.0,
            'current_value' => 50.0, // Less than target
        ]);

        // active() scope returns all goals with status='active' (both active and overdue goals are active)
        // So we should have 2 active goals: the future one and the overdue one
        $this->assertCount(2, Goal::active()->get());
        $this->assertCount(1, Goal::achieved()->get());
        // overdue() scope returns active goals that are past due and not achieved
        $this->assertCount(1, Goal::overdue()->get());
    }
}

