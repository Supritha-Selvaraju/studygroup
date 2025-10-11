-- Create Database
CREATE DATABASE IF NOT EXISTS studygroup_db;
USE studygroup_db;

-- Departments (lookup table for dropdown)
CREATE TABLE IF NOT EXISTS departments (
  department_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
);

-- Users (students)
CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(100) NOT NULL,
  roll_no VARCHAR(20) NOT NULL UNIQUE,
  department_id INT NOT NULL,
  year INT NOT NULL,
  FOREIGN KEY (department_id) REFERENCES departments(department_id)
);

-- Subjects (linked to department + year + semester)
CREATE TABLE IF NOT EXISTS subjects (
  subject_id INT AUTO_INCREMENT PRIMARY KEY,
  department_id INT NOT NULL,
  year INT NOT NULL,
  semester INT NOT NULL,
  subject_code VARCHAR(20) NOT NULL,
  subject_name VARCHAR(150) NOT NULL,
  subject_type VARCHAR(50),
  UNIQUE (department_id, year, semester, subject_code),
  FOREIGN KEY (department_id) REFERENCES departments(department_id)
);

-- Study Groups (one group per subject)
CREATE TABLE IF NOT EXISTS study_groups (
  group_id INT AUTO_INCREMENT PRIMARY KEY,
  subject_id INT UNIQUE NOT NULL,
  group_name VARCHAR(200) NOT NULL,
  created_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
  FOREIGN KEY (created_by) REFERENCES users(user_id)
);

-- Group Members
CREATE TABLE IF NOT EXISTS group_members (
  group_id INT NOT NULL,
  user_id INT NOT NULL,
  joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (group_id, user_id),
  FOREIGN KEY (group_id) REFERENCES study_groups(group_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Messages (Core feature for industry study groups)
CREATE TABLE IF NOT EXISTS messages (
  message_id INT AUTO_INCREMENT PRIMARY KEY,
  group_id INT NOT NULL,
  user_id INT NOT NULL,
  message_text TEXT NOT NULL,
  timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (group_id) REFERENCES study_groups(group_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Materials (Resource sharing)
CREATE TABLE IF NOT EXISTS materials (
  material_id INT AUTO_INCREMENT PRIMARY KEY,
  group_id INT NOT NULL,
  uploaded_by INT NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  file_type VARCHAR(50),
  description VARCHAR(255),
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (group_id) REFERENCES study_groups(group_id) ON DELETE CASCADE,
  FOREIGN KEY (uploaded_by) REFERENCES users(user_id)
);

-- Study Sessions (Calendar/Events)
CREATE TABLE IF NOT EXISTS study_sessions (
  session_id INT AUTO_INCREMENT PRIMARY KEY,
  group_id INT NOT NULL,
  session_link VARCHAR(255) NOT NULL,
  scheduled_time DATETIME NOT NULL,
  created_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (group_id) REFERENCES study_groups(group_id) ON DELETE CASCADE,
  FOREIGN KEY (created_by) REFERENCES users(user_id)
);

-- Extra Feature: Academic Progress Tracking (Simple notes/status)
CREATE TABLE IF NOT EXISTS academic_notes (
    note_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject_id INT, -- Can be linked to a subject or general
    note_title VARCHAR(255) NOT NULL,
    note_content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS upvotes (
  message_id INT NOT NULL,
  user_id INT NOT NULL,
  PRIMARY KEY (message_id, user_id),
  FOREIGN KEY (message_id) REFERENCES messages(message_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
