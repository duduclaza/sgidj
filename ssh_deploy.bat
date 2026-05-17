@echo off
"C:\Program Files\PuTTY\plink.exe" -P 65002 -pw Pandora@1989 -hostkey SHA256:BasCT+DeuZlOSmyPwr1r46VaJ8Yref7jth4IFEycjqM u230868210@82.112.247.45 "cd ~/domains/sgidj.tiuai.com.br/public_html && git log --oneline -3 && grep -n 'clientX' views/melhorias/index.php | head -5"
