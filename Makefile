usage:
	@echo "test:     Runs the test suite.\nvendors:  Install the dependencies.\nclean:    Remove the dependencies.\ncs: Run the PHP CS fixer.\ncs_dry_run: Run the PHP CS fixer using --dry-run argument."

vendors:
	@composer install

phpunit:
	./vendor/bin/phpunit --coverage-text

clean:
	@rm -rf vendor
	@rm composer.lock

test: vendors phpunit clean

cs:
	./vendor/bin/php-cs-fixer fix --verbose

cs_dry_run:
	./vendor/bin/php-cs-fixer fix --verbose --dry-run

.PHONY: vendors phpunit clean test cs cs_dry_run
