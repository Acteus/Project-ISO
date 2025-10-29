# Flask AI Service Integration for ISO 21001 Quality Education

This document describes the integration of a Python Flask AI microservice with 8 advanced machine learning models into the Laravel-based ISO 21001 Quality Education system.

## Overview

The Flask AI service provides comprehensive machine learning capabilities including:
- **8 Specialized ML Models**: From compliance prediction to dropout risk assessment
- **Deep Learning**: TensorFlow-powered models for complex predictions
- **Advanced NLP**: Sentiment analysis with scikit-learn and TF-IDF vectorization
- **Time Series Forecasting**: ARIMA models for satisfaction trend analysis
- **Student Segmentation**: K-Means and DBSCAN clustering algorithms
- **Robust Fallback**: Automatic failover to PHP-ML when Flask is unavailable
- **Real-time Dashboard**: Interactive AI insights dashboard at `/admin/ai-insights`

## Architecture

```
┌──────────────────┐    HTTP/REST    ┌───────────────────────┐
│   Laravel App    │◄───────────────►│  Flask AI Service     │
│                  │                 │  (Python ML Stack)    │
│ - Controllers    │                 │                       │
│ - AIService.php  │                 │ - 8 ML Models:        │
│ - FlaskAIClient  │                 │   • Compliance        │
│ - Views/Blade    │                 │   • Sentiment         │
│ - Database       │                 │   • Clustering        │
│                  │                 │   • Performance       │
│                  │                 │   • Dropout Risk      │
│                  │                 │   • Risk Assessment   │
│                  │                 │   • Trend Analysis    │
│                  │                 │   • Predictive        │
└──────────────────┘                 └───────────────────────┘
         │                                     │
         └────► Fallback to PHP-ML ◄───────────┘
         (When Flask unavailable)
```

## Key Features

### 1. Advanced Compliance Prediction
- **Deep Learning Model**: TensorFlow-based neural network for ISO 21001 compliance
- **Weighted Scoring**: Aligned with ISO 21001 standard requirements
- **Risk Level Classification**: Low, Medium, High risk categorization
- **Confidence Scoring**: Prediction confidence with probability distributions
- **Real-time Analysis**: <500ms average response time
- **PHP Fallback**: Rule-based implementation when Flask unavailable

### 2. Enhanced Sentiment Analysis
- **NLP Pipeline**: scikit-learn with TF-IDF vectorization
- **Multi-class Classification**: Positive, Neutral, Negative sentiment
- **Batch Processing**: Analyze multiple comments simultaneously
- **Probability Distribution**: Confidence scores for each sentiment class
- **Keyword Extraction**: Identify key themes in student feedback

### 3. Intelligent Student Clustering
- **Multiple Algorithms**: K-Means and DBSCAN for different scenarios
- **Risk Profiling**: Automatic risk level assignment per cluster
- **Intervention Targeting**: Identify groups needing specific support
- **Silhouette Scoring**: Validate cluster quality
- **Detailed Analytics**: Per-cluster characteristics and insights

### 4. Performance & Dropout Prediction
- **Gradient Boosting**: Advanced ensemble methods for performance prediction
- **Random Forest**: Dropout risk classification with feature importance
- **Early Warning System**: Proactive identification of at-risk students
- **Intervention Urgency**: Prioritized recommendations
- **Multi-factor Analysis**: Considers attendance, satisfaction, performance

### 5. Comprehensive Risk Assessment
- **Multi-dimensional**: Evaluates 5+ ISO 21001 dimensions simultaneously
- **Compliance Impact**: Links risk to ISO 21001 requirements
- **Actionable Insights**: Specific intervention recommendations
- **Risk Breakdown**: Granular analysis by category

### 6. Satisfaction Trend Analysis
- **Time Series Forecasting**: ARIMA models for trend prediction
- **3-Month Forecasts**: Forward-looking satisfaction projections
- **Trend Strength**: Quantified trend magnitude
- **Pattern Recognition**: Identify cyclical patterns

### 7. Real-time AI Insights Dashboard
- **Interactive UI**: Located at `/admin/ai-insights`
- **Live Metrics**: 6 key performance indicators updated in real-time
- **8 Analysis Tools**: One-click access to all AI models
- **Visual Results**: Color-coded confidence badges and risk indicators
- **Data Range Display**: Shows analysis scope and date ranges

### 8. Robust Error Handling
- **Circuit Breaker Pattern**: Prevents cascade failures
- **Automatic Fallback**: Seamless switch to PHP implementations
- **Retry Mechanisms**: Exponential backoff for transient failures
- **Comprehensive Logging**: Detailed logs for monitoring and debugging

## Installation & Setup

### 1. Python Environment Setup

```bash
# Navigate to ai-service directory
cd ai-service

# Create virtual environment
python -m venv venv

# Activate environment
source venv/bin/activate  # Linux/Mac
# or
venv\Scripts\activate     # Windows

# Install dependencies
pip install -r requirements.txt
```

### 2. Docker Deployment (Recommended)

```bash
# Build and run with Docker Compose
cd ai-service
docker-compose up --build -d

# Service will be available at http://localhost:5002
```

