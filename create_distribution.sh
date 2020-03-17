#!/bin/sh
  
BASE=.

mkdir $BASE/dist
cp -Rf $BASE/src/* $BASE/dist/
rm $BASE/dist/assets/css/*.scss
rm $BASE/dist/assets/js/admin.js
rm $BASE/dist/assets/js/frontend.js
rm $BASE/dist/assets/js/settings.js
rm -Rf $BASE/dist/vendor
rm $BASE/dist/package*.json
rm $BASE/dist/Gruntfile.js
rm $BASE/dist/readme.md
rm $BASE/dist/composer.json

zip -r -X $BASE/plugin.zip $BASE/src/
rm -Rf $BASE/dist
