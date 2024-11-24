PRAGMA foreign_keys = ON;

CREATE TABLE client_id (
    client_id CHAR(32) NOT NULL,
    session_key CHAR(32) NOT NULL,
    last_visit_time BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (client_id),
    UNIQUE (client_id)
);

CREATE TABLE client_ip (
    client_ip VARCHAR(39) NOT NULL,
    client_id CHAR(32) NOT NULL,
    last_visit_time BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (client_ip, client_id),
    UNIQUE (client_ip, client_id)
    CONSTRAINT fk_cip_to_cid FOREIGN KEY (client_id) REFERENCES client_id (client_id) ON DELETE CASCADE
);

CREATE TABLE client_hash (
    client_hash CHAR(32) NOT NULL,
    client_id CHAR(32) NOT NULL,
    last_visit_time BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (client_hash, client_id),
    UNIQUE (client_hash, client_id)
    CONSTRAINT fk_chash_to_cid FOREIGN KEY (client_id) REFERENCES client_id (client_id) ON DELETE CASCADE
);

CREATE TABLE register_key (
    register_key VARCHAR(255) NOT NULL,
    client_id CHAR(32) DEFAULT NULL,
    register_time BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (register_key, register_time),
    UNIQUE (register_key, client_id),
    CONSTRAINT fk_rkey_to_cid FOREIGN KEY (client_id) REFERENCES client_id (client_id) ON DELETE SET NULL
);
