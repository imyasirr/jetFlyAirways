# JetFly — small APK build (arm64 only, ~15–20 MB)
# Full universal APK is ~50 MB because it bundles all CPU architectures.

$env:JAVA_HOME = "C:\Program Files\Microsoft\jdk-17.0.19.10-hotspot"
$env:ANDROID_HOME = "D:\Android\Sdk"
$env:ANDROID_SDK_ROOT = "D:\Android\Sdk"
$env:Path = "D:\JetFly\flutter_sdk\bin;$env:JAVA_HOME\bin;" + $env:Path

Set-Location $PSScriptRoot

flutter pub get
flutter build apk --release --target-platform android-arm64

Write-Host ""
Write-Host "APK ready:" -ForegroundColor Green
Write-Host "  build\app\outputs\flutter-apk\app-release.apk"
Get-Item "build\app\outputs\flutter-apk\app-release.apk" | ForEach-Object {
    Write-Host ("  Size: {0:N1} MB" -f ($_.Length / 1MB))
}
