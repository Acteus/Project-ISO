# Flask AI Service for ISO 21001 Quality Education

Advanced Python Flask service providing machine learning capabilities for ISO 21001 compliance prediction, sentiment analysis, and student segmentation.

## Features

- **Compliance Prediction**: Deep learning model for ISO 21001 compliance assessment
- **Sentiment Analysis**: NLP-powered analysis of student feedback
- **Student Clustering**: Machine learning-based student segmentation for targeted interventions
- **Performance Prediction**: ML-based academic performance forecasting
- **Dropout Risk Prediction**: Early warning system for student retention
- **Risk Assessment**: Comprehensive risk scoring across multiple ISO 21001 dimensions
- **Satisfaction Trend Analysis**: Time series analysis of satisfaction trends
- **RESTful API**: Clean API endpoints for Laravel integration
- **Docker Support**: Containerized deployment with docker-compose
- **Fallback Mechanisms**: Graceful degradation when models are unavailable

## Quick Start

### Using Docker (Recommended)

```bash
# Clone and navigate to ai-service directory
cd ai-service

# Build and start the service
docker-compose up --build

# Service will be available at http://localhost:5000
```

### Manual Setup

```bash
# Create virtual environment
python -m venv venv
source venv/bin/activate  # On Windows: venv\Scripts\activate

# Install dependencies
pip install -r requirements.txt

# Copy environment file
cp .env.example .env

# Run the service
python app.py
```

## API Endpoints

### Health Check
```
GET /health
```

### Compliance Prediction
```
POST /api/v1/compliance/predict
Content-Type: application/json

{
  "learner_needs_index": 4.2,
  "satisfaction_score": 3.8,
  "success_index": 4.1,
  "safety_index": 4.5,
  "wellbeing_index": 3.9,
  "overall_satisfaction": 4.0
}
```

### Sentiment Analysis
```
POST /api/v1/sentiment/analyze
Content-Type: application/json

{
  "comments": ["Great teaching quality!", "Need more support"]
}
```

### Student Clustering
```
POST /api/v1/students/cluster
Content-Type: application/json

{
  "responses": [...],
  "clusters": 3
}
```

### Performance Prediction
```
POST /api/v1/performance/predict
Content-Type: application/json

{
  "curriculum_relevance_rating": 4.2,
  "learning_pace_appropriateness": 3.8,
  "individual_support_availability": 4.1,
  "teaching_quality_rating": 4.5,
  "attendance_rate": 85.5,
  "participation_score": 4.2,
  "overall_satisfaction": 4.0
}
```

### Dropout Risk Prediction
```
POST /api/v1/dropout/predict
Content-Type: application/json

{
  "attendance_rate": 65.2,
  "overall_satisfaction": 2.8,
  "academic_progress_rating": 2.5,
  "physical_safety_rating": 3.2,
  "psychological_safety_rating": 2.9,
  "mental_health_support_rating": 2.7
}
```

### Risk Assessment
```
POST /api/v1/risk/assess
Content-Type: application/json

{
  "curriculum_relevance_rating": 3.8,
  "teaching_quality_rating": 3.5,
  "physical_safety_rating": 4.2,
  "mental_health_support_rating": 3.1,
  "attendance_rate": 78.5,
  "overall_satisfaction": 3.6,
  "grade_average": 2.8
}
```

### Satisfaction Trend Analysis
```
POST /api/v1/satisfaction/trend
Content-Type: application/json

{
  "curriculum_relevance_rating": 4.2,
  "teaching_quality_rating": 4.1,
  "learning_environment_rating": 3.9,
  "overall_satisfaction": 4.0,
  "timestamp": "2024-01-15T10:00:00Z"
}
```

### Comprehensive Analytics
```
POST /api/v1/analytics/comprehensive
Content-Type: application/json

{
  "learner_needs_index": 4.2,
  "satisfaction_score": 3.8,
  "comments": ["Good experience"],
  "curriculum_relevance_rating": 4.1,
  "attendance_rate": 82.5
}
```

## Configuration

Create a `.env` file with the following variables:

```env
FLASK_PORT=5000
FLASK_DEBUG=false
LARAVEL_BASE_URL=http://localhost:8000
MODEL_SAVE_PATH=models/
LOG_LEVEL=INFO
```

## Model Training

The service includes pre-built models, but you can train custom models:

```python
from ai_models.compliance_predictor import CompliancePredictor

predictor = CompliancePredictor()
# Train with your data
result = predictor.train(X_train, y_train)
```

## Laravel Integration

The service is designed to work with Laravel's existing AIService. Update your Laravel configuration to use the Flask endpoints:

```php
// config/ai.php
return [
    'flask_service_url' => env('FLASK_AI_SERVICE_URL', 'http://localhost:5000'),
    'fallback_to_php' => env('AI_FALLBACK_TO_PHP', true),
];
```

## Architecture

```
ai-service/
├── app.py                 # Main Flask application
├── ai_models/            # Machine learning models
│   ├── compliance_predictor.py
│   ├── sentiment_analyzer.py
│   ├── student_clusterer.py
│   ├── student_performance_predictor.py
│   ├── dropout_risk_predictor.py
│   ├── risk_assessment_predictor.py
│   └── satisfaction_trend_predictor.py
├── utils/                # Utility functions
│   └── data_processor.py
├── models/               # Trained model files
├── data/                 # Data storage
├── requirements.txt      # Python dependencies
├── Dockerfile           # Docker configuration
└── docker-compose.yml   # Docker Compose setup
```

## Performance

- **Response Time**: <2 seconds for compliance prediction
- **Throughput**: Handles multiple concurrent requests
- **Memory Usage**: Optimized for production deployment
- **Fallback**: Automatic fallback to rule-based methods if ML models fail

## Monitoring

Health checks are available at `/health`. Monitor key metrics:

- Model prediction accuracy
- Response times
- Error rates
- Resource usage

## Development

### Running Tests

```bash
python -m pytest tests/
```

### Code Formatting

```bash
black .
flake8 .
```

### Training New Models

See individual model classes for training methods and data requirements.

## License

This project is part of the ISO Quality Education system.
