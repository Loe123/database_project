INSERT INTO department (dept_name)
VALUES
('資訊工程系'),
('電機工程系'),
('機械工程系');


INSERT INTO teacher (teacher_id, teacher_name, dept_id, email, password)
VALUES
('T001', '張老師', 1, 't001@school.com', 'password1'),
('T002', '李老師', 2, 't002@school.com', 'password2'),
('T003', '王老師', 3, 't003@school.com', 'password3');


INSERT INTO student (student_id, student_name, dept_id, email, password, total_credits)
VALUES
-- 資訊工程系 (dept_id = 1)
('S001', '張三', 1, 's001@school.com', 'password1', 0),
('S002', '李雷', 1, 's002@school.com', 'password2', 0),
('S003', '王芳', 1, 's003@school.com', 'password3', 0),

-- 電機工程系 (dept_id = 2)
('S004', '趙四', 2, 's004@school.com', 'password4', 0),
('S005', '孫五', 2, 's005@school.com', 'password5', 0),
('S006', '周六', 2, 's006@school.com', 'password6', 0),

-- 機械工程系 (dept_id = 3)
('S007', '錢七', 3, 's007@school.com', 'password7', 0),
('S008', '宋八', 3, 's008@school.com', 'password8', 0),
('S009', '朱九', 3, 's009@school.com', 'password9', 0);

INSERT INTO classroom (classroom_id, building, capacity)
VALUES
-- A棟
('A501', 'A', 40),  -- A棟5樓第1間教室，容納 40 人
('A502', 'A', 35),  -- A棟5樓第2間教室，容納 35 人
('A503', 'A', 45),  -- A棟5樓第3間教室，容納 45 人

-- B棟
('B101', 'B', 30),  -- B棟1樓第1間教室，容納 30 人
('B102', 'B', 50),  -- B棟1樓第2間教室，容納 50 人
('B103', 'B', 40),  -- B棟1樓第3間教室，容納 40 人

-- C棟
('C301', 'C', 60),  -- C棟3樓第1間教室，容納 60 人
('C302', 'C', 30),  -- C棟3樓第2間教室，容納 30 人
('C303', 'C', 25);  -- C棟3樓第3間教室，容納 25 人


INSERT INTO timeslot (timeslot_id, start_time, end_time, weekday)
VALUES
-- 星期一 (Mon)
('Mon_08~09', '08:00:00', '09:00:00', 'Mon'),
('Mon_09~10', '09:00:00', '10:00:00', 'Mon'),
('Mon_10~11', '10:00:00', '11:00:00', 'Mon'),
('Mon_11~12', '11:00:00', '12:00:00', 'Mon'),
('Mon_12~13', '12:00:00', '13:00:00', 'Mon'),
('Mon_13~14', '13:00:00', '14:00:00', 'Mon'),
('Mon_14~15', '14:00:00', '15:00:00', 'Mon'),
('Mon_15~16', '15:00:00', '16:00:00', 'Mon'),
('Mon_16~17', '16:00:00', '17:00:00', 'Mon'),
('Mon_17~18', '17:00:00', '18:00:00', 'Mon'),
('Mon_18~19', '18:00:00', '19:00:00', 'Mon'),
('Mon_19~20', '19:00:00', '20:00:00', 'Mon'),

-- 星期二 (Tue)
('Tue_08~09', '08:00:00', '09:00:00', 'Tue'),
('Tue_09~10', '09:00:00', '10:00:00', 'Tue'),
('Tue_10~11', '10:00:00', '11:00:00', 'Tue'),
('Tue_11~12', '11:00:00', '12:00:00', 'Tue'),
('Tue_12~13', '12:00:00', '13:00:00', 'Tue'),
('Tue_13~14', '13:00:00', '14:00:00', 'Tue'),
('Tue_14~15', '14:00:00', '15:00:00', 'Tue'),
('Tue_15~16', '15:00:00', '16:00:00', 'Tue'),
('Tue_16~17', '16:00:00', '17:00:00', 'Tue'),
('Tue_17~18', '17:00:00', '18:00:00', 'Tue'),
('Tue_18~19', '18:00:00', '19:00:00', 'Tue'),
('Tue_19~20', '19:00:00', '20:00:00', 'Tue'),

