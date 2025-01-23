CREATE DATABASE IF NOT EXISTS youdemy;
USE youdemy;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  email VARCHAR(100),
  password VARCHAR(255),
  role ENUM('student','teacher','admin') DEFAULT 'student',
  is_approved TINYINT(1) DEFAULT 0,
  status ENUM('active','suspended') DEFAULT 'active'
);

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  teacher_id INT,
  title VARCHAR(255),
  description TEXT,
  content TEXT,
  category_id INT,
  format ENUM('document','video') DEFAULT 'document',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS enrollments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT,
  course_id INT,
  enrolled_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_enrollment (student_id, course_id),
  FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS course_tags (
  course_id INT,
  tag_id INT,
  PRIMARY KEY (course_id, tag_id),
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

INSERT INTO users (username, email, password, role, is_approved, status)
VALUES ('admin', 'admin@admin.com', MD5('admin'), 'admin', 1, 'active');

INSERT INTO users (username, email, password, role, is_approved, status)
VALUES ('student1', 'student1@mail.com', MD5('student'), 'student', 1, 'active');

INSERT INTO users (username, email, password, role, is_approved, status)
VALUES ('teacher1', 'teacher1@mail.com', MD5('teacher'), 'teacher', 1, 'active');

INSERT INTO users (username, email, password, role, is_approved, status)
VALUES ('teacher2', 'teacher2@mail.com', MD5('teacher2'), 'teacher', 0, 'active');

INSERT INTO users (username, email, password, role, is_approved, status)
VALUES ('suspended_student', 'suspended@student.com', MD5('suspended'), 'student', 1, 'suspended');

INSERT INTO categories (name)
VALUES ('Programming'), ('Business'), ('Math'), ('Art');

INSERT INTO tags (name) VALUES ('PHP'), ('Java'), ('Finance'), ('Painting');

INSERT INTO courses (teacher_id, title, description, content, category_id, format)
VALUES (3, 'Intro to PHP', 'Learn basic PHP', 'Video content or code here', 1, 'video');

INSERT INTO course_tags (course_id, tag_id) VALUES (1, 1);
