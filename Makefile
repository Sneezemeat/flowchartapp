start:
	./script.sh composer install -n
	./script.sh apt-get update
	./script.sh apt-get upgrade
	./script.sh apt-get install npm
	./script.sh npm install
	./script.sh npm run dev

start-dev:
	make start
	./script.sh bin/console doctrine:database:create
	make migrate

migrate:
	./script.sh bin/console doctrine:migrations:migrate

shell:
	./shell.sh
