DROP TABLE IF EXISTS urls;
DROP TABLE IF EXISTS url_checks;

CREATE TABLE urls (
    id BIGINT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    name VARCHAR(50),
    created_at TIMESTAMP
);

CREATE TABLE url_checks (
    id BIGINT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    url_id BIGINT,
    status_code BIGINT,
    h1 VARCHAR(50),
    title VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP
);