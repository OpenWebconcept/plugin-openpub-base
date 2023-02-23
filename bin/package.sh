#!/bin/bash

## Remove old packages
rm -rf ./releases
mkdir -p ./releases

# Copy current dir to tmp
rsync \
	-ua \
	--exclude='vendor/*' \
	--exclude='releases/*' \
	./ ./releases/openpub-base/

# Remove current vendor folder (if any)
# and install the dependencies without dev packages.
cd ./releases/openpub-base || exit
composer install -o --no-dev

# Remove unneeded files in a WordPress plugin
rm -rf ./.git ./composer.json ./.gitignore ./.editorconfig ./.eslintignore \
	./.eslintrc ./.php-cs-fixer.php ./composer.lock ./bin \
	./phpstan.neon.dist ./phpunit.xml.dist ./tests \
	./DOCKER_ENV ./docker_tag ./output.log ./.github

cd ../

# Create a zip file from the optimized plugin folder
zip -rq openpub-base.zip ./openpub-base
rm -rf ./openpub-base

echo "Zip completed @ $(pwd)/openpub-base.zip"
