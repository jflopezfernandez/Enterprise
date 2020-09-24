
-- Create the production database.
-- createdb enterprise
-- CREATE ROLE enterprise NOLOGIN;
-- CREATE ROLE enterprisedb LOGIN PASSWORD 'enterprisedb';
-- GRANT enterprise TO jflopezfernandez, enterprisedb;
-- ALTER DATABASE enterprise OWNER TO enterprise;
-- GRANT ALL PRIVILEGES ON DATABASE enterprise TO enterprise;

-- Create the testing database.
-- createdb -O enterprise enterprisetestdb "Enterprise testing database"

-- Create the development database.
-- createdb -O enterprise enterprisedevdb "Enterprise development database"

CREATE TABLE IF NOT EXISTS employees(
    id SERIAL PRIMARY KEY,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL
);

ALTER TABLE employees OWNER TO enterprise;

INSERT INTO employees(first_name, last_name)
    VALUES
        ('James', 'Jones'),
        ('Alfred', 'Morris'),
        ('Jenna', 'Clayborne');

SELECT id AS "ID", first_name AS "First Name", last_name AS "Last Name" FROM employees;
