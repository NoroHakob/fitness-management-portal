DROP TABLE IF EXISTS exercises CASCADE;

CREATE TABLE exercises (
  id SERIAL PRIMARY KEY,
  name TEXT NOT NULL,
  category TEXT NOT NULL,
  difficulty TEXT NOT NULL CHECK (difficulty IN ('Beginner', 'Intermediate', 'Advanced')),
  description TEXT NOT NULL,
  image_path TEXT DEFAULT '',
  video_link TEXT DEFAULT ''
);
 
DROP TABLE IF EXISTS users CASCADE;

CREATE TABLE users (
  id SERIAL PRIMARY KEY,  
  email TEXT UNIQUE NOT NULL,
  password_hash TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS workout_plans CASCADE;
DROP TABLE IF EXISTS plan_exercises;

CREATE TABLE workout_plans (
  id SERIAL PRIMARY KEY,
  user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  name TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE plan_exercises (
  plan_id INT NOT NULL REFERENCES workout_plans(id) ON DELETE CASCADE,
  exercise_id INT NOT NULL REFERENCES exercises(id) ON DELETE CASCADE,
  PRIMARY KEY (plan_id, exercise_id)
);

DROP TABLE IF EXISTS workout_logs;

CREATE TABLE workout_logs (
  id SERIAL PRIMARY KEY,
  user_id INT REFERENCES users(id) ON DELETE CASCADE,
  plan_id INT REFERENCES workout_plans(id) ON DELETE CASCADE,
  exercise_id INT REFERENCES exercises(id) ON DELETE CASCADE,
  reps INT NOT NULL,
  log_date DATE NOT NULL DEFAULT CURRENT_DATE
);