-- 星期三 (Wed)
('Wed_08~09', '08:00:00', '09:00:00', 'Wed'),
('Wed_09~10', '09:00:00', '10:00:00', 'Wed'),
('Wed_10~11', '10:00:00', '11:00:00', 'Wed'),
('Wed_11~12', '11:00:00', '12:00:00', 'Wed'),
('Wed_12~13', '12:00:00', '13:00:00', 'Wed'),
('Wed_13~14', '13:00:00', '14:00:00', 'Wed'),
('Wed_14~15', '14:00:00', '15:00:00', 'Wed'),
('Wed_15~16', '15:00:00', '16:00:00', 'Wed'),
('Wed_16~17', '16:00:00', '17:00:00', 'Wed'),
('Wed_17~18', '17:00:00', '18:00:00', 'Wed'),
('Wed_18~19', '18:00:00', '19:00:00', 'Wed'),
('Wed_19~20', '19:00:00', '20:00:00', 'Wed'),

-- 星期四 (Thu)
('Thu_08~09', '08:00:00', '09:00:00', 'Thu'),
('Thu_09~10', '09:00:00', '10:00:00', 'Thu'),
('Thu_10~11', '10:00:00', '11:00:00', 'Thu'),
('Thu_11~12', '11:00:00', '12:00:00', 'Thu'),
('Thu_12~13', '12:00:00', '13:00:00', 'Thu'),
('Thu_13~14', '13:00:00', '14:00:00', 'Thu'),
('Thu_14~15', '14:00:00', '15:00:00', 'Thu'),
('Thu_15~16', '15:00:00', '16:00:00', 'Thu'),
('Thu_16~17', '16:00:00', '17:00:00', 'Thu'),
('Thu_17~18', '17:00:00', '18:00:00', 'Thu'),
('Thu_18~19', '18:00:00', '19:00:00', 'Thu'),
('Thu_19~20', '19:00:00', '20:00:00', 'Thu'),

-- 星期五 (Fri)
('Fri_08~09', '08:00:00', '09:00:00', 'Fri'),
('Fri_09~10', '09:00:00', '10:00:00', 'Fri'),
('Fri_10~11', '10:00:00', '11:00:00', 'Fri'),
('Fri_11~12', '11:00:00', '12:00:00', 'Fri'),
('Fri_12~13', '12:00:00', '13:00:00', 'Fri'),
('Fri_13~14', '13:00:00', '14:00:00', 'Fri'),
('Fri_14~15', '14:00:00', '15:00:00', 'Fri'),
('Fri_15~16', '15:00:00', '16:00:00', 'Fri'),
('Fri_16~17', '16:00:00', '17:00:00', 'Fri'),
('Fri_17~18', '17:00:00', '18:00:00', 'Fri'),
('Fri_18~19', '18:00:00', '19:00:00', 'Fri'),
('Fri_19~20', '19:00:00', '20:00:00', 'Fri'),

-- 星期六 (Sat)
('Sat_08~09', '08:00:00', '09:00:00', 'Sat'),
('Sat_09~10', '09:00:00', '10:00:00', 'Sat'),
('Sat_10~11', '10:00:00', '11:00:00', 'Sat'),
('Sat_11~12', '11:00:00', '12:00:00', 'Sat'),
('Sat_12~13', '12:00:00', '13:00:00', 'Sat'),
('Sat_13~14', '13:00:00', '14:00:00', 'Sat'),
('Sat_14~15', '14:00:00', '15:00:00', 'Sat'),
('Sat_15~16', '15:00:00', '16:00:00', 'Sat'),
('Sat_16~17', '16:00:00', '17:00:00', 'Sat'),
('Sat_17~18', '17:00:00', '18:00:00', 'Sat'),
('Sat_18~19', '18:00:00', '19:00:00', 'Sat'),
('Sat_19~20', '19:00:00', '20:00:00', 'Sat'),

