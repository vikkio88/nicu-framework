#!/bin/bash


vendor/bin/phpcs --standard=PSR2 src/ tests/
vendor/bin/phpmd src/ text cleancode,codesize,controversial,design,naming,unusedcode