### 3. Laravel Configuration

Add to your `.env` file:

```env
# Flask AI Service
FLASK_AI_SERVICE_URL=http://localhost:5002
FLASK_AI_API_KEY=your-optional-api-key
AI_TIMEOUT_SECONDS=30
AI_MAX_RETRIES=3
AI_ENABLE_CACHE=true
AI_FALLBACK_TO_PHP=true

# Model Configuration
AI_COMPLIANCE_MODEL_ENABLED=true
AI_SENTIMENT_MODEL_ENABLED=true
AI_CLUSTER_MODEL_ENABLED=true
```

## API Endpoints

### Flask Service Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/health` | GET | Service health check |
| `/api/v1/compliance/predict` | POST | Compliance prediction |
| `/api/v1/sentiment/analyze` | POST | Sentiment analysis |
| `/api/v1/students/cluster` | POST | Student clustering |
| `/api/v1/analytics/comprehensive` | POST | Combined analytics |

### Laravel Integration

The existing `AIService` class automatically uses Flask when available:

```php
// Automatically uses Flask service with PHP fallback
$result = app(AIService::class)->predictCompliance($data);

// Check service status
$status = app(FlaskAIClient::class)->getServiceStatus();
```

## Testing

### Command Line Testing

```bash
# Test service availability
php artisan ai:test-flask --service-only

# Test compliance prediction
php artisan ai:test-flask --compliance

# Test sentiment analysis
php artisan ai:test-flask --sentiment

# Run all tests
php artisan ai:test-flask
```

### Manual Testing

```bash
# Start Flask service
cd ai-service && python app.py

# Test health endpoint
curl http://localhost:5001/health

# Test compliance prediction
curl -X POST http://localhost:5001/api/v1/compliance/predict \
  -H "Content-Type: application/json" \
  -d '{"learner_needs_index": 4.2, "satisfaction_score": 3.8, "success_index": 4.1, "safety_index": 4.5, "wellbeing_index": 3.9, "overall_satisfaction": 4.0}'
```

## Performance Benchmarks

| Operation | Flask Service | PHP Fallback | Target |
|-----------|---------------|--------------|--------|
| Compliance Prediction | <500ms | <200ms | <2s |
| Sentiment Analysis | <800ms | <300ms | <2s |
| Student Clustering | <2s | <1s | <5s |
| Service Health Check | <100ms | N/A | <500ms |

## Monitoring & Maintenance

### Health Monitoring

```php
// Check service status programmatically
$client = app(FlaskAIClient::class);
$status = $client->getServiceStatus();

// Returns: ['available' => true/false, 'base_url' => '...', ...]
```

### Logs

- **Laravel Logs**: `/storage/logs/laravel.log`
- **Flask Logs**: `ai-service/logs/ai_service.log`
- **Performance Metrics**: Available via Laravel Telescope (if installed)

### Model Training

Models can be retrained with new data:

```python
from ai_models.compliance_predictor import CompliancePredictor

predictor = CompliancePredictor()
result = predictor.train(X_train, y_train, epochs=100)
```

## Troubleshooting

### Common Issues

1. **Flask Service Not Available**
   - Check if Docker container is running: `docker ps`
   - Verify port 5000 is not blocked
   - Check Flask logs: `docker logs ai-service`

2. **Slow Response Times**
   - Enable caching in Laravel config
   - Check network latency between services
   - Monitor resource usage on Flask container

3. **Model Accuracy Issues**
   - Retrain models with more recent data
   - Adjust hyperparameters in model classes
   - Validate input data preprocessing

### Fallback Behavior

When Flask service is unavailable, the system automatically falls back to PHP implementations:

- ✅ **Compliance Prediction**: Rule-based weighted scoring
- ✅ **Sentiment Analysis**: Keyword-based analysis
- ✅ **Student Clustering**: K-Means with PHP-ML library

## Security Considerations

- **API Authentication**: Optional API key support
- **Input Validation**: Comprehensive data validation
- **Rate Limiting**: Configurable request limits
- **HTTPS**: Recommended for production deployments

## Deployment

### Production Docker Setup

```yaml
# docker-compose.prod.yml
version: '3.8'
services:
  ai-service:
    build: .
    environment:
      - FLASK_ENV=production
      - FLASK_DEBUG=false
    deploy:
      resources:
        limits:
          memory: 2G
          cpus: '1.0'
```

### Scaling Considerations

- **Horizontal Scaling**: Multiple Flask service instances behind load balancer
- **Caching Layer**: Redis for API response caching
- **Database**: Shared database for model training data
- **Monitoring**: Prometheus/Grafana for metrics collection

## Future Enhancements

- **Model Auto-training**: Automated model retraining pipelines
- **A/B Testing**: Compare Flask vs PHP implementations
- **Advanced NLP**: Integration with transformer models (BERT, GPT)
- **Real-time Learning**: Online learning capabilities
- **Multi-language Support**: Support for multiple languages

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review logs in both Laravel and Flask services
3. Test individual components using the artisan commands
4. Ensure all dependencies are properly installed

## License

This integration is part of the ISO Quality Education system and follows the same licensing terms.
