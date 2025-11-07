<?php

namespace Tests\Unit\Models;

use App\Models\WeeklyMetric;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeeklyMetricTest extends TestCase
{
    use RefreshDatabase;

    public function test_weekly_metric_factory_creates_metric()
    {
        $metric = WeeklyMetric::factory()->create();

        $this->assertDatabaseHas('weekly_metrics', [
            'id' => $metric->id,
            'year' => $metric->year,
        ]);
    }

    public function test_weekly_metric_all_targets_met()
    {
        $metric = WeeklyMetric::factory()->allTargetsMet()->create();

        $this->assertTrue($metric->satisfaction_target_met);
        $this->assertTrue($metric->compliance_target_met);
        $this->assertTrue($metric->response_target_met);
        $this->assertTrue($metric->all_targets_met);
    }

    public function test_weekly_metric_no_targets_met()
    {
        $metric = WeeklyMetric::factory()->noTargetsMet()->create();

        $this->assertFalse($metric->satisfaction_target_met);
        $this->assertFalse($metric->compliance_target_met);
        $this->assertFalse($metric->response_target_met);
        $this->assertFalse($metric->all_targets_met);
    }

    public function test_weekly_metric_for_specific_week()
    {
        $metric = WeeklyMetric::factory()->forWeek(2024, 10)->create();

        $this->assertEquals(2024, $metric->year);
        $this->assertEquals(10, $metric->week_number);
    }

    public function test_weekly_metric_week_label()
    {
        $metric = WeeklyMetric::factory()->forWeek(2024, 10)->create();

        $this->assertEquals('Week 10, 2024', $metric->week_label);
    }

    public function test_weekly_metric_date_range_label()
    {
        $metric = WeeklyMetric::factory()->create([
            'week_start_date' => '2024-03-04',
            'week_end_date' => '2024-03-10',
        ]);

        $this->assertStringContainsString('Mar', $metric->date_range_label);
        $this->assertStringContainsString('2024', $metric->date_range_label);
    }

    public function test_weekly_metric_casts()
    {
        $metric = WeeklyMetric::factory()->create([
            'responses_by_track' => ['CSS' => 10],
            'responses_by_grade' => [11 => 5, 12 => 5],
            'key_insights' => ['Insight 1'],
            'recommendations' => ['Recommendation 1'],
        ]);

        $this->assertIsArray($metric->responses_by_track);
        $this->assertIsArray($metric->responses_by_grade);
        $this->assertIsArray($metric->key_insights);
        $this->assertIsArray($metric->recommendations);
        $this->assertIsInt($metric->year);
        $this->assertIsInt($metric->week_number);
    }

    public function test_weekly_metric_scopes()
    {
        WeeklyMetric::factory()->forWeek(2024, 10)->create();
        WeeklyMetric::factory()->forWeek(2024, 11)->create();
        WeeklyMetric::factory()->forWeek(2023, 10)->create();

        $this->assertCount(2, WeeklyMetric::byYear(2024)->get());
        $this->assertCount(2, WeeklyMetric::byWeek(10)->get());
        $this->assertCount(3, WeeklyMetric::recent(12)->get());
    }

    public function test_weekly_metric_decimal_fields()
    {
        $metric = WeeklyMetric::factory()->create([
            'learner_needs_index' => 4.25,
            'satisfaction_score' => 4.50,
            'compliance_percentage' => 85.75,
        ]);

        $this->assertEquals(4.25, $metric->learner_needs_index);
        $this->assertEquals(4.50, $metric->satisfaction_score);
        $this->assertEquals(85.75, $metric->compliance_percentage);
    }
}


