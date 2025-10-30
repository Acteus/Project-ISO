# Analytics Dashboard Overhaul Plan

## Current Problems Identified

1. **Inconsistent Calculations**: Complex, nested calculations that are hard to verify
2. **Data Redundancy**: Multiple charts showing the same data in different ways
3. **Unclear Metrics**: Some metrics don't have clear origins (weekly progress, goal tracking)
4. **Over-Engineering**: Too many API endpoints for simple visualizations
5. **Poor Performance**: Multiple API calls for data that could be fetched once

## New Streamlined Approach

### Core Metrics (Single Source of Truth)

All metrics will be calculated from the `survey_responses` table with clear, simple formulas:

#### 1. **ISO 21001 Indices** (5 core dimensions)
- **Learner Needs Index** = AVG(4 curriculum/support questions)
- **Satisfaction Score** = AVG(4 teaching/environment questions)
- **Success Index** = AVG(4 academic/skills questions)
- **Safety Index** = AVG(4 safety questions)
- **Wellbeing Index** = AVG(4 health/wellbeing questions)
- **Overall Satisfaction** = Direct field value

#### 2. **Distribution Metrics**
- Responses by Grade Level (11 vs 12)
- Responses by Gender (if provided)
- Responses by Semester

#### 3. **Time-Based Trends**
- Weekly submission count
- Monthly average scores
- Semester comparisons

#### 4. **Compliance Metrics**
- Overall Compliance Score = AVG of all 5 ISO indices
- Risk Level = Based on compliance threshold (<3.5 = High, 3.5-4.0 = Medium, >4.0 = Low)

### New Dashboard Layout

```
┌─────────────────────────────────────────────────────────┐
│  FILTERS: Date Range | Grade | Semester                  │
└─────────────────────────────────────────────────────────┘

┌──────────┬──────────┬──────────┬──────────┬──────────┐
│ Total    │ Avg      │ Response │ Current  │ Compliance│
│ Responses│ Satis.   │ Rate     │ Week     │ Score     │
└──────────┴──────────┴──────────┴──────────┴──────────┘

┌─────────────────────────────────────────────────────────┐
│  ISO 21001 Performance Radar (5 dimensions)              │
└─────────────────────────────────────────────────────────┘

┌────────────────────────┬─────────────────────────────────┐
│ Grade Distribution     │ Satisfaction Trend (Time Series)│
│ (Pie Chart)            │ (Line Chart)                    │
└────────────────────────┴─────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ ISO 21001 Dimensions Comparison (Bar Chart)              │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│ Compliance Risk Assessment                               │
│ - Current Score                                          │
│ - Risk Level                                             │
│ - Recommendations                                        │
└─────────────────────────────────────────────────────────┘
```

### Simplified API Endpoints

**REMOVE:**
- `/api/visualizations/weekly-progress` (unclear data source)
- `/api/visualizations/goal-progress` (no goals defined)
- `/api/visualizations/weekly-comparison` (redundant)
- `/api/visualizations/monthly-report` (can be filtered)
- `/api/visualizations/progress-alerts` (unclear logic)
- `/api/visualizations/heat-map` (overly complex)
- `/api/visualizations/comparative-analysis` (redundant with filters)

**KEEP & SIMPLIFY:**
- `/api/analytics/summary` - Single endpoint for all dashboard data
- `/api/analytics/time-series` - Simple time-based trend
- `/api/analytics/compliance` - Risk assessment only

### Implementation Steps

1. ✅ Create new simplified AnalyticsService
2. ✅ Create new AnalyticsController with 3 endpoints
3. ✅ Redesign frontend dashboard with clear data flow
4. ✅ Remove unnecessary endpoints
5. ✅ Update routes
6. ✅ Test with real data

## Benefits

- **Clarity**: Every metric has a clear, documented source
- **Performance**: Single API call instead of 10+
- **Maintainability**: Simple calculations, easy to debug
- **Consistency**: All data from same source with same filters
- **Accuracy**: No complex nested calculations that could be wrong
