<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Profile Settings - ISO Quality Education</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ time() }}">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --info-gradient: linear-gradient(135deg, #4285F4 0%, #2c6cd6 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            --card-shadow-hover: 0 20px 60px rgba(0, 0, 0, 0.15);
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Base header styles to prevent overflow */
        .landing-header {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .landing-header .container {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .nav-wrapper {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .logo {
            min-width: 0;
            box-sizing: border-box;
            overflow: hidden;
        }

        .logo a {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Base styles for main and dashboard-container */
        main.survey-main {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        /* Override inline styles for dashboard-header children */
        .dashboard-header > div[style] {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            box-sizing: border-box !important;
            overflow: hidden !important;
        }

        .dashboard-header > div > div[style] {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            box-sizing: border-box !important;
            overflow: hidden !important;
        }

        .dashboard-header .btn[style] {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            /* Header mobile optimizations - Force prevent overflow */
            .landing-header {
                overflow: hidden !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                position: relative;
            }

            .landing-header .container {
                padding: 0 15px !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
            }

            .nav-wrapper {
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                display: flex !important;
                flex-wrap: nowrap !important;
                justify-content: space-between !important;
                align-items: center !important;
                gap: 10px !important;
                overflow: hidden !important;
                padding: 1rem 0 !important;
            }

            .logo {
                flex: 1 1 auto !important;
                min-width: 0 !important;
                max-width: calc(100% - 60px) !important;
                overflow: hidden !important;
                flex-shrink: 1 !important;
            }

            .logo a {
                font-size: 1rem !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                display: block !important;
                max-width: 100% !important;
            }

            /* Hide desktop nav on mobile - Force override */
            .landing-header .desktop-nav {
                display: none !important;
                visibility: hidden !important;
                width: 0 !important;
                height: 0 !important;
                overflow: hidden !important;
            }

            .mobile-menu-btn {
                display: block !important;
                flex-shrink: 0 !important;
                flex-grow: 0 !important;
                width: auto !important;
                min-width: 44px !important;
                max-width: 44px !important;
            }

            .menu-toggle {
                display: flex !important;
                flex-direction: column !important;
                gap: 0.25rem !important;
                padding: 0.5rem !important;
                background: none !important;
                border: none !important;
                cursor: pointer !important;
            }

            /* Disable backdrop-filter on mobile for better performance */
            .dashboard-header,
            .student-info-card,
            .survey-history {
                backdrop-filter: none;
                -webkit-backdrop-filter: none;
                background: rgba(255, 255, 255, 0.98) !important;
            }

            /* Simplify animations */
            .dashboard-header::before,
            .dashboard-header::after {
                animation: none;
            }

            /* Reduce card shadows on mobile */
            .student-info-card,
            .survey-history,
            .info-item {
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            /* Disable hover transforms on mobile */
            .info-item:hover,
            .btn:hover {
                transform: none;
            }

            /* Optimize dashboard header */
            .dashboard-header {
                padding: 30px 15px !important;
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
                background-color: rgba(255, 255, 255, 0.95);
                overflow: hidden !important; /* Prevent overflow */
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            /* Force all dashboard-header children to respect width */
            .dashboard-header > div {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .dashboard-header > div > div {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* Ensure buttons don't overflow */
            .dashboard-header .btn {
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                min-width: 0 !important;
                padding: 12px 16px !important;
                margin: 0 !important;
            }

            .dashboard-header .btn span {
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
            }

            .dashboard-header .btn svg {
                flex-shrink: 0 !important;
            }

            /* Ensure header content is visible and doesn't overflow */
            .dashboard-header > div {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 20px !important;
            }

            /* Text container - prevent overflow */
            .dashboard-header > div > div:first-child {
                position: relative;
                z-index: 2;
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                margin-bottom: 0 !important;
                box-sizing: border-box !important;
                overflow-wrap: break-word !important;
                word-wrap: break-word !important;
                padding: 0 !important;
            }

            /* Title - prevent overflow */
            .dashboard-header h1 {
                font-size: 24px !important;
                margin: 0 0 8px 0 !important;
                padding: 0 !important;
                word-break: break-word !important;
                overflow-wrap: break-word !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                line-height: 1.2 !important;
                color: #1e293b !important;
            }

            /* Paragraph - prevent overflow */
            .dashboard-header p {
                font-size: 14px !important;
                color: #64748b !important;
                opacity: 1 !important;
                display: block !important;
                visibility: visible !important;
                margin: 0 !important;
                padding: 0 !important;
                word-break: break-word !important;
                overflow-wrap: break-word !important;
                hyphens: auto !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                line-height: 1.4 !important;
            }

            /* Buttons container - prevent overflow */
            .dashboard-actions {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
                display: flex !important;
                flex-direction: column !important;
                gap: 10px !important;
                flex-shrink: 1 !important;
            }

            /* Buttons - full width on mobile */
            .dashboard-actions .btn {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
                justify-content: center !important;
                margin: 0 !important;
                white-space: normal !important;
            }

            /* Improve touch targets */
            .btn {
                min-height: 48px;
                min-width: 48px;
                padding: 14px 20px;
                touch-action: manipulation;
                -webkit-tap-highlight-color: rgba(102, 126, 234, 0.2);
            }

            /* Dashboard header layout changes for mobile */
            .dashboard-header > div {
                flex-direction: column !important;
                align-items: flex-start !important;
            }

            .dashboard-header > div > div:last-child {
                width: 100% !important;
                margin-top: 20px;
            }

            .dashboard-actions {
                width: 100% !important;
                flex-direction: column !important;
            }

            .dashboard-actions .btn {
                width: 100% !important;
            }

            /* Optimize form inputs */
            .form-input {
                font-size: 16px; /* Prevents zoom on iOS */
                padding: 14px 16px;
            }

            /* Simplify grid layouts */
            .student-info-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .consent-grid {
                grid-template-columns: 1fr;
            }

            /* Reduce padding */
            .student-info-card,
            .survey-history {
                padding: 25px 20px;
            }

            /* Optimize alert messages */
            .alert {
                padding: 16px 20px;
                font-size: 14px;
            }

            /* Simplify privacy section */
            .privacy-section::before {
                display: none;
            }

            /* Remove complex gradients on mobile */
            .rights-section {
                background: #fff3cd;
            }
        }

        @media (max-width: 480px) {
            /* Header optimizations for small screens */
            .landing-header {
                overflow: hidden !important;
                width: 100% !important;
            }

            .landing-header .container {
                padding: 0 10px !important;
                width: 100% !important;
                max-width: 100% !important;
                overflow: hidden !important;
            }

            .nav-wrapper {
                padding: 0.75rem 0 !important;
                gap: 8px !important;
                width: 100% !important;
                max-width: 100% !important;
                overflow: hidden !important;
            }

            .logo {
                max-width: calc(100% - 50px) !important;
                min-width: 0 !important;
                flex: 1 1 auto !important;
                overflow: hidden !important;
            }

            .logo a {
                font-size: 0.85rem !important;
                max-width: 100% !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }

            .mobile-menu-btn {
                padding: 0.25rem !important;
                flex-shrink: 0 !important;
                flex-grow: 0 !important;
                width: 44px !important;
                min-width: 44px !important;
                max-width: 44px !important;
            }

            /* Ensure desktop nav is completely hidden */
            .landing-header .desktop-nav {
                display: none !important;
                visibility: hidden !important;
                width: 0 !important;
                height: 0 !important;
                overflow: hidden !important;
                position: absolute !important;
                opacity: 0 !important;
            }

            .dashboard-header {
                padding: 20px 10px !important;
                overflow: hidden !important;
                max-width: 100% !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            .dashboard-header > div {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .dashboard-header > div > div {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .dashboard-header .btn {
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                padding: 12px 12px !important;
                font-size: 14px !important;
                margin: 0 !important;
            }

            .dashboard-header > div {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 12px !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .dashboard-header h1 {
                font-size: 20px !important;
                word-break: break-word !important;
                overflow-wrap: break-word !important;
                max-width: 100% !important;
                line-height: 1.2 !important;
                margin: 0 0 6px 0 !important;
                padding: 0 !important;
                color: #1e293b !important;
            }

            .dashboard-header p {
                font-size: 13px !important;
                color: #64748b !important;
                margin: 0 !important;
                padding: 0 !important;
                word-break: break-word !important;
                overflow-wrap: break-word !important;
                max-width: 100% !important;
                line-height: 1.4 !important;
            }

            .dashboard-header > div > div:first-child {
                width: 100% !important;
                min-width: 0 !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .dashboard-header > div > div:last-child {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                flex-direction: column !important;
                gap: 8px !important;
            }

            .dashboard-header > div > div:last-child > a {
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
                justify-content: center !important;
                margin: 0 !important;
            }

            .student-info-card h3,
            .survey-history h3 {
                font-size: 20px;
            }

            .info-value {
                font-size: 18px;
            }

            .form-label {
                font-size: 13px;
            }
        }

        /* Disable animations for reduced motion preference */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }

            .dashboard-header::before,
            .dashboard-header::after {
                display: none;
            }
        }

        /* Performance optimizations for mobile */
        @media (max-width: 768px) {
            .dashboard-header,
            .student-info-card,
            .survey-history,
            .info-item {
                will-change: auto;
            }

            /* Use transform3d for better GPU acceleration */
            .btn:active {
                transform: scale(0.98);
            }
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            overflow-x: hidden; /* Prevent horizontal overflow */
        }

        /* Mobile: reduce padding to prevent overflow */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px !important;
                width: 100% !important;
                max-width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }

        @media (max-width: 480px) {
            .dashboard-container {
                padding: 10px !important;
            }
        }

        /* Modern Glassmorphism Dashboard Header */
        .dashboard-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            color: #333;
            padding: 50px 40px;
            border-radius: var(--border-radius);
            margin-bottom: 40px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.5);
            position: relative;
            overflow: hidden;
            animation: fadeInDown 0.6s ease-out;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 200% 100%;
            animation: gradientShift 3s ease infinite;
        }

        .dashboard-header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
            pointer-events: none;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dashboard-header > div {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
            min-width: 0; /* Prevents flex overflow */
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        .dashboard-header > div > div:first-child {
            min-width: 0; /* Allows text to wrap properly */
            max-width: 100%;
            flex: 1;
            box-sizing: border-box;
        }

        .dashboard-header > div > div:last-child {
            min-width: 0;
            flex-shrink: 0;
            display: flex;
            gap: 12px;
            box-sizing: border-box;
        }

        .dashboard-header > div > div:last-child > a {
            white-space: nowrap;
        }

        .dashboard-header h1 {
            margin: 0 0 15px 0;
            font-size: 42px;
            font-weight: 800;
            line-height: 1.2;
            color: #1e293b;
            letter-spacing: -1px;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .dashboard-header p {
            margin: 0;
            font-size: 18px;
            line-height: 1.7;
            color: #64748b;
            font-weight: 500;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .dashboard-actions {
            display: flex;
            gap: 12px;
            flex-shrink: 0;
        }

        .dashboard-actions .btn {
            white-space: nowrap;
            width: auto;
        }

        /* Beautiful Student Info Card */
        .student-info-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
            backdrop-filter: blur(10px);
            padding: 35px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(102, 126, 234, 0.2);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .student-info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--primary-gradient);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .student-info-card h3 {
            margin-top: 0;
            margin-bottom: 25px;
            color: #1e293b;
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-info-card h3::before {
            content: '👤';
            font-size: 28px;
        }

        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(102, 126, 234, 0.1);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .info-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
        }

        .info-item:hover::before {
            transform: scaleX(1);
        }

        .info-label {
            font-weight: 600;
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 20px;
            color: #1e293b;
            font-weight: 600;
            margin-top: 5px;
        }

        /* Modern Form Inputs */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            min-height: 50px;
            transition: var(--transition);
            background: #ffffff;
            color: #1e293b;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-input:hover {
            border-color: #cbd5e1;
        }

        /* Beautiful Cards */
        .survey-history {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
            backdrop-filter: blur(10px);
            padding: 35px;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(102, 126, 234, 0.1);
            margin-bottom: 30px;
            transition: var(--transition);
            animation: fadeInUp 0.6s ease-out both;
        }

        .survey-history:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .survey-history h3 {
            margin-top: 0;
            margin-bottom: 25px;
            color: #1e293b;
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Enhanced Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: var(--transition);
            cursor: pointer;
            min-height: 50px;
            gap: 8px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .btn:active {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: var(--info-gradient);
            color: white;
        }

        .btn-primary:hover {
            color: white;
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
        }

        .btn-success:hover {
            color: white;
        }

        .btn-warning {
            background: var(--warning-gradient);
            color: white;
        }

        .btn-warning:hover {
            color: white;
        }

        .btn-danger {
            background: var(--danger-gradient);
            color: white;
        }

        /* Alert Messages */
        .alert {
            padding: 18px 24px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.5s ease-out;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border: 1px solid #10b981;
            color: #065f46;
        }

        .alert-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border: 1px solid #ef4444;
            color: #991b1b;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #f59e0b;
            color: #92400e;
        }

        .alert-info {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border: 1px solid #3b82f6;
            color: #1e40af;
        }

        /* Privacy Section Enhancement */
        .privacy-section {
            position: relative;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        }

        .privacy-section::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            background: var(--primary-gradient);
            border-radius: var(--border-radius) 0 0 var(--border-radius);
        }

        .privacy-status-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .status-badge.active {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding: 40px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
            backdrop-filter: blur(15px);
            text-align: center;
            color: #64748b;
            border-radius: var(--border-radius);
            border: 1px solid rgba(102, 126, 234, 0.1);
            box-shadow: var(--card-shadow);
        }

        /* Mobile Responsive - Enhanced */
        @media (max-width: 768px) {
            /* Prevent body and html overflow */
            html, body {
                overflow-x: hidden !important;
                width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            * {
                box-sizing: border-box !important;
            }

            main.survey-main {
                width: 100% !important;
                max-width: 100% !important;
                overflow-x: hidden !important;
                box-sizing: border-box !important;
            }

            /* Ensure mobile nav is properly styled */
            .mobile-nav {
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
            }

            .mobile-nav .logout-btn {
                width: 100%;
                max-width: 100%;
                box-sizing: border-box;
            }

            .desktop-nav .desktop-text {
                display: none;
            }

            .desktop-nav .header-action-btn {
                padding: 8px 12px;
                min-width: 44px;
            }

            /* Optimize footer */
            .footer {
                padding: 30px 20px;
            }
        }

        @media (max-width: 480px) {
            .desktop-nav {
                gap: 8px;
            }

            .desktop-nav > div {
                flex-wrap: wrap;
            }

            .footer {
                padding: 25px 15px;
            }
        }

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Loading Animation */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        /* Consent Status Grid */
        .consent-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            font-size: 14px;
        }

        .consent-item {
            background: #ffffff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .consent-item strong {
            color: #64748b;
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Rights Section */
        .rights-section {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffc107;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .rights-section h4 {
            color: #856404;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .rights-section ul {
            margin: 0;
            padding-left: 25px;
            color: #856404;
            line-height: 2;
        }

        .rights-section li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="landing-header">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ route('survey.landing') }}">ISO Quality Education</a>
                </div>

                <!-- Simple student navigation -->
                <nav class="desktop-nav">
                    <span class="nav-link" style="font-weight: 600; cursor: default; color: #312e81; font-size: 16px; white-space: nowrap;">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('student.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn" style="background: linear-gradient(90deg, #dc3545, #c82333); border: none; color: white; cursor: pointer; padding: 8px 20px; border-radius: 6px; font-weight: 600; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap;">
                            <svg style="width: 16px; height: 16px; fill: currentColor; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                            </svg>
                            <span class="desktop-text">Logout</span>
                        </button>
                    </form>
                </nav>

                <!-- Mobile menu button -->
                <div class="mobile-menu-btn">
                    <button class="menu-toggle" id="mobileMenuButton" aria-expanded="false" aria-controls="mobileNav">
                        <span class="hamburger"></span>
                        <span class="hamburger"></span>
                        <span class="hamburger"></span>
                    </button>
                </div>
            </div>

            <!-- Mobile navigation -->
            <nav class="mobile-nav" id="mobileNav">
                <div style="margin-bottom: 15px;">
                    <span class="mobile-nav-link" style="font-weight: 600; display: block; margin-bottom: 15px; font-size: 18px; color: #312e81;">{{ Auth::user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('student.logout') }}" style="margin-top: 10px;">
                    @csrf
                    <button type="submit" class="logout-btn" style="background: linear-gradient(90deg, #dc3545, #c82333); border: none; color: white; cursor: pointer; padding: 12px 20px; border-radius: 6px; font-weight: 600; transition: all 0.3s ease; width: 100%; display: flex; align-items: center; gap: 8px; justify-content: center;">
                        <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <main class="survey-main">
        <div class="dashboard-container">
            <!-- Profile Header -->
            <div class="dashboard-header">
                <div>
                    <div>
                        <h1>Profile Settings</h1>
                        <p>Manage your account information and preferences</p>
                    </div>
                    <div class="dashboard-actions">
                        <a href="{{ route('survey.form') }}" class="btn btn-success">
                            <svg style="width: 18px; height: 18px; fill: currentColor; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                            </svg>
                            <span>Take Survey</span>
                        </a>
                        <a href="{{ route('survey.landing') }}" class="btn btn-primary">
                            <svg style="width: 18px; height: 18px; fill: currentColor; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                            </svg>
                            <span>Back to Home</span>
                        </a>
                        @if(!empty($hasPreviousResponse))
                            <form method="POST" action="{{ route('student.responses.clear') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('This will delete your previous survey responses so you can submit a new one. Continue?');">
                                    <svg style="width: 18px; height: 18px; fill: currentColor; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1z"/>
                                    </svg>
                                    <span>Clear My Responses</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <svg style="width: 24px; height: 24px; fill: currentColor; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <svg style="width: 24px; height: 24px; fill: currentColor; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <svg style="width: 24px; height: 24px; fill: currentColor; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                    <div>
                        <strong>There were some issues with your submission:</strong>
                        <ul style="margin: 10px 0 0 20px; padding: 0; list-style: disc;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Student Information Card (Read-only) -->
            <div class="student-info-card">
                <h3>Student Information</h3>
                <div class="student-info-grid">
                    <div class="info-item">
                        <div class="info-label">Student ID</div>
                        <div class="info-value">{{ Auth::user()->student_id }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Year Level</div>
                        <div class="info-value">Grade {{ Auth::user()->year_level }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Section</div>
                        <div class="info-value">{{ Auth::user()->section }}</div>
                    </div>
                </div>
            </div>

            <!-- Profile Settings Form -->
            <div class="survey-history" style="margin-bottom: 30px;">
                <h3 style="display: flex; align-items: center; gap: 12px;">
                    <svg style="width: 28px; height: 28px; fill: #667eea;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    Update Profile Information
                </h3>
                <form action="{{ route('student.profile.update') }}" method="POST" style="max-width: 600px;">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="section" class="form-label">Section</label>
                        <input type="text" id="section" name="section" value="{{ Auth::user()->section }}" class="form-input" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Data Privacy & Consent Management (GDPR & ISO 27001) -->
            <div class="survey-history privacy-section" style="margin-bottom: 30px;">
                <h3 style="display: flex; align-items: center; gap: 12px;">
                    <svg style="width: 28px; height: 28px; fill: #667eea;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                    </svg>
                    Data Privacy & Consent Management
                </h3>
                <p style="color: #666; margin-bottom: 20px; line-height: 1.6;">
                    Manage your data privacy settings and consent preferences in compliance with GDPR and ISO 27001 standards.
                </p>

                @php
                    $consentService = app(\App\Services\ConsentService::class);
                    $studentId = Auth::user()->student_id ?? null;
                    $hasValidConsent = $studentId ? $consentService->hasValidConsent($studentId, 'survey_response') : false;
                    $consentHistory = $studentId ? $consentService->getConsentHistory($studentId, 'survey_response') : collect([]);
                    $latestConsent = $consentHistory->first();
                @endphp

                <div class="privacy-status-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <div>
                            <h4 style="margin: 0 0 10px 0; color: #1e293b; font-size: 20px; font-weight: 700;">Current Consent Status</h4>
                            <p style="margin: 0;">
                                @if($hasValidConsent)
                                    <span class="status-badge active">
                                        <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                        </svg>
                                        Active Consent
                                    </span>
                                @else
                                    <span class="status-badge inactive">
                                        <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                        </svg>
                                        No Active Consent
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($latestConsent)
                        <div style="background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%); padding: 20px; border-radius: 12px; margin-top: 20px; border: 1px solid rgba(102, 126, 234, 0.1);">
                            <div class="consent-grid">
                                <div class="consent-item">
                                    <strong>Consent Given:</strong>
                                    <div style="color: #1e293b; margin-top: 8px; font-weight: 600;">
                                        {{ $latestConsent->created_at->format('M j, Y g:i A') }}
                                    </div>
                                </div>
                                @if($latestConsent->expires_at)
                                    <div class="consent-item">
                                        <strong>Expires:</strong>
                                        <div style="color: #1e293b; margin-top: 8px; font-weight: 600;">
                                            {{ $latestConsent->expires_at->format('M j, Y') }}
                                        </div>
                                    </div>
                                @endif
                                @if($latestConsent->revoked_at)
                                    <div class="consent-item">
                                        <strong>Revoked:</strong>
                                        <div style="color: #dc2626; margin-top: 8px; font-weight: 600;">
                                            {{ $latestConsent->revoked_at->format('M j, Y g:i A') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="rights-section">
                    <h4 style="margin: 0 0 15px 0; font-size: 18px; font-weight: 700;">
                        <svg style="width: 24px; height: 24px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                        </svg>
                        Your Rights
                    </h4>
                    <ul>
                        <li>You can <strong>revoke your consent</strong> at any time</li>
                        <li>Revoking consent will prevent future data processing</li>
                        <li>Existing data will be handled according to retention policies</li>
                        <li>You can <strong>request data deletion</strong> by contacting the administrator</li>
                    </ul>
                </div>

                @if($studentId)
                    @if($hasValidConsent)
                        <form method="POST" action="{{ route('student.consent.revoke') }}" id="revokeConsentForm" style="max-width: 600px;">
                            @csrf
                            <div class="alert alert-warning" style="margin-bottom: 20px;">
                                <svg style="width: 24px; height: 24px; fill: currentColor; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                </svg>
                                <div>
                                    <strong>Warning:</strong> Revoking consent will prevent you from submitting new surveys.
                                    This action will be logged for audit purposes.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger" id="revokeConsentButton">
                                <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                </svg>
                                Revoke Consent
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <svg style="width: 24px; height: 24px; fill: currentColor; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                            </svg>
                            <span>You currently do not have active consent. You will need to provide consent when submitting a survey.</span>
                        </div>
                    @endif
                @else
                    <div class="alert alert-warning">
                        <svg style="width: 24px; height: 24px; fill: currentColor; flex-shrink: 0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                        </svg>
                        <span><strong>Note:</strong> Student ID not found. Consent management requires a valid student ID. Please contact support if you need assistance.</span>
                    </div>
                @endif
            </div>

            <!-- Change Password Section -->
            <div class="survey-history" style="margin-bottom: 30px;">
                <h3 style="display: flex; align-items: center; gap: 12px;">
                    <svg style="width: 28px; height: 28px; fill: #667eea;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                    </svg>
                    Change Password
                </h3>
                <form action="{{ route('student.password.update') }}" method="POST" style="max-width: 600px;">
                    @csrf
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                    </div>

                    <button type="submit" class="btn btn-warning">
                        <svg style="width: 18px; height: 18px; fill: currentColor;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <h3 style="color: #2c3e50; font-weight: 700; margin-bottom: 15px; font-size: 24px;">ISO Learner-Centric Quality Education</h3>
            <p style="color: #5a6c7d; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                Empowering CSS Strand Students through Learner-Centric Quality Education
            </p>
            <p style="color: #6c757d; font-weight: 500; font-size: 14px; margin: 0;">
                © <span id="currentYear"></span> JRU Senior High School. All rights reserved.
            </p>
        </div>
    </footer>

    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
