# Railway Deployment Testing Guide

This guide provides step-by-step instructions for testing the Railway deployment configuration locally before deploying to production.

## Local Testing Environment Setup

### Prerequisites

1. **Railway CLI**: Install Railway CLI for local testing
```bash
npm install -g @railway/cli
# or
curl -fsSL https://railway.app/install.sh | sh
```

2. **Docker**: Ensure Docker is installed and running
```bash
docker --version
docker-compose --version
```

3. **Local Dependencies**: Install project dependencies
```bash
# Laravel dependencies
composer install
npm install

# AI service dependencies
cd ai-service
pip install -r requirements.txt
cd ..
```

### Environment Setup

1. **Copy environment files**:
```bash
cp .env.example .env
cp ai-service/.env.example ai-service/.env
```

2. **Configure local environment**:
```env
# .env (Laravel)
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

# AI Service URL (use localhost for testing)
FLASK_AI_SERVICE_URL=http://localhost:5000

# ai-service/.env
FLASK_PORT=5000
FLASK_DEBUG=true
LARAVEL_BASE_URL=http://localhost:8000
```

## Testing Components

### 1. Laravel Application Testing

#### Database Setup
```bash
# Create SQLite database
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed --class=AdminSeeder
```

#### Start Laravel Application
```bash
# Development server
php artisan serve --host=0.0.0.0 --port=8000

# Or with asset watching
npm run dev
```

#### Test Laravel Endpoints
```bash
# Health check
curl http://localhost:8000/health

# Admin login test
curl -X POST http://localhost:8000/api/admin/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'

# Survey analytics
curl http://localhost:8000/api/survey/analytics \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 2. AI Service Testing

#### Start AI Service
```bash
cd ai-service

# Using Python directly
python app.py

# Or using Docker
docker-compose up --build
```

#### Test AI Service Endpoints
```bash
# Health check
curl http://localhost:5000/health

# Compliance prediction
curl -X POST http://localhost:5000/api/v1/compliance/predict \
  -H "Content-Type: application/json" \
  -d '{
    "learner_needs_index": 4.2,
    "satisfaction_score": 3.8,
    "success_index": 4.1,
    "safety_index": 4.5,
    "wellbeing_index": 3.9,
    "overall_satisfaction": 4.0
  }'

# Sentiment analysis
curl -X POST http://localhost:5000/api/v1/sentiment/analyze \
  -H "Content-Type: application/json" \
  -d '{"comments": ["Great teaching quality!", "Need more support"]}'
```

### 3. Integration Testing

#### Test Laravel-AI Service Communication
```bash
# Test AI service connectivity from Laravel
php artisan ai:test-flask

# Test specific AI features
php artisan ai:test-flask --compliance
php artisan ai:test-flask --sentiment
```

#### End-to-End Survey Flow
1. **Access the application**: http://localhost:8000
2. **Student registration**: Create a test student account
3. **Complete survey**: Fill out the ISO 21001 survey
4. **Admin dashboard**: Login as admin and check analytics
5. **AI insights**: Verify AI predictions are working

## Railway CLI Testing

### Link Project
```bash
# Login to Railway
railway login

# Link to existing project or create new one
railway link

# Or create new project
railway init
```

### Local Railway Simulation
```bash
# Test build process locally
railway run --service laravel-app composer install
railway run --service laravel-app npm ci
railway run --service laravel-app npm run build

# Test AI service build
railway run --service ai-service pip install -r requirements.txt
```

### Environment Variables Testing
```bash
# Check environment variables
railway variables

# Set test variables
railway variables set APP_ENV=staging
railway variables set FLASK_AI_SERVICE_URL=http://ai-service:5000
```

## Docker Testing

### Build Docker Images
```bash
# Build Laravel image
docker build -t iso21001-laravel .

# Build AI service image
cd ai-service
docker build -t iso21001-ai .
cd ..
```

### Run with Docker Compose
```bash
# Start all services
docker-compose up --build

# Run in background
docker-compose up -d --build

# Check logs
docker-compose logs -f laravel
docker-compose logs -f ai-service
```

### Test Docker Containers
```bash
# Test Laravel container
docker exec -it iso21001-laravel php artisan migrate
docker exec -it iso21001-laravel php artisan db:seed

# Test AI service container
docker exec -it iso21001-ai curl http://localhost:5000/health
```

## Performance Testing

### Load Testing
```bash
# Install artillery for load testing
npm install -g artillery

# Create load test script
# artillery quick --count 10 --num 5 http://localhost:8000/health
```

### Memory and CPU Testing
```bash
# Monitor resource usage
docker stats

# Check Laravel memory usage
php artisan tinker
echo "Memory usage: " . memory_get_peak_usage(true) / 1024 / 1024 . " MB";
```

## Security Testing

### Environment Variables Check
```bash
# Ensure no sensitive data in logs
grep -r "password\|secret\|key" storage/logs/

