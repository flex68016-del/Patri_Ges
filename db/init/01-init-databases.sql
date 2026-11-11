-- Création des bases de données pour chaque service
CREATE DATABASE auth_db;
CREATE DATABASE patrimoine_db;
CREATE DATABASE operations_db;

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

-- Accorder les droits sur le schéma public pour chaque utilisateur
\c auth_db
GRANT ALL ON SCHEMA public TO auth_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO auth_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO auth_user;

\c patrimoine_db
GRANT ALL ON SCHEMA public TO patrimoine_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO patrimoine_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO patrimoine_user;

\c operations_db
GRANT ALL ON SCHEMA public TO operations_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO operations_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO operations_user;
