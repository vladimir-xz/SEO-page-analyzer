-- DROP TABLE IF EXISTS urls;
-- DROP TABLE IF EXISTS url_checks;

CREATE TABLE urls (
    id BIGINT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    name VARCHAR(255),
    created_at TIMESTAMP
);

CREATE TABLE url_checks (
    id BIGINT PRIMARY KEY GENERATED ALWAYS AS IDENTITY,
    url_id BIGINT,
    status_code BIGINT,
    h1 TEXT,
    title TEXT,
    description TEXT,
    created_at TIMESTAMP
);