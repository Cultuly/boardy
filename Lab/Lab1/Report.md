## Конфигурация виртуальной машины
![Конфигурация виртуальной машины](screenshots/01-vm-settings.png)
## Командная строка Ubuntu после установки с приглашением фамилия@фамилия:~$
![Командная строка Ubuntu после установки с приглашением фамилия@фамилия:~$](screenshots/02-vm-console.png)
## Вывод cat ~/report/01-system.txt
![Вывод cat ~/report/01-system.txt](screenshots/03-system-info.png)
## Вывод ip addr show
![Вывод ip addr show](screenshots/04-ip-addr.png)
## Вывод sudo ss -tlnp
![Вывод sudo ss -tlnp](screenshots/05-ports.png)
## Вывод sudo systemctl status ssh
![Вывод sudo systemctl status ssh](screenshots/06-ssh-status.png)
## Вывод sudo ss -tlnp | grep ssh
![Вывод sudo ss -tlnp | grep ssh](screenshots/07-ssh-port.png)
## Вывод grep '/bin/bash' /etc/passwd
![Вывод grep '/bin/bash' /etc/passwd](screenshots/08-users.png)
## Процесс создания нового пользователя boardy
![Процесс создания нового пользователя boardy](screenshots/09-new-user.png)
## [Вывод id boardy
![Вывод id boardy](screenshots/10-user-check.png)
## Вывод ls -la /
![Вывод ls -la /](screenshots/11-root-tree.png)
## Вывод ls -la ~
![Вывод ls -la ~](screenshots/12-home-tree.png)
## Вывод ls -ld / /etc /var /tmp /home
![Вывод ls -ld / /etc /var /tmp /home](screenshots/13-permissions.png)
## Три состояния testfile.txt (до, после chmod 755, после chmod 600)
![Три состояния testfile.txt (до, после chmod 755, после chmod 600)](screenshots/14-chmod.png)
## Вывод dpkg -l | grep -E 'openssh|python|git|curl|vim|nano'
![Вывод dpkg -l | grep -E 'openssh|python|git|curl|vim|nano'](screenshots/15-packages.png)
## Вывод systemctl list-units --type=service --state=running
![Вывод systemctl list-units --type=service --state=running](screenshots/16-services.png)
## Вывод ps aux --sort=-%mem | head -11
![Вывод ps aux --sort=-%mem | head -11](screenshots/17-top-processes.png)
## Вывод процессов по пользователям
![Вывод процессов по пользователям](screenshots/18-process-count.png)
## Вывод топ-10 больших файлов в /var
![Вывод топ-10 больших файлов в /var](screenshots/19-big-files.png)
## Вывод ls -lh ~/report/ со всеми файлами
![Вывод ls -lh ~/report/ со всеми файлами](screenshots/20-report-files.png)