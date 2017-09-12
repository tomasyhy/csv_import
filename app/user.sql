CREATE TABLE IF NOT EXISTS User (
    username VARCHAR(20) NOT NULL,
    surname VARCHAR(15) NOT NULL,
    given_name VARCHAR(15) NOT NULL,
    number INT,
    gender VARCHAR(6),
    name_set VARCHAR(10),
    title VARCHAR(4),
    middle_initial CHAR,
    street_address VARCHAR(30),
    city VARCHAR(10),
    state VARCHAR(10),
    zip_code VARCHAR(6),
    country VARCHAR(10),
    email_address VARCHAR(20),
    password VARCHAR(32),
    browser_user_agent VARCHAR(20),
    UNIQUE KEY `UNIQE_USERNAME_SURNAME_GIVEN_NAME` (`username`,`surname`,`given_name`)
);