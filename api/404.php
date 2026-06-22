<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #0f0c29, #302b63, #24243e);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* ===== ANIMATED BACKGROUND ORBS ===== */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: float 20s ease-in-out infinite alternate;
        }

        body::before {
            width: 500px;
            height: 500px;
            background: #ff6b6b;
            top: -150px;
            right: -150px;
        }

        body::after {
            width: 500px;
            height: 500px;
            background: #4ecdc4;
            bottom: -150px;
            left: -150px;
            animation-delay: 10s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(10px, -10px) scale(1.05); }
        }

        /* ===== MAIN CARD ===== */
        .error-container {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 48px;
            padding: 60px 50px;
            max-width: 700px;
            width: 100%;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.10);
            box-shadow: 
                0 40px 80px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.10);
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .error-container:hover {
            transform: translateY(-8px) scale(1.01);
        }

        /* ===== 404 NUMBER ===== */
        .error-number {
            font-size: 140px;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 50%, #ffecd2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: none;
            letter-spacing: -6px;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }

        .error-number::after {
            content: '404';
            position: absolute;
            top: 0;
            left: 0;
            -webkit-text-fill-color: transparent;
            background: none;
            text-shadow: 0 20px 60px rgba(245, 87, 108, 0.3);
            opacity: 0.3;
            filter: blur(20px);
            z-index: -1;
            transform: scale(1.05);
        }

        /* ===== DECORATIVE LINE ===== */
        .divider {
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #f093fb, #f5576c);
            border-radius: 4px;
            margin: 16px auto 24px;
        }

        /* ===== TYPOGRAPHY ===== */
        .error-title {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .error-message {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.70);
            line-height: 1.7;
            max-width: 440px;
            margin: 0 auto 32px;
            font-weight: 400;
        }

        /* ===== SEARCH / ACTION BAR ===== */
        .search-box {
            display: flex;
            gap: 12px;
            max-width: 480px;
            margin: 0 auto 28px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 60px;
            padding: 6px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }

        .search-box:focus-within {
            border-color: rgba(245, 87, 108, 0.5);
            box-shadow: 0 0 0 4px rgba(245, 87, 108, 0.10);
            background: rgba(255, 255, 255, 0.08);
        }

        .search-box input {
            flex: 1;
            padding: 14px 20px;
            border: none;
            background: transparent;
            color: #fff;
            font-size: 15px;
            font-weight: 400;
            outline: none;
            min-width: 0;
        }

        .search-box input::placeholder {
            color: rgba(255, 255, 255, 0.40);
            font-weight: 300;
        }

        .search-box button {
            padding: 12px 28px;
            border: none;
            border-radius: 40px;
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            letter-spacing: 0.3px;
        }

        .search-box button:hover {
            transform: scale(1.04);
            box-shadow: 0 8px 30px rgba(245, 87, 108, 0.35);
        }

        .search-box button:active {
            transform: scale(0.96);
        }

        /* ===== BUTTON GROUP ===== */
        .action-buttons {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            border-radius: 40px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            letter-spacing: 0.2px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: #fff;
            box-shadow: 0 8px 28px rgba(245, 87, 108, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 40px rgba(245, 87, 108, 0.35);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(4px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-3px);
            border-color: rgba(255, 255, 255, 0.25);
        }

        .btn:active {
            transform: scale(0.96);
        }

        /* ===== SVG ICON INLINE ===== */
        .btn svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 600px) {
            .error-container {
                padding: 40px 24px;
                border-radius: 32px;
            }

            .error-number {
                font-size: 90px;
                letter-spacing: -4px;
            }

            .error-title {
                font-size: 22px;
            }

            .search-box {
                flex-direction: column;
                background: transparent;
                padding: 0;
                border: none;
                gap: 12px;
            }

            .search-box input {
                padding: 16px 20px;
                background: rgba(255, 255, 255, 0.06);
                border-radius: 40px;
                border: 1px solid rgba(255, 255, 255, 0.08);
                text-align: center;
            }

            .search-box input:focus {
                border-color: rgba(245, 87, 108, 0.4);
            }

            .search-box button {
                width: 100%;
                justify-content: center;
                padding: 16px;
            }

            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .btn {
                justify-content: center;
            }

            .divider {
                width: 60px;
            }
        }

        @media (max-width: 400px) {
            .error-number {
                font-size: 70px;
            }

            .error-container {
                padding: 30px 16px;
            }
        }
    </style>
</head>
<body>

    <div class="error-container">
        <!-- 404 Number -->
        <div class="error-number">404</div>

        <!-- Decorative line -->
        <div class="divider"></div>

        <!-- Title & Message -->
        <h1 class="error-title">Page not found</h1>
        <p class="error-message">
            The page you're looking for seems to have wandered off.<br>
            Let's get you back on track.
        </p>

        <!-- Search / Quick action -->
        <form class="search-box" method="get" action="/">
            <input 
                type="text" 
                name="s" 
                placeholder="Search our site..." 
                aria-label="Search"
                autocomplete="off"
            >
            <button type="submit">Search</button>
        </form>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="/" class="btn btn-primary">
                <!-- Home icon (inline SVG) -->
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/>
                </svg>
                Home
            </a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <!-- Arrow left icon -->
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Go Back
            </a>
        </div>
    </div>

</body>
</html>
