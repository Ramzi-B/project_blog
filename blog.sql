CREATE TABLE categories(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    categoryName VARCHAR(100) NOT NULL
) ENGINE=INNODB;

CREATE TABLE authors(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    authorName VARCHAR(100) NOT NULL,
    authorEmail VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL,
    modified_at DATETIME NULL
) ENGINE=INNODB;

CREATE TABLE comments(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL,
    modified_at DATETIME NULL,
    content TEXT NOT NULL,
    post_id UNSIGNED INT NOT NULL,
    CONSTRAINT fk_post
    FOREIGN KEY (post_id)
        REFERENCES posts(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE
) ENGINE=INNODB;

CREATE TABLE posts(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title varchar(100) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    modified_at DATETIME NULL,
    category_id INT UNSIGNED NOT NULL,
    author_id INT UNSIGNED NOT NULL,
    CONSTRAINT fk_category FOREIGN KEY (category_id)
        REFERENCES categories(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE,
    CONSTRAINT fk_author FOREIGN KEY (author_id)
        REFERENCES authors(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE
) ENGINE=INNODB;

CREATE TABLE admins(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL,
    modified_at DATETIME NULL
) ENGINE=INNODB;
