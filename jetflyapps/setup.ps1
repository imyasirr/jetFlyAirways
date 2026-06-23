# JetFly Apps - Flutter Setup Script
# Run this once after installing Flutter SDK

Write-Host "JetFly Apps Setup" -ForegroundColor Cyan

if (-not (Get-Command flutter -ErrorAction SilentlyContinue)) {
    Write-Host "ERROR: Flutter not found. Install from https://docs.flutter.dev/get-started/install/windows" -ForegroundColor Red
    exit 1
}

Write-Host "Flutter version:" -ForegroundColor Green
flutter --version

# Generate android/ios platform folders if missing
if (-not (Test-Path "android")) {
    Write-Host "Creating Flutter platform folders..." -ForegroundColor Yellow
    flutter create . --org com.jetflyairways --project-name jetflyapps
}

Write-Host "Installing dependencies..." -ForegroundColor Yellow
flutter pub get

Write-Host ""
Write-Host "Setup complete!" -ForegroundColor Green
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "  1. Start Laravel: cd .. && php artisan serve --host=0.0.0.0"
Write-Host "  2. Update API URL in lib/config/api_config.dart if needed"
Write-Host "  3. Run app: flutter run"
