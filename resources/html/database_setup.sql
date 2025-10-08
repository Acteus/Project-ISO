-- Database setup for the survey application
-- Run this SQL to create the necessary database and table

-- Create database
CREATE DATABASE IF NOT EXISTS education_survey;
USE education_survey;

-- Create survey responses table
CREATE TABLE IF NOT EXISTS survey_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Rating questions (1-21)
    q1 INT DEFAULT NULL,
    q2 INT DEFAULT NULL,
    q3 INT DEFAULT NULL,
    q4 INT DEFAULT NULL,
    q5 INT DEFAULT NULL,
    q6 INT DEFAULT NULL,
    q7 INT DEFAULT NULL,
    q8 INT DEFAULT NULL,
    q9 INT DEFAULT NULL,
    q10 INT DEFAULT NULL,
    q11 INT DEFAULT NULL,
    q12 INT DEFAULT NULL,
    q13 INT DEFAULT NULL,
    q14 INT DEFAULT NULL,
    q15 INT DEFAULT NULL,
    q16 INT DEFAULT NULL,
    q17 INT DEFAULT NULL,
    q18 INT DEFAULT NULL,
    q19 INT DEFAULT NULL,
    q20 INT DEFAULT NULL,
    q21 INT DEFAULT NULL,
    
    -- Demographics
    year_level VARCHAR(50) DEFAULT NULL,
    gender VARCHAR(50) DEFAULT NULL,
    
    -- Open feedback
    open_feedback TEXT DEFAULT NULL,
    
    -- Metadata
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL
);

-- Create indexes for better performance
CREATE INDEX idx_submitted_at ON survey_responses(submitted_at);
CREATE INDEX idx_year_level ON survey_responses(year_level);
CREATE INDEX idx_gender ON survey_responses(gender);

-- Insert some sample data for testing (optional)
INSERT INTO survey_responses (
    q1, q2, q3, q4, q5, q6, q7, q8, q9, q10,
    q11, q12, q13, q14, q15, q16, q17, q18, q19, q20, q21,
    year_level, gender, open_feedback, submitted_at
) VALUES 
(4, 5, 4, 4, 5, 4, 3, 4, 4, 5, 4, 4, 4, 3, 4, 3, 3, 4, 4, 5, 4, 'Grade 11', 'Female', 'Great program overall!', '2024-01-15 10:30:00'),
(5, 4, 5, 4, 4, 5, 4, 3, 4, 4, 5, 4, 4, 4, 3, 4, 4, 3, 5, 4, 5, 'Grade 12', 'Male', 'Could use more practical exercises.', '2024-01-14 14:22:00'),
(3, 4, 4, 3, 4, 3, 3, 4, 3, 4, 3, 4, 4, 4, 4, 3, 3, 4, 4, 4, 4, 'Grade 11', 'Female', 'Teachers are very supportive.', '2024-01-13 09:15:00'),
(4, 4, 3, 4, 3, 4, 4, 4, 4, 3, 4, 3, 4, 4, 4, 4, 4, 4, 3, 4, 3, 'Grade 12', 'Male', '', '2024-01-12 16:45:00'),
(5, 5, 5, 5, 5, 4, 4, 5, 4, 5, 5, 5, 4, 5, 4, 4, 4, 4, 5, 5, 5, 'Grade 11', 'Prefer not to say', 'Excellent program, highly recommend!', '2024-01-11 11:20:00');

-- View to get section averages
CREATE VIEW section_averages AS
SELECT 
    AVG((q1 + q2 + q3) / 3) as learner_needs_avg,
    AVG((q4 + q5 + q6) / 3) as teaching_quality_avg,
    AVG((q7 + q8 + q9) / 3) as assessments_avg,
    AVG((q10 + q11 + q12) / 3) as support_avg,
    AVG((q13 + q14 + q15) / 3) as environment_avg,
    AVG((q16 + q17 + q18) / 3) as feedback_avg,
    AVG((q19 + q20 + q21) / 3) as satisfaction_avg
FROM survey_responses
WHERE q1 IS NOT NULL;

-- View to get overall statistics
CREATE VIEW survey_statistics AS
SELECT 
    COUNT(*) as total_responses,
    AVG((q1 + q2 + q3 + q4 + q5 + q6 + q7 + q8 + q9 + q10 + 
         q11 + q12 + q13 + q14 + q15 + q16 + q17 + q18 + q19 + q20 + q21) / 21) as overall_average,
    COUNT(CASE WHEN year_level = 'Grade 11' THEN 1 END) as grade_11_count,
    COUNT(CASE WHEN year_level = 'Grade 12' THEN 1 END) as grade_12_count,
    COUNT(CASE WHEN gender = 'Male' THEN 1 END) as male_count,
    COUNT(CASE WHEN gender = 'Female' THEN 1 END) as female_count,
    COUNT(CASE WHEN open_feedback IS NOT NULL AND open_feedback != '' THEN 1 END) as feedback_count
FROM survey_responses;