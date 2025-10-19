# ISO Quality Education Survey System

A complete survey application converted from React/TypeScript to HTML/CSS/JavaScript/PHP.

## Files Structure

### HTML Pages
- `index.html` - Main landing page with survey information (entry point)
- `survey.html` - Interactive survey form with multi-step navigation
- `dashboard.html` - Analytics dashboard for viewing survey results
- `thank-you.html` - Thank you page after survey submission

### Stylesheets
- `styles.css` - Complete responsive CSS styling for all pages

### JavaScript Files
- `main.js` - Common functionality (navigation, utilities, notifications)
- `survey.js` - Survey-specific functionality (form handling, progress tracking)
- `dashboard.js` - Dashboard functionality (data visualization, filtering)

### Backend
- `submit_survey.php` - PHP backend for handling survey submissions and dashboard data
- `database_setup.sql` - MySQL database schema and sample data

### Documentation
- `README.md` - Complete setup and usage guide

## Setup Instructions

### 1. Database Setup
1. Open phpMyAdmin or MySQL command line
2. Import or run the `database_setup.sql` file
3. This will create the `education_survey` database and `survey_responses` table

### 2. PHP Configuration
1. Update the database credentials in `submit_survey.php`:
   ```php
   $host = 'localhost';
   $dbname = 'education_survey';
   $username = 'root';        // Your MySQL username
   $password = '';            // Your MySQL password
   ```

### 3. Web Server Setup
1. Place all files in your web server directory (e.g., `htdocs` for XAMPP)
2. Ensure PHP and MySQL are running
3. Access the application via `http://localhost/Survey/index.html`

## Current File Structure
```
c:\xampp\htdocs\Survey\
├── index.html          (Main landing page - entry point)
├── survey.html         (Multi-step survey form)
├── dashboard.html      (Analytics dashboard)
├── thank-you.html      (Success/thank you page)
├── styles.css          (Complete CSS styling)
├── main.js            (Common JavaScript functionality)
├── survey.js          (Survey-specific logic)
├── dashboard.js       (Dashboard functionality)
├── submit_survey.php  (PHP backend API)
├── database_setup.sql (Database schema)
└── README.md          (Documentation)
```

## Features

### Landing Page
- Hero section with survey introduction
- Information cards explaining the survey process
- Responsive design for mobile and desktop

### Survey Form
- Multi-step form with 8 sections
- Progress tracking with visual progress bar
- Form validation and error handling
- Local storage for saving progress
- Responsive rating scales (1-5 Likert scale)
- Demographics and open-ended questions

### Dashboard
- Key performance metrics display
- Interactive tabs for different analytics views
- Filtering by academic year and grade level
- Real-time data refresh
- Export functionality (JSON/CSV)
- Responsive charts and visualizations

## Getting Started

```bash
# For XAMPP users:
1. Start Apache and MySQL in XAMPP Control Panel
2. Place files in htdocs/Survey/
3. Import database_setup.sql in phpMyAdmin
4. Visit http://localhost/Survey/landing.html
```
