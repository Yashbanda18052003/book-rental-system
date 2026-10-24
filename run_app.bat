@echo off
title BookNest - Online Book Rental Management System
echo ===================================================
echo     Launching BookNest (Laravel 11 Web Application)
echo ===================================================
echo.

cd /d "%~dp0"

REM Verify PHP is available
php -v >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo [ERROR] PHP is not detected in your PATH.
    echo Please install PHP 8.2+ or ensure C:\xampp\php is in your system PATH.
    pause
    exit /b 1
)

REM Verify .env exists
if not exist ".env" (
    echo [.env missing] Creating .env from .env.example...
    copy .env.example .env
    php artisan key:generate
)

REM Check SQLite database file
if not exist "database\database.sqlite" (
    echo [Database] Creating database\database.sqlite...
    type nul > "database\database.sqlite"
    php artisan migrate --force --seed
)

echo Starting BookNest local server on http://127.0.0.1:8000 ...
start "" http://127.0.0.1:8000

php artisan serve --port=8000
pause
