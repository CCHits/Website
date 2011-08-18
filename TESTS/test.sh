#! /bin/bash
BASE_DIR="`dirname $0`"
if [ -z $1 ]; then
  echo "Checking syntax... "
  rm -Rf /tmp/phpcs_stats "$BASE_DIR/../DOCS/" /tmp/testing_status
  mkdir "$BASE_DIR/../DOCS"
  find "$BASE_DIR/.." -name "*.php" -not -wholename "*/EXTERNALS/*/*" -not -wholename "*/TEMPLATES/C*" -exec "$0" '{}' \;
  echo "Done."
  echo "Checking classes... "
  php "$BASE_DIR/missing_function_finder.php" "$BASE_DIR/../CLASSES" >> /tmp/phpcs_stats
  if [ ! -s /tmp/phpcs_stats ]; then
    echo -n "Generating Documentation... "
    `which phpdoc` -o HTML:frames:earthli -d "$BASE_DIR/.." -t "$BASE_DIR/../DOCS" > /dev/null
    echo "Done."
  fi
  if [ -s /tmp/phpcs_stats ]; then
    mv /tmp/phpcs_stats "$BASE_DIR/../DOCS/phpcs_failures.txt"
    less "$BASE_DIR/../DOCS/phpcs_failures.txt"
  fi
else
  rm /tmp/phpcs_test
  echo "Checking $1:"
  `which php` -l "$1" >/dev/null 2> /tmp/phpcs_test
  if [ $? == 0 ]; then
    echo "  PHP syntax OK"
    phpcs --standard=CCHits "$1" > /tmp/phpcs_test
    if [ $? == 0 ]; then
      echo "  PHP_CodeSniffer OK"
    else
      echo "====================== PHPCS for $1 ==================" >>/tmp/phpcs_stats
      cat /tmp/phpcs_test >>/tmp/phpcs_stats
      echo "  PHP_CodeSniffer Failed"
    fi
  else
    echo "======================= PHP for $1 ===================" >>/tmp/phpcs_stats
    cat /tmp/phpcs_test >>/tmp/phpcs_stats
    echo "  PHP syntax Failed"
  fi
fi
