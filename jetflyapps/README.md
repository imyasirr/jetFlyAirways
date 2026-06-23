# JetFly Mobile App (Flutter)

JetFly Airways ka official mobile app — Laravel backend ke saath connected.

## Setup

### 1. Flutter install karein
Download: https://docs.flutter.dev/get-started/install/windows

### 2. Platform folders generate karein (pehli baar)
```powershell
cd jetflyapps
.\setup.ps1
```

Ya manually:
```powershell
flutter create . --org com.jetflyairways --project-name jetflyapps
flutter pub get
```

### 3. Laravel server chalayein
```powershell
cd ..
php artisan serve --host=0.0.0.0 --port=8000
```

### 4. API URL configure karein
`lib/config/api_config.dart` mein apna server URL set karein:

| Device | URL |
|--------|-----|
| Android Emulator | `http://10.0.2.2:8000` |
| iOS Simulator | `http://127.0.0.1:8000` |
| Physical Phone | `http://YOUR_PC_IP:8000` |

Run karte waqt override:
```powershell
flutter run --dart-define=API_BASE_URL=http://192.168.1.5:8000
```

### 5. App run karein
```powershell
flutter run
```

## Features

- Home screen with 8 travel modules
- Search & browse (flights, hotels, packages, buses, trains, cabs, visa, insurance)
- Detail view + booking form
- Razorpay payment integration
- Login (email/password + OTP)
- Register with referral code
- My Bookings + cancel
- Wishlist
- Account profile

## API Endpoints

Base URL: `{APP_URL}/api/v1`

| Method | Endpoint | Auth |
|--------|----------|------|
| GET | `/home` | No |
| GET | `/modules/{module}` | No |
| GET | `/modules/{module}/{item}` | No |
| POST | `/modules/{module}/{item}/book` | Optional |
| POST | `/auth/login` | No |
| POST | `/auth/register` | No |
| POST | `/auth/otp/send` | No |
| POST | `/auth/otp/verify` | No |
| GET | `/auth/user` | Yes |
| GET | `/account/bookings` | Yes |
| POST | `/bookings/{id}/payment/order` | Optional |
| POST | `/payments/verify` | Optional |

## Razorpay

Admin panel ya `.env` mein Razorpay keys set karein:
```
RAZORPAY_KEY=rzp_test_xxx
RAZORPAY_SECRET=xxx
```

## Test Account

Website se register karein ya API se:
```
POST /api/v1/auth/register
{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```
