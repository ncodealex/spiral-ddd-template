# App
install:
	php app.php app:install

refresh:
	rm app/migrations/*.php
	php app.php app:refresh

dump-prototype:
	php app.php prototype:dump

translate:
	php app.php i18n:index

######
#
#  DB
#
######
db-status:
	php app.php db:list

db-create-migration-from-entity:
	php app.php cycle:migrate

db-migration-status:
	php app.php migrate:status

db-migration-to-db:
	php app.php migrate

db-migration-rollback:
	php app.php migrate:rollback

seed:
	php app.php db:seed
######
#
#  Debug / Tests
#
######
debug:
	vendor/bin/trap -p1025 -p9912 -p9913 -p8000 --ui=8080

run-tests:
	./vendor/bin/phpunit

######
#
#  ROAD RUNNER
#
######
rr-start:
	./rr serve

rr-start-build:
	./rr serve -c .rr.build.yaml
rr-reset:
	./rr reset
rr-stop:
	./rr stop -p
rr-jobs-list:
	./rr jobs --list

