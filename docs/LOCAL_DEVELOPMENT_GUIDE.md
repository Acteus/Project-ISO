# 🚀 Local Development Guide - ISO 21001 Quality Education System

## 📁 Project Structure

```
C:\Users\Bubbles\Desktop\
├── Project-ISO\              ← Laravel Application (Main App)
├── ai-service\               ← Python Flask AI Service (ML Models)
└── Project-ISO-main-backup\  ← Backup (can be deleted once stable)
```

---

## ✅ Prerequisites

- [x] **PHP 8.2+** (for Laravel)
- [x] **Python 3.x** (for AI service)
- [x] **Composer** (PHP dependencies)
- [x] **Node.js & npm** (Frontend assets)
- [x] **Docker Desktop** (optional, for containerized deployment)

---

## 🔧 Initial Setup (One-Time)

### 1. **AI Service Setup**

Open **Terminal 1** (PowerShell):

```powershell
# Navigate to AI service
cd "C:\Users\Bubbles\Desktop\ai-service"

# Create .env file
Copy-Item .env.example .env

# Install Python dependencies
pip install -r requirements.txt

# Train ML models (first time only - takes a few minutes)
python train_models.py
```

**Note**: Training generates 8 ML models:
- Compliance Predictor
- Sentiment Analyzer  
- Student Clusterer
- Risk Classifier
- Engagement Predictor
- Performance Predictor
- Dropout Predictor
- Recommendation System

---

### 2. **Laravel Application Setup**

Open **Terminal 2** (PowerShell):

```powershell
# Navigate to Laravel project
cd "C:\Users\Bubbles\Desktop\Project-ISO"

# Install dependencies
composer install
npm install

# Setup database (already done ✅)
# php artisan migrate:fresh

# Create admin user (optional)
php artisan db:seed --class=AdminSeeder
```

---

## 🏃 Running the System

### **Terminal 1: Start AI Service** (Flask)

```powershell
cd "C:\Users\Bubbles\Desktop\ai-service"
python app.py
```

✅ AI Service runs on: **http://localhost:5002**

Check health: http://localhost:5002/health

---

### **Terminal 2: Start Laravel App**

```powershell
cd "C:\Users\Bubbles\Desktop\Project-ISO"
php artisan serve
```

✅ Laravel App runs on: **http://localhost:8000**

---

### **Terminal 3: Compile Frontend Assets** (Optional)

```powershell
cd "C:\Users\Bubbles\Desktop\Project-ISO"
npm run dev
```

This watches for CSS/JS changes and auto-compiles.

---

## 🧪 Testing the Integration

### Test AI Service Connection

```powershell
cd "C:\Users\Bubbles\Desktop\Project-ISO"
php artisan ai:test-connection
```

### Test All AI Models

```powershell
php artisan ai:test-connection --service=all --detailed
```

### Test Specific Endpoints

```powershell
# Health check
curl http://localhost:5002/health

# Compliance prediction (requires AI service running)
curl -X POST http://localhost:5002/predict/compliance \
  -H "Content-Type: application/json" \
  -H "X-API-Key: your-secret-key-here" \
  -d '{"responses": {...}}'
```

---

## 🔑 Default Credentials

### Admin Login
- **URL**: http://localhost:8000/admin/login
- **Username**: `admin`
- **Password**: `password` (or set via seeder)

### Student Registration
- **URL**: http://localhost:8000/register

---

## 📊 Key Features to Test

1. **Student Survey** 
   - Register/login as student
   - Complete ISO 21001 survey (20+ questions)
   - View personalized dashboard

2. **Admin Dashboard**
   - Login as admin
   - View real-time analytics
   - Generate reports (Excel, PDF)
   - Manage QR codes

3. **AI Analytics**
   - Compliance risk predictions
   - Sentiment analysis
   - Student clustering
   - Performance predictions

4. **QR Code System**
   - Generate QR codes for survey distribution
   - Track scans and responses

---

## 🐛 Troubleshooting

### AI Service Won't Start

```powershell
# Check Python version
python --version  # Should be 3.8+

# Reinstall dependencies
pip install -r requirements.txt --force-reinstall

# Check if models exist
ls ai_models/
```

### Laravel Errors

```powershell
# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Regenerate key
php artisan key:generate

# Check logs
cat storage/logs/laravel.log
```

### Database Issues

```powershell
# Reset database
php artisan migrate:fresh

# Seed test data
php artisan db:seed
```

### AI Service Not Reachable from Laravel

Check `.env` file in Project-ISO:

```env
FLASK_AI_SERVICE_URL=http://localhost:5002
FLASK_AI_API_KEY=your-secret-key-here
AI_TIMEOUT_SECONDS=30
AI_FALLBACK_TO_PHP=true
```

---

## 📦 Environment Configuration

### AI Service `.env` (ai-service/)

```env
FLASK_ENV=development
FLASK_DEBUG=True
API_KEY=your-secret-key-here
MODEL_PATH=./ai_models
PORT=5002
```

### Laravel `.env` (Project-ISO/)

```env
APP_NAME="ISO 21001 Quality Education"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite

FLASK_AI_SERVICE_URL=http://localhost:5002
FLASK_AI_API_KEY=your-secret-key-here
AI_FALLBACK_TO_PHP=true
```

---

## 🐳 Docker Alternative (Optional)

If you prefer Docker:

```powershell
# AI Service with Docker
cd "C:\Users\Bubbles\Desktop\ai-service"
docker-compose up

# Laravel with Docker (if you have Laravel Sail)
cd "C:\Users\Bubbles\Desktop\Project-ISO"
./vendor/bin/sail up
```

---

## 📚 Additional Resources

- **Main README**: [README.md](./README.md)
- **Deployment Guide**: [DEPLOYMENT_README.md](./DEPLOYMENT_README.md)
- **Cloudways Deploy**: [cloudways-deployment.md](./cloudways-deployment.md)
- **Fly.io Deploy**: [fly-deployment.md](./fly-deployment.md)
- **AI Service Docs**: `../ai-service/README.md`
- **Training Guide**: `../ai-service/TRAINING_GUIDE.md`

---

## 🎯 Development Workflow

1. **Start AI Service** (Terminal 1)
2. **Start Laravel** (Terminal 2)  
3. **Watch Assets** (Terminal 3) - optional
4. **Make Changes** - Code in your editor
5. **Test** - Visit http://localhost:8000
6. **Commit** - `git add .` → `git commit` → `git push`

---

## 📝 Notes

- **AI Service must run FIRST** before Laravel can use AI features
- **Fallback Mode**: If AI service is down, PHP-ML library provides basic analytics
- **Cache**: Laravel caches AI responses for 5 minutes (configurable)
- **Background Jobs**: For production, set up `queue:work` for email reports

---

## 🆘 Need Help?

- Check logs: `storage/logs/laravel.log`
- AI Service logs: Console output where `python app.py` runs
- Documentation: `docs/` folder
- GitHub Issues: [Create an issue](https://github.com/Acteus/Project-ISO/issues)

---

**Happy Coding! 🚀**
