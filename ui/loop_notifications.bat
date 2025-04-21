@echo off
:start
php C:\xampp\htdocs\posbarcode\ui\send_due_notifications.php
timeout /t 60 >nul
goto start