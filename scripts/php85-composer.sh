#!/bin/bash
PHP85="/c/Users/ADN GOLFE 1/AppData/Local/Microsoft/WinGet/Packages/PHP.PHP.8.5_Microsoft.Winget.Source_8wekyb3d8bbwe/php.exe"
PHP_INI="/c/Users/ADN GOLFE 1/AppData/Local/Microsoft/WinGet/Packages/PHP.PHP.8.5_Microsoft.Winget.Source_8wekyb3d8bbwe/php.ini"
COMPOSER="/c/ProgramData/ComposerSetup/bin/composer.phar"

"$PHP85" -c "$PHP_INI" "$COMPOSER" "$@"
