@echo off
start bin\mintty.exe -o AllowBlinking=0 -o FontQuality=3 -o Font="Consolas" -o FontHeight=10 -o CursorType=0 -h error -o CursorBlinks=1 -w max bin\php\php.exe start.php