# ISO 21001 Risk Levels Overhaul Plan

## Current State Analysis

### Existing Risk Level Definitions
- **Low Risk**: ≥ 4.2 compliance score
- **Medium Risk**: ≥ 3.5 compliance score
- **High Risk**: < 3.5 compliance score

### Files Using Risk Levels
1. `config/ai.php` - Risk thresholds configuration
2. `app/Console/Commands/AggregateWeeklyMetrics.php` - Weekly metrics calculation
3. `app/Services/VisualizationService.php` - Risk meter generation
4. `ai-service/ai_models/compliance_predictor.py` - AI compliance prediction
5. `ai-service/ai_models/risk_assessment_predictor.py` - Risk assessment AI
6. `ai-service/ai_models/student_performance_predictor.py` - Performance prediction
7. `ai-service/ai_models/dropout_risk_predictor.py` - Dropout risk assessment
8. Frontend views (analytics, reports, emails) - Display logic
9. Test files - Expected values

## Proposed New Risk Level Definitions

Based on ISO 21001 standards and user feedback:

- **Low Risk**: ≥ 4.5 compliance score (High compliance, minimal risk)
- **Medium Risk**: 3.0 - 4.5 compliance score (Moderate compliance, acceptable risk)
- **High Risk**: < 3.0 compliance score (Low compliance, significant risk)

## Implementation Plan

### Phase 1: Configuration Updates
1. Update `config/ai.php` risk thresholds
2. Update `app/Console/Commands/AggregateWeeklyMetrics.php` calculation logic
3. Update `app/Services/VisualizationService.php` risk meter logic

### Phase 2: AI Model Updates
1. Update `ai-service/ai_models/compliance_predictor.py` thresholds
2. Update `ai-service/ai_models/risk_assessment_predictor.py` logic
3. Update `ai-service/ai_models/student_performance_predictor.py` risk levels
4. Update `ai-service/ai_models/dropout_risk_predictor.py` risk categories

### Phase 3: Frontend Updates
1. Update JavaScript risk level calculations in analytics views
2. Update email templates with new threshold displays
3. Update dashboard risk level indicators

### Phase 4: Testing & Validation
1. Update test files with new expected risk level values
2. Verify consistency across all calculations
3. Test with sample data to ensure proper categorization

## Risk Level Calculation Logic

### Compliance Score Based Risk Assessment
```php
if ($complianceScore >= 4.5) {
    $riskLevel = 'Low';
} elseif ($complianceScore >= 3.0) {
    $riskLevel = 'Medium';
} else {
    $riskLevel = 'High';
}
```

### AI Model Risk Level Mapping
- **High Compliance (≥4.5)** → Low Risk
- **Moderate Compliance (3.0-4.5)** → Medium Risk
- **Low Compliance (<3.0)** → High Risk

## Files to Modify

### PHP Backend Files
- `config/ai.php`
- `app/Console/Commands/AggregateWeeklyMetrics.php`
- `app/Services/VisualizationService.php`
- `app/Http/Controllers/AIController.php`

### AI Service Files
- `ai-service/ai_models/compliance_predictor.py`
- `ai-service/ai_models/risk_assessment_predictor.py`
- `ai-service/ai_models/student_performance_predictor.py`
- `ai-service/ai_models/dropout_risk_predictor.py`

### Frontend Files
- `resources/views/analytics/index.blade.php`
- `resources/views/admin/reports.blade.php`
- `resources/views/emails/weekly-progress-report.blade.php`
- `resources/views/emails/monthly-compliance-report.blade.php`

### Test Files
- `tests/Feature/ISO21001ComplianceTest.php`

## Validation Criteria

1. **Consistency**: All files use the same risk level thresholds
2. **ISO 21001 Compliance**: Thresholds align with standards
3. **User Clarity**: Risk levels are clearly defined and communicated
4. **Backward Compatibility**: Existing data remains valid

## Testing Strategy

1. Unit tests for risk level calculations
2. Integration tests with AI models
3. Frontend display validation
4. End-to-end testing with sample data

## Rollback Plan

If issues arise:
1. Revert configuration changes
2. Update AI models to previous thresholds
3. Restore frontend logic
4. Re-run data aggregation

## Success Metrics

- Risk levels accurately reflect compliance scores
- Consistent application across all system components
- Clear communication to stakeholders
- Improved compliance monitoring effectiveness
