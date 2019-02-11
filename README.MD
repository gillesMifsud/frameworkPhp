**Lancer server PHP**

php -S localhost:8000 -d display_errors=1 -t public/


**Lancer les tests unitaires**

./vendor/bin/phpunit tests/Framework/AppTest.php

**Lancer le codesniffer**

./vendor/bin/phpcbf
./vendor/bin/phpcs

**Phinx commands**

http://docs.phinx.org/en/latest/commands.html

*Migrate* : ``./vendor/bin/phinx migrate -e development``

*Rollback* : ``./vendor/bin/phinx rollback -e development``

*Seed run* : ``./vendor/bin/phinx seed:run -e development``
