Lancer server PHP

php -S localhost:8000 -d display_errors=1 -t public/


Lancer les tests unitaires

./vendor/bin/phpunit tests/Framework/AppTest.php

Lancer le codesniffer

./vendor/bin/phpcs