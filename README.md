BLOBGASTH
=========

English follow

¿Qué es?
--------
Blobgasth es un software que te ayudará a llevar al día tus cuentas, gastos e ingresos. Funciona tanto con bases de datos mysql como sqlite3.

El software está escrito en html, jQuery y PHP por lo que es necesario un servidor web con php y soporte para mysql o sqlite3, según tu preferencia. Si no tienes uno, puedes empezar con uno local que puedes encontrar [aquí](https://www.apachefriends.org) (hay que descomentar la linea extension=php_pdo_sqlite en el fichero php.ini).

Instalación
-----------
Descomprimir y crear en el directorio raiz el fichero db_settings.ini con el siguiente contenido (si no existe, Blobgasth lo creará por tí usando sqlite3):
```
[database]
driver = sqlite
;sqlite o mysql
host = database.sqlite3
;host mysql o directorio de la base de datos sqlite3

;los siguientes solo son necesarios para bases de datos mysql.
port = 3306
db_name = nombredb
username = usuario
password = contraseña
```

What is?
--------
Blobgasth is a software that will help you keep your accounts up to date, expenses and incomes. It works with both MySQL and sqlite3 databases.

This software is writting in html, jQuery and PHP, so it's necessary a web server with php and mysql or sqlite3 support, as you wish. If you haven't you can try with a local version [here](https://www.apachefriends.org) (you need to uncomment the line extension=php_pdo_sqlite in file php.ini).

At now the software is entirely in spanish. Future releases will let you to select the language you prefer (or base it in system language).

Install
-----------
Unzip and create in root directory the file db_settings.ini with the following content (if it not exists, Blobgasth will create one for you using sqlite3):
```
[database]
driver = sqlite
;sqlite or mysql
host = database.sqlite3
;mysql host or path to sqlite3 database

;below only neccesary for mysql databases.
port = 3306
db_name = dbname
username = user
password = pass
```

TODO list
---------
- [ ] Add posibility to modify movements.
- [ ] Add posibility to rearrange accounts or categories.
- [ ] Add language selection (now only in spanish).
