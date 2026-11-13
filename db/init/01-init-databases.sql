-- Création des bases de données pour chaque service
CREATE DATABASE auth_db;
CREATE DATABASE patrimoine_db;
CREATE DATABASE operations_db;
CREATE DATABASE web_db;

-- Création des utilisateurs avec droits limités

-- Utilisateur pour auth-service
CREATE USER auth_user WITH PASSWORD 'auth_password';
GRANT ALL PRIVILEGES ON DATABASE auth_db TO auth_user;

-- Utilisateur pour patrimoine-service
CREATE USER patrimoine_user WITH PASSWORD 'patrimoine_password';
GRANT ALL PRIVILEGES ON DATABASE patrimoine_db TO patrimoine_user;

-- Utilisateur pour operations-service
CREATE USER operations_user WITH PASSWORD 'operations_password';
GRANT ALL PRIVILEGES ON DATABASE operations_db TO operations_user;

-- Utilisateur pour web
CREATE USER web_user WITH PASSWORD 'web_password';
GRANT ALL PRIVILEGES ON DATABASE web_db TO web_user;
