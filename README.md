# CampusConnect – Student Activity & Event Management System

## Technology
- PHP 8.x
- MySQL / MariaDB
- HTML5 + CSS3
- PDO prepared statements
- PHP sessions

## Features
### Student
- Registration, login and logout
- Student dashboard with student name
- Upcoming event listing and search
- Event details and online registration
- Cancel registration
- My Registered Events
- Campus announcements
- Profile update

### Administrator
- Admin login
- Dashboard statistics
- Add / edit / delete events
- View and search registered students
- Add / edit / delete announcements

## XAMPP Installation
1. Start Apache and MySQL in XAMPP.
2. Copy the `CampusConnect` folder into `C:\xampp\htdocs\`.
3. Open `http://localhost/phpmyadmin`.
4. Import `database.sql`.
5. Open `http://localhost/CampusConnect/`.

If your MySQL root account has a password, change the credentials in `config.php`.

## Demo Accounts
Student: `ekta@campusconnect.com` / `student123`

Admin: `admin@campusconnect.com` / `admin123`

## Project Structure
```
CampusConnect/
├── admin/
├── assets/
├── includes/
├── student/
├── config.php
├── database.sql
├── index.php
├── login.php
├── register.php
└── logout.php
```
