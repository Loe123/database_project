CREATE TABLE department (
    dept_id INT AUTO_INCREMENT PRIMARY KEY,
    dept_name VARCHAR(50) NOT NULL
);

CREATE TABLE teacher (
    teacher_id VARCHAR(10) PRIMARY KEY,
    teacher_name VARCHAR(100) NOT NULL,
    dept_id INT,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    FOREIGN KEY (dept_id) REFERENCES department(dept_id)
);

CREATE TABLE classroom (
    classroom_id VARCHAR(10) PRIMARY KEY,
    building VARCHAR(10) NOT NULL,
    capacity INT NOT NULL
);

CREATE TABLE timeslot (
    timeslot_id VARCHAR(10) PRIMARY KEY,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    weekday VARCHAR(3) NOT NULL
);

CREATE TABLE course (
    course_id VARCHAR(10) PRIMARY KEY,     -- 課程代碼 (主鍵)
    course_name VARCHAR(100) NOT NULL,     -- 課程名稱
    credits INT NOT NULL,                  -- 學分數
    course_type ENUM('必修', '選修') NOT NULL,  -- 課程類型 (必修/選修)
    dept_id INT,                           -- 開課系所
    classroom_id VARCHAR(10),              -- 教室
    teacher_id VARCHAR(10) NOT NULL,       -- 授課教師
    max_students INT DEFAULT 50,           -- 最大選課人數 (可選)
    is_open BOOLEAN DEFAULT TRUE,          -- 是否開放選課
    FOREIGN KEY (dept_id) REFERENCES department(dept_id),
    FOREIGN KEY (classroom_id) REFERENCES classroom(classroom_id),
    FOREIGN KEY (teacher_id) REFERENCES teacher(teacher_id)
);


CREATE TABLE course_timeslot (
    course_id VARCHAR(10),
    timeslot_id VARCHAR(10),
    PRIMARY KEY (course_id, timeslot_id),
    FOREIGN KEY (course_id) REFERENCES course(course_id),
    FOREIGN KEY (timeslot_id) REFERENCES timeslot(timeslot_id),
    CONSTRAINT unique_course_time UNIQUE (course_id, timeslot_id)
);

CREATE TABLE student (
    student_id VARCHAR(10) PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    dept_id INT,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    total_credits INT DEFAULT 0,
    FOREIGN KEY (dept_id) REFERENCES department(dept_id)
);

CREATE TABLE enrollment (
    student_id VARCHAR(10),
    course_id VARCHAR(10),
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (student_id, course_id),
    FOREIGN KEY (student_id) REFERENCES student(student_id),
    FOREIGN KEY (course_id) REFERENCES course(course_id)
);

CREATE TABLE admin (
    admin_id VARCHAR(10) PRIMARY KEY,  -- 管理員編號
    admin_name VARCHAR(100) NOT NULL,  -- 管理員名稱
    email VARCHAR(100) NOT NULL UNIQUE,  -- 管理員電子郵件，設為唯一
    password VARCHAR(255) NOT NULL  -- 密碼
);
