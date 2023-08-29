Tout d'abord nous devons installer le serveur applicatif :

sudo apt-get install nginx

Ensuite on active le service :

sudo systemctl status nginx

il faut installer php-fpm, et les autres packages au cas où pour que nginx puisse gérer les pages php :

sudo apt install php php-cli php-fpm php-json php-mysql php-zip php-gd  php-mbstring php-curl php-xml php-pear php-bcmath

on utilise cette commande pour vérifier que le service est bien installé et le daemon est actif :

systemctl status php*-fpm.service

il faut générer les clefs et certificat pour sécuriser la connexion https :

sudo apt install openssl

openssl genrsa -out /etc/ssl/private/private.key 4096

openssl req -new -key /etc/ssl/private/private.key -out /etc/ssl/certs/serveur.csr

openssl x509 -req -days 365 -in /etc/ssl/certs/serveur.csr -signkey /etc/ssl/private/private.key -out /etc/ssl/certs/serveur.crt

Il faut maintenant configurer nginx pour déployer le site en éditant le fichier de configuration avec la commande  /etc/nginx/sites-available/default que l'on modifiera comme tel :

server {
    # port d'écoute IPV4
    listen 80;
    # port d'écoute IPV6
    listen [::]:80;

    # nom de domaine
    server_name domain.com;

    # rediréction des connexions http vers https
    return 301 https://$server_name$request_uri;
}

server {

    listen 443;
    listen [::]:443;

    server_name domain.com;

    # chemin du repertoire racine sur le serveur (on choisira le repertoire de notre git repository où sont nos pages)
    root /var/www/html;

    # fichiers index que le serveur doit servir dans le repertoire racine
    index index.html

    # protocoles SSL gérés par le serveur
    ssl_protocols       TLSv1.2 TLSv1.1;

    # chemin du certificat et clef serveur
    ssl_certificate     /chemin/vers/cert.pem;
    ssl_certificate_key /chemin/vers/key.key;

    # traitement de l'URI: ce qui suit le nom de domaine
    location / {
        try_files $uri $uri/ =404;
}

