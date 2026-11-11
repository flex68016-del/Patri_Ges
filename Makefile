.PHONY: up down restart build logs clean install-auth install-patrimoine install-operations install-web test-auth test-patrimoine test-operations test-web

# Démarrer tous les services
up:
	docker compose up -d

# Arrêter tous les services
down:
	docker compose down

# Redémarrer tous les services
restart:
	docker compose restart

# Reconstruire les images
build:
	docker compose build

# Voir les logs
logs:
	docker compose logs -f

# Nettoyer tout (conteneurs, volumes, réseaux)
clean:
	docker compose down -v

# Installer les dépendances auth-service
install-auth:
	docker compose exec auth-service composer install

# Installer les dépendances patrimoine-service
install-patrimoine:
	docker compose exec patrimoine-service composer install

# Installer les dépendances operations-service
install-operations:
	docker compose exec operations-service composer install

# Installer les dépendances web
install-web:
	docker compose exec web composer install

# Exécuter les migrations auth-service
migrate-auth:
	docker compose exec auth-service php artisan migrate

# Exécuter les migrations patrimoine-service
migrate-patrimoine:
	docker compose exec patrimoine-service php artisan migrate

# Exécuter les migrations operations-service
migrate-operations:
	docker compose exec operations-service php artisan migrate

# Exécuter les migrations web
migrate-web:
	docker compose exec web php artisan migrate

# Exécuter les seeders auth-service
seed-auth:
	docker compose exec auth-service php artisan db:seed

# Exécuter les seeders patrimoine-service
seed-patrimoine:
	docker compose exec patrimoine-service php artisan db:seed

# Exécuter les seeders operations-service
seed-operations:
	docker compose exec operations-service php artisan db:seed

# Exécuter les seeders web
seed-web:
	docker compose exec web php artisan db:seed

# Tests auth-service
test-auth:
	docker compose exec auth-service php artisan test

# Tests patrimoine-service
test-patrimoine:
	docker compose exec patrimoine-service php artisan test

# Tests operations-service
test-operations:
	docker compose exec operations-service php artisan test

# Tests web
test-web:
	docker compose exec web php artisan test

# Lint PHP (Pint)
lint-auth:
	docker compose exec auth-service ./vendor/bin/pint

lint-patrimoine:
	docker compose exec patrimoine-service ./vendor/bin/pint

lint-operations:
	docker compose exec operations-service ./vendor/bin/pint

lint-web:
	docker compose exec web ./vendor/bin/pint

# Analyse statique (Larastan)
stan-auth:
	docker compose exec auth-service ./vendor/bin/phpstan analyse

stan-patrimoine:
	docker compose exec patrimoine-service ./vendor/bin/phpstan analyse

stan-operations:
	docker compose exec operations-service ./vendor/bin/phpstan analyse

stan-web:
	docker compose exec web ./vendor/bin/phpstan analyse

# Générer les clés JWT
generate-jwt-keys:
	@echo "Pour générer les clés JWT :"
	@echo "Windows : scripts\generate-jwt-keys.bat"
	@echo "Linux/Mac : bash scripts/generate-jwt-keys.sh"
