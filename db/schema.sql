DROP TABLE IF EXISTS exercises;

CREATE TABLE exercises (
  id SERIAL PRIMARY KEY,
  name TEXT NOT NULL,
  category TEXT NOT NULL,
  difficulty TEXT NOT NULL CHECK (difficulty IN ('Beginner', 'Intermediate', 'Advanced')),
  description TEXT NOT NULL,
  image_path TEXT DEFAULT '',
  video_link TEXT DEFAULT ''
);
 
