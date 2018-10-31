BananaStation
=============

This is the repository for my Symfony 3 web site

##Installation

Clone the repository :  
`git clone https://github.com/Dowdow/BananaStation.git`

Make cache and logs folders accessible :  
`chmod 777 -R var/cache var/logs`

Install dependencies with Composer :  
`composer install`

Create the database and create tables :  
`php bin/console doctrine:migration:migrate`

###Hosts

Hosts to add in `/etc/hosts` :

```
# Banana Station
127.0.0.1   www.banana-station.local
127.0.0.1   music.banana-station.local
127.0.0.1   notifier.banana-station.local
127.0.0.1   poke.banana-station.local
127.0.0.1   tron.banana-station.local
```

###Vhosts

Vhosts to add in `/etc/apache2/sites-available/banana.conf` :

```
Listen 80

NameVirtualHost *:80

<VirtualHost *:80>
 DocumentRoot /var/www/html/BananaStation/public
 ServerName www.banana-station.local
</VirtualHost>

<VirtualHost *:80>
 DocumentRoot /var/www/html/BananaStation/public 
 ServerName music.banana-station.local
</VirtualHost>

<VirtualHost *:80>
 DocumentRoot /var/www/html/BananaStation/public 
 ServerName notifier.banana-station.local
</VirtualHost>

<VirtualHost *:80>
 DocumentRoot /var/www/html/BananaStation/public 
 ServerName poke.banana-station.local
</VirtualHost>

<VirtualHost *:80>
 DocumentRoot /var/www/html/BananaStation/public 
 ServerName tron.banana-station.local
</VirtualHost>
```
Enjoy life !
