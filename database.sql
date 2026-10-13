CREATE DATABASE IF NOT EXISTS campusconnect CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE campusconnect;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS event_registrations;
DROP TABLE IF EXISTS announcements;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS admins;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE students (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    enrollment_no VARCHAR(30) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20) DEFAULT NULL,
    course VARCHAR(120) NOT NULL,
    semester TINYINT UNSIGNED NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    venue VARCHAR(150) NOT NULL,
    organizer VARCHAR(150) NOT NULL,
    max_seats INT UNSIGNED NOT NULL DEFAULT 100,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE event_registrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Registered', 'Cancelled') NOT NULL DEFAULT 'Registered',
    UNIQUE KEY unique_registration (event_id, student_id),
    CONSTRAINT fk_registration_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    CONSTRAINT fk_registration_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE announcements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    posted_by VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Demo passwords are generated with PHP password_hash(PASSWORD_DEFAULT).
INSERT INTO admins (name, email, password) VALUES
('CampusConnect Administrator', 'admin@campusconnect.com', '$2y$12$/wTVdVXAngCiyH.LhPnqFeMabskpnoBMlquBrk9uo8NByNTWRjCjS');

INSERT INTO students (student_name, enrollment_no, email, phone, course, semester, password) VALUES
('Ekta Parmar', 'JGUCS001', 'ekta@campusconnect.com', '9876543210', 'B.Tech Computer Science Engineering', 3, '$2y$12$zNlZcAuRokpkg67voW01c.n.UDu0z6QBecWNVZ14Vs9YTjhQVGWHa'),
('Riya Patel', 'JGUCS002', 'riya@campusconnect.com', '9876543211', 'B.Tech Computer Science Engineering', 3, '$2y$12$zNlZcAuRokpkg67voW01c.n.UDu0z6QBecWNVZ14Vs9YTjhQVGWHa'),
('Priya Shah', 'JGUCS003', 'priya@campusconnect.com', '9876543212', 'B.Tech Computer Science Engineering', 3, '$2y$12$zNlZcAuRokpkg67voW01c.n.UDu0z6QBecWNVZ14Vs9YTjhQVGWHa'),
('Neha Joshi', 'JGUCS004', 'neha@campusconnect.com', '9876543213', 'B.Tech Information Technology', 3, '$2y$12$zNlZcAuRokpkg67voW01c.n.UDu0z6QBecWNVZ14Vs9YTjhQVGWHa');

INSERT INTO events (title, description, event_date, event_time, venue, organizer, max_seats) VALUES
('TechFest 2026', 'A campus technology festival featuring coding challenges, project demonstrations and technical talks.', '2026-10-20', '10:00:00', 'Innovation Hall', 'Computer Science Department', 120),
('Web Development Workshop', 'Hands-on workshop covering HTML, CSS, JavaScript, PHP and MySQL.', '2026-10-24', '11:00:00', 'Computer Lab 2', 'Coding Club', 50),
('Inter-College Quiz Competition', 'General knowledge and technology quiz competition for college students.', '2026-11-05', '14:00:00', 'Seminar Hall', 'Student Council', 80),
('Cultural Evening', 'Music, dance and cultural performances by students from different departments.', '2026-11-15', '17:30:00', 'Open Air Theatre', 'Cultural Committee', 200);

INSERT INTO announcements (title, message, posted_by) VALUES
('Welcome to CampusConnect', 'Students can use CampusConnect to discover campus events and register online.', 'CampusConnect Admin'),
('TechFest Registration Open', 'Registration is now open for TechFest 2026. Seats are limited.', 'Computer Science Department'),
('Keep Your Profile Updated', 'Please make sure your phone number and course details are correct.', 'Student Affairs Office');
