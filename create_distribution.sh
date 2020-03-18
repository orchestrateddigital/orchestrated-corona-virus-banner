#!/bin/sh
  
BASE=.

mkdir $BASE/dist
rm $BASE/latest.zip
cp -Rf $BASE/src/* $BASE/dist/
cd $BASE/dist && npm install grunt && grunt && cd ..
rm $BASE/dist/assets/css/*.scss
rm $BASE/dist/assets/js/admin.js
rm $BASE/dist/assets/js/frontend.js
rm $BASE/dist/assets/js/settings.js
rm -Rf $BASE/dist/node_modules
rm $BASE/dist/package*.json
rm $BASE/dist/Gruntfile.js
rm $BASE/dist/readme.md
rm $BASE/dist/composer.json

mv $BASE/dist $BASE/orchestrated-corona-virus-banner
zip -r -X $BASE/latest.zip $BASE/orchestrated-corona-virus-banner/
rm -Rf $BASE/orchestrated-corona-virus-banner
