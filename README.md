Poduptime


Dependencies:  
php7.0 php7.0-curl php7.0-pgsql php-geoip php7.0-cli php7.0-common php7.0-json php7.0-readline
git
curl
postgresql postgresql-contrib
wget
dnsutils
npm
nodejs nodejs-legacy

To Install:  
git clone https://github.com/diasporg/Poduptime.git  
cd Poduptime  
sudo npm install -g bower  
bower install  
cp config.php.example config.php  

If you need to setup your Postgresql/DB:  
  sudo adduser podupuser  
  sudo -u postgres bash -c "psql -c \"CREATE USER podupuser WITH PASSWORD 'MYpassword';\""  
  sudo -u postgres bash -c "psql -c \"CREATE DATABASE podupdb;\""  
  sudo -u postgres bash -c "psql -c \"GRANT ALL PRIVILEGES ON DATABASE podupdb TO podupuser;\""  
  sudo nano /etc/postgresql/vx.x/main/pg_hba.conf 
  update your local line to allow md5 METHOD  
  restart postgresql  
  
psql -u podupuser podupdb < db/tables.sql  
  
edit config.php with your DB and file settings  


============================

Source for http://podupti.me

  Poduptime is software to get live stats and data on listed Diaspora Pods.
  Copyright (C) 2011 David Morley

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as
  published by the Free Software Foundation, either version 3 of the
  License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
