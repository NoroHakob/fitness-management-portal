-- ========================
-- 🚫 Drop Tables if Exist
-- ========================
DROP TABLE IF EXISTS logs;
DROP TABLE IF EXISTS workout_logs;
DROP TABLE IF EXISTS plan_exercises;
DROP TABLE IF EXISTS workout_plans;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS exercises;
DROP TABLE IF EXISTS settings;


-- ========================
-- 📦 Table Definitions
-- ========================

-- 📋 Exercises Table
CREATE TABLE exercises (
  id SERIAL PRIMARY KEY,
  name TEXT NOT NULL,
  category TEXT NOT NULL,
  difficulty TEXT NOT NULL CHECK (difficulty IN ('Beginner', 'Intermediate', 'Advanced')),
  description TEXT NOT NULL,
  image_path TEXT DEFAULT '',
  video_link TEXT DEFAULT ''
);

-- 👤 Users Table
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  email TEXT UNIQUE NOT NULL,
  password_hash TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- 🗓️ Workout Plans Table
CREATE TABLE workout_plans (
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  name TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 📌 Plan Exercises Table (Join)
CREATE TABLE plan_exercises (
  plan_id INT NOT NULL REFERENCES workout_plans(id) ON DELETE CASCADE,
  exercise_id INT NOT NULL REFERENCES exercises(id) ON DELETE CASCADE,
  PRIMARY KEY (plan_id, exercise_id)
);

-- 📝 Workout Logs Table
CREATE TABLE workout_logs (
  id SERIAL PRIMARY KEY,
  user_id INT REFERENCES users(id) ON DELETE CASCADE,
  plan_id INT REFERENCES workout_plans(id) ON DELETE CASCADE,
  exercise_id INT REFERENCES exercises(id) ON DELETE CASCADE,
  reps INT NOT NULL,
  log_date DATE NOT NULL DEFAULT CURRENT_DATE
);

-- 📚 Logs Table (for trigger)
CREATE TABLE logs (
  id SERIAL PRIMARY KEY,
  action TEXT,
  target_table TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 🔒 Settings Table for Lockdown
CREATE TABLE settings (
  keyword TEXT PRIMARY KEY,
  value TEXT NOT NULL
);

-- 🔒 Insert default lockdown setting
INSERT INTO settings (keyword, value)
VALUES ('lockdown', 'off');

-- =====================================
-- ⚡ Trigger: Log New Exercise Insert
-- =====================================

-- 🔧 Trigger Function
CREATE OR REPLACE FUNCTION log_new_exercise()
RETURNS TRIGGER AS $$
BEGIN
  INSERT INTO logs(action, target_table)
  VALUES ('INSERT', 'exercises');
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- 🚨 Trigger on INSERT into exercises
CREATE TRIGGER trg_log_exercise_insert
AFTER INSERT ON exercises
FOR EACH ROW
EXECUTE FUNCTION log_new_exercise();

-- =====================================
-- 📊 Function: Get Average Reps
-- =====================================
-- 🔍 Returns average reps for an exercise (optionally by user)

CREATE OR REPLACE FUNCTION get_avg_reps(ex_id INT, u_id INT DEFAULT NULL)
RETURNS NUMERIC AS $$
BEGIN
  IF u_id IS NULL THEN
    RETURN (SELECT AVG(reps)::NUMERIC FROM workout_logs WHERE exercise_id = ex_id);
  ELSE
    RETURN (SELECT AVG(reps)::NUMERIC FROM workout_logs WHERE exercise_id = ex_id AND user_id = u_id);
  END IF;
END;
$$ LANGUAGE plpgsql;

-- =====================================
-- 🏋️ Procedure: Log Workout Entry
-- =====================================
-- 🛠️ Adds a workout log record

CREATE OR REPLACE PROCEDURE log_workout(
  u_id INT,
  p_id INT,
  e_id INT,
  r INT,
  l_date DATE DEFAULT CURRENT_DATE
)
LANGUAGE plpgsql
AS $$
BEGIN
  INSERT INTO workout_logs(user_id, plan_id, exercise_id, reps, log_date)
  VALUES (u_id, p_id, e_id, r, l_date);
END;
$$;

-- DROP TABLE IF EXISTS exercises CASCADE;

-- CREATE TABLE exercises (
--   id SERIAL PRIMARY KEY,
--   name TEXT NOT NULL,
--   category TEXT NOT NULL,
--   difficulty TEXT NOT NULL CHECK (difficulty IN ('Beginner', 'Intermediate', 'Advanced')),
--   description TEXT NOT NULL,
--   image_path TEXT DEFAULT '',
--   video_link TEXT DEFAULT ''
-- );
 
-- DROP TABLE IF EXISTS users CASCADE;

-- CREATE TABLE users (
--   id SERIAL PRIMARY KEY,  
--   email TEXT UNIQUE NOT NULL,
--   password_hash TEXT NOT NULL,
--   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- DROP TABLE IF EXISTS workout_plans CASCADE;
-- DROP TABLE IF EXISTS plan_exercises;

-- CREATE TABLE workout_plans (
--   id SERIAL PRIMARY KEY,
--   user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
--   name TEXT NOT NULL,
--   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- CREATE TABLE plan_exercises (
--   plan_id INT NOT NULL REFERENCES workout_plans(id) ON DELETE CASCADE,
--   exercise_id INT NOT NULL REFERENCES exercises(id) ON DELETE CASCADE,
--   PRIMARY KEY (plan_id, exercise_id)
-- );

-- DROP TABLE IF EXISTS workout_logs;

-- CREATE TABLE workout_logs (
--   id SERIAL PRIMARY KEY,
--   user_id INT REFERENCES users(id) ON DELETE CASCADE,
--   plan_id INT REFERENCES workout_plans(id) ON DELETE CASCADE,
--   exercise_id INT REFERENCES exercises(id) ON DELETE CASCADE,
--   reps INT NOT NULL,
--   log_date DATE NOT NULL DEFAULT CURRENT_DATE
-- );