-- 星期日 (Sun)
('Sun_08~09', '08:00:00', '09:00:00', 'Sun'),
('Sun_09~10', '09:00:00', '10:00:00', 'Sun'),
('Sun_10~11', '10:00:00', '11:00:00', 'Sun'),
('Sun_11~12', '11:00:00', '12:00:00', 'Sun'),
('Sun_12~13', '12:00:00', '13:00:00', 'Sun'),
('Sun_13~14', '13:00:00', '14:00:00', 'Sun'),
('Sun_14~15', '14:00:00', '15:00:00', 'Sun'),
('Sun_15~16', '15:00:00', '16:00:00', 'Sun'),
('Sun_16~17', '16:00:00', '17:00:00', 'Sun'),
('Sun_17~18', '17:00:00', '18:00:00', 'Sun'),
('Sun_18~19', '18:00:00', '19:00:00', 'Sun'),
('Sun_19~20', '19:00:00', '20:00:00', 'Sun');


INSERT INTO course (course_id, course_name, credits, dept_id, teacher_id, classroom_id, is_open, course_type)
VALUES
('CSE101', '計算機科學概論', 3, 1, 'T001', 'A501', TRUE, '必修'),
('CSE102', '資料結構', 3, 1, 'T001', 'A502', TRUE, '必修'),
('EE101', '電路學', 3, 2, 'T002', 'B101', TRUE, '必修'),
('EE102', '電子學', 3, 2, 'T002', 'B102', TRUE, '選修'),
('ME101', '力學', 3, 3, 'T003', 'C301', TRUE, '必修'),
('ME102', '材料力學', 3, 3, 'T003', 'C302', TRUE, '選修');

INSERT INTO enrollment (student_id, course_id)
VALUES
('S001', 'CSE101'),
('S001', 'CSE102'),
('S002', 'CSE101'),
('S003', 'CSE102'),
('S004', 'EE101'),
('S005', 'EE102'),
('S006', 'EE101'),
('S007', 'ME101'),
('S008', 'ME102');

INSERT INTO course_timeslot (course_id, timeslot_id)
VALUES
    ('CSE101', 'Mon_09~10'),
    ('CSE101', 'Mon_10~11'),
    ('CSE101', 'Mon_11~12');

-- 將課程 CSE102 的時段安排插入
INSERT INTO course_timeslot (course_id, timeslot_id)
VALUES
    ('CSE102', 'Tue_09~10'),
    ('CSE102', 'Tue_10~11');

-- 將課程 EE101 的時段安排插入
INSERT INTO course_timeslot (course_id, timeslot_id)
VALUES
    ('EE101', 'Mon_09~10'),
    ('EE101', 'Mon_10~11'),
    ('EE101', 'Mon_11~12');

-- 將課程 EE102 的時段安排插入
INSERT INTO course_timeslot (course_id, timeslot_id)
VALUES
    ('EE102', 'Tue_09~10'),
    ('EE102', 'Tue_10~11');

-- 將課程 ME101 的時段安排插入
INSERT INTO course_timeslot (course_id, timeslot_id)
VALUES
    ('ME101', 'Wed_09~10'),
    ('ME101', 'Wed_10~11');

-- 將課程 ME102 的時段安排插入
INSERT INTO course_timeslot (course_id, timeslot_id)
VALUES
    ('ME102', 'Thu_09~10'),
    ('ME102', 'Thu_10~11');

INSERT INTO admin (admin_id, admin_name, email, password)
VALUES ('admin001', '李邦任', 'admin@admin.com', 'adminpassword');