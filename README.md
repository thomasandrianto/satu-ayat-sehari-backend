# Satu Ayat Sehari Backend

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![Filament](https://img.shields.io/badge/Filament-3-orange)
![MySQL](https://img.shields.io/badge/MySQL-8-blue)
![Firebase](https://img.shields.io/badge/Firebase-FCM-yellow)

Laravel backend powering the **Satu Ayat Sehari** Android application.

This project provides a RESTful API, an administrative CMS built with Filament, scheduled push notifications through Firebase Cloud Messaging (FCM), and a scalable queue-based notification architecture for delivering daily Bible verses and reflections.

---

## Overview

**Satu Ayat Sehari** is a devotional application designed around a simple principle:

> **One day. One Bible verse. One reflection.**

This backend is the second generation of the project. The original version was built using Supabase, while this version was completely rebuilt with Laravel to provide better maintainability, flexibility, and long-term scalability.

The backend is responsible for:

- Daily devotion management
- Device registration
- REST API for Flutter
- Scheduled push notifications
- Queue-based notification delivery
- Administrative CMS using Filament

---

## Tech Stack

- Laravel 12
- PHP 8.2
- Filament 3
- MySQL
- Firebase Cloud Messaging (FCM)
- Laravel Queue
- Laravel Scheduler

---

## Features

- REST API for Flutter application
- Daily devotion management
- Device registration
- Firebase Cloud Messaging integration
- Queue-based push notification delivery
- Filament CMS
- Feature tests
- Scheduled notification automation

---

## Project Structure

```
app/
├── Console/
├── Filament/
├── Http/
├── Jobs/
├── Models/
├── Services/

database/
routes/
tests/
```

---

## System Architecture

```
Flutter Android App
         │
         ▼
Laravel REST API
         │
         ▼
MySQL Database
         │
         ├────────────► Filament CMS
         │
         ▼
Laravel Queue
         │
         ▼
Firebase Cloud Messaging
         │
         ▼
Android Device
```

---

## API Endpoints

### Devotions

| Method | Endpoint               |
| ------ | ---------------------- |
| GET    | `/api/devotion/today`  |
| GET    | `/api/devotion/{date}` |
| GET    | `/api/devotions`       |

### Devices

| Method | Endpoint               |
| ------ | ---------------------- |
| POST   | `/api/device/register` |
| POST   | `/api/device/ping`     |

---

## Scheduled Notification Flow

Every day at **06:00 (Asia/Jakarta)** Laravel Scheduler executes a command that dispatches a Queue Job responsible for sending push notifications through Firebase Cloud Messaging.

```
Scheduler
      │
      ▼
Artisan Command
      │
      ▼
Queue Job
      │
      ▼
Firebase Cloud Messaging
      │
      ▼
Registered Devices
```

---

## Installation

Clone the repository.

```bash
git clone https://github.com/thomasandrianto/satu-ayat-sehari-backend.git
```

Install dependencies.

```bash
composer install
```

Copy the environment file.

```bash
cp .env.example .env
```

Generate the application key.

```bash
php artisan key:generate
```

Configure:

- Database connection
- Firebase credentials

Run migrations and seed the admin account.

```bash
php artisan migrate --seed
```

Start the development server.

```bash
php artisan serve
```

---

## Firebase Configuration

Place your Firebase Service Account file at:

```
storage/app/firebase/firebase.json
```

Configure the environment variable:

```
FIREBASE_CREDENTIALS=storage/app/firebase/firebase.json
```

> **Important**
>
> Never commit your Firebase Service Account JSON file into the repository.

---

## Testing

Run all feature tests.

```bash
php artisan test
```

---

## Companion Project

Flutter Android client repository:

> Coming soon.

Google Play:

https://play.google.com/store/apps/details?id=net.thomas.app_satu_ayat

---

## Future Improvements

- Notification analytics
- Multi-language support
- Rich push notifications
- API versioning
- User authentication
- Dashboard analytics

---

## License

This project was developed as a personal learning project and portfolio application.
