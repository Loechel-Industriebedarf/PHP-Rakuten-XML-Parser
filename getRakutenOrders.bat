@ECHO OFF
cd C:\eNVenta-ERP\BMECat\Rakuten\debug
:loop
ECHO Executing script...
php\php.exe index.php
ECHO.
ECHO Waiting 15 Minutes...
TIMEOUT 900
ECHO.
ECHO.
GOTO :loop
