# Etapes pour installer un serveur pouvant déployer notre site sur Debian 11 :
&NewLine;

## Tout d'abord nous devons installer le serveur applicatif :
```
sudo apt-get install nginx
```
&NewLine;
## Ensuite on active le service :
```
sudo systemctl status nginx
```
&NewLine;
## Il faut installer php-fpm, et les autres packages au cas où pour que nginx puisse gérer les pages php :
```
sudo apt install php php-cli php-fpm php-json php-mysql php-zip php-gd  php-mbstring php-curl php-xml php-pear php-bcmath
```
&NewLine;
## On utilise cette commande pour vérifier que le service est bien installé et le daemon est actif :
```
systemctl status php*-fpm.service
```
&NewLine;
## Il faut générer les clefs et certificats pour sécuriser la connexion https : 
```
sudo apt install openssl
```
```
sudo openssl genrsa -out /etc/ssl/private/private.key 4096
```
```
sudo openssl req -new -key /etc/ssl/private/private.key -out /etc/ssl/certs/serveur.csr
```
```
sudo openssl x509 -req -days 365 -in /etc/ssl/certs/serveur.csr -signkey /etc/ssl/private/private.key -out /etc/ssl/certs/serveur.crt
```
&NewLine;
## Il faut maintenant configurer nginx pour déployer le site en éditant /etc/nginx/sites-available/default :
```
server {
    #port d'écoute IPV4
    listen 80;
    #port d'écoute IPV6
    listen [::]:80;

    #nom de domaine
    server_name intergalactiques.com;

    #redirection des connexions http vers https
    return 301 https://$server_name$request_uri;
}

server {

    #Il faut absolument ajouter ssl sinon la redirection ne fonctionnera pas
    listen 443 ssl;
    listen [::]:443 ssl;

    server_name intergalactiques.com;

    #chemin du repertoire racine sur le serveur (on choisira le repertoire de notre git repository où sont nos pages)
    root /home/vboxuser/Documents/Projet-alternatif/site;

    #fichiers index que le serveur doit servir dans le repertoire racine
    index index.html

    #protocoles SSL gérés par le serveur
    ssl_protocols       TLSv1.2 TLSv1.1;

    #chemin du certificat et clef serveur
    ssl_certificate     /etc/ssl/certs/serveur.crt;
    ssl_certificate_key /etc/ssl/private/private.key;

    #Pour que nginx puisse traiter les fichiers php
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```
&NewLine;

## On restart nginx pour appliquer les changements et vérifier qu'il n'y a pas d'erreurs

```
sudo systemctl restart nginx
```
&NewLine;

## Installation de système de gestion de base de données

```
sudo apt install mariadb-test mariadb-server-core-10.5

```
## Création de la base de données et des tables

```
sudo mariadb
```

```CREATE DATABASE intergalactiques;

```
```CREATE TABLE client ( Id_client INT PRIMARY KEY, id VARCHAR(50), mail VARCHAR(50), postal VARCHAR(50), nom VARCHAR(50), prenom VARCHAR(50), date_inscription DATE, date_naissance DATE, pswd VARCHAR(50), distance DECIMAL(15,2));
```