# Check .env file permissions
ls -la .env
```

### CORS Testing
```bash
# Test CORS headers
curl -I -H "Origin: http://localhost:3000" http://localhost:8000/api/survey/analytics
```

## Database Testing

### Migration Testing
```bash
# Test migrations
php artisan migrate:status
php artisan migrate:rollback --step=1
php artisan migrate
```

### Seed Testing
```bash
# Test seeders
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=CssSurveyResponseSeeder
```

## AI Model Testing

### Model Loading Test
```bash
# Test model loading in AI service
cd ai-service
python -c "
from ai_models.compliance_predictor import CompliancePredictor
predictor = CompliancePredictor()
print('Model loaded successfully')
"
```

### Prediction Accuracy Test
```bash
# Test prediction endpoints with sample data
curl -X POST http://localhost:5000/api/v1/compliance/predict \
  -H "Content-Type: application/json" \
  -d '{"learner_needs_index": 4.0, "satisfaction_score": 3.5, "success_index": 4.0, "safety_index": 4.0, "wellbeing_index": 3.5, "overall_satisfaction": 4.0}'
```

## Deployment Simulation

### Railway Deploy Dry Run
```bash
# Simulate deployment
railway up --detach

# Check deployment status
railway status

# View logs
railway logs
```

### Rollback Testing
```bash
# Test rollback capability
railway rollback

# Verify application still works after rollback
curl http://localhost:8000/health
```

## Monitoring Setup Testing

### Health Check Testing
```bash
# Test health check endpoint
curl http://localhost:8000/health | jq .

# Test AI service health
curl http://localhost:5000/health | jq .
```

### Log Monitoring
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check AI service logs
docker logs -f iso21001-ai
```

## Automated Testing

### Create Test Scripts

#### Laravel Test Script (`test-laravel.sh`)
```bash
#!/bin/bash

echo "Testing Laravel Application..."

# Health check
if curl -f http://localhost:8000/health > /dev/null; then
    echo "✓ Health check passed"
else
    echo "✗ Health check failed"
    exit 1
fi

# Database connection
if php artisan tinker --execute="echo DB::select('SELECT 1');" > /dev/null; then
    echo "✓ Database connection ok"
else
    echo "✗ Database connection failed"
    exit 1
fi

# AI service connectivity
if php artisan ai:test-flask > /dev/null; then
    echo "✓ AI service connectivity ok"
else
    echo "✗ AI service connectivity failed"
    exit 1
fi

echo "All Laravel tests passed!"
```

#### AI Service Test Script (`test-ai-service.sh`)
```bash
#!/bin/bash

echo "Testing AI Service..."

# Health check
if curl -f http://localhost:5000/health > /dev/null; then
    echo "✓ AI service health check passed"
else
    echo "✗ AI service health check failed"
    exit 1
fi

# Model prediction test
response=$(curl -s -X POST http://localhost:5000/api/v1/compliance/predict \
  -H "Content-Type: application/json" \
  -d '{"learner_needs_index": 4.0, "satisfaction_score": 3.5, "success_index": 4.0, "safety_index": 4.0, "wellbeing_index": 3.5, "overall_satisfaction": 4.0}')

if echo "$response" | jq -e '.prediction' > /dev/null; then
    echo "✓ Compliance prediction working"
else
    echo "✗ Compliance prediction failed"
    exit 1
fi

echo "All AI service tests passed!"
```

#### Integration Test Script (`test-integration.sh`)
```bash
#!/bin/bash

echo "Running Integration Tests..."

# Start services if not running
docker-compose up -d

# Wait for services to be ready
sleep 30

# Run individual tests
./test-laravel.sh
./test-ai-service.sh

# End-to-end test
echo "Running end-to-end survey test..."

# This would require a more complex test setup
# For now, just check that the survey page loads
if curl -f http://localhost:8000/survey > /dev/null; then
    echo "✓ Survey page accessible"
else
    echo "✗ Survey page not accessible"
    exit 1
fi

echo "All integration tests passed!"
```

### Run Automated Tests
```bash
chmod +x test-laravel.sh test-ai-service.sh test-integration.sh
./test-integration.sh
```

## Troubleshooting Common Issues

### Laravel Issues
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Regenerate autoloader
composer dump-autoload

# Check environment
php artisan env
```

### AI Service Issues
```bash
# Check Python environment
cd ai-service
python --version
pip list

# Test model loading
python -c "import ai_models.compliance_predictor; print('Models import ok')"
```

### Docker Issues
```bash
# Clean up Docker
docker system prune -a

# Rebuild images
docker-compose build --no-cache

# Check container logs
docker-compose logs
```

### Railway CLI Issues
```bash
# Re-login
railway logout
railway login

# Check project status
railway status
railway services
```

## Performance Benchmarks

### Expected Response Times
- **Health check**: < 500ms
- **Survey submission**: < 2s
- **Analytics dashboard**: < 3s
- **AI prediction**: < 1s
- **Report generation**: < 5s

### Resource Usage Benchmarks
- **Laravel (idle)**: ~50MB RAM, <5% CPU
- **Laravel (active)**: ~100MB RAM, <20% CPU
- **AI Service (idle)**: ~200MB RAM, <5% CPU
- **AI Service (active)**: ~500MB RAM, <30% CPU

## Final Deployment Checklist

- [ ] All local tests pass
- [ ] Docker containers build successfully
- [ ] Railway CLI commands work
- [ ] Environment variables are configured
- [ ] Database migrations run successfully
- [ ] AI service models load correctly
- [ ] Health checks return healthy status
- [ ] Performance meets benchmarks
- [ ] Security scan passes
- [ ] Backup strategy tested

This testing guide ensures your Railway deployment is thoroughly tested before going to production, minimizing potential issues and ensuring a smooth deployment process.
