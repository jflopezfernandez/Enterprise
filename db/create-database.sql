
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

-- Sample query: Select all employees
-- SELECT id AS "ID", first_name AS "First Name", last_name AS "Last Name" FROM employees;

CREATE TABLE IF NOT EXISTS organizational_units(
    id SERIAL PRIMARY KEY,
    name TEXT UNIQUE NOT NULL,
    description TEXT,
    parent_unit integer NOT NULL DEFAULT 1,
    date_created timestamp NOT NULL DEFAULT NOW()
);

ALTER TABLE organizational_units OWNER TO enterprise;

INSERT INTO organizational_units(id, name, description, parent_unit)
    VALUES
        (1, 'Enterprise', 'Enterprise, Inc.', 0),
        (2, 'Human Resources', 'Human Resources', 1),
        (3, 'Legal', 'Legal', 1),
        (4, 'Investment Banking', 'Investment Banking', 1);

CREATE TABLE IF NOT EXISTS groups(
    id SERIAL PRIMARY KEY,
    name TEXT UNIQUE NOT NULL,
    description TEXT,
    date_created timestamp NOT NULL DEFAULT NOW()
);

ALTER TABLE groups OWNER TO enterprise;
