## Interaction with web3
In order to interact with web3 what should be done is:
- from front send a request to the backend, it works on it and get some packet
- the backend forward the request to an internal node.js based website that actually uses the request
- the response is manipulated by the node hidden backend and forwarded to the php one
- the php backend uses the result of the computation and updates the database if needed

## Interaction with blockchains events
In order to interact with the blockchain events:
- the node backend sets up hooks for the interesting events and forward the events to the php backend
- the php backend manipulates them and updates the database if needed, further sending through websocket can occur in this phase


# Setup
* Run installation script to install all required software
* clone `https://github.com/ebalo55/doduet-node-backend`
* run it in supervised mode with supervisord `node ./bin/www`
* set up the public website
```apacheconf
<VirtualHost *:80>
    ServerAlias www.doduet.studio
    ServerName doduet.studio
    ServerAdmin webmaster@localhost
    DocumentRoot /home/ebalo/Desktop/Projects/php/TheDittyTune/public
    
    <Directory /home/ebalo/Desktop/Projects/php/TheDittyTune/public/>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Order allow,deny
        allow from all
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```
* set up the internal website receiver
```apacheconf
<VirtualHost *:80>
    ServerName php.internal.doduet.studio
    ServerAdmin webmaster@localhost
    DocumentRoot /home/ebalo/Desktop/Projects/php/TheDittyTune/public
    
    <Directory /home/ebalo/Desktop/Projects/php/TheDittyTune/public/>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Order allow,deny
        allow from "127.0.0.1"
        Require ip "127.0.0.1"
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```
* set up the internal node website
```apacheconf
<VirtualHost *:80>
    ServerName node.internal.doduet.studio
    ServerAdmin webmaster@localhost
    
    ProxyRequests Off
    ProxyPreserveHost On
    ProxyVia Full
    <Proxy "*">
        Require ip 127.0.0.1
    </Proxy>
    
    <Location "/api">
        ProxyPass "http://127.0.0.1:3002"
        ProxyPassReverse "http://127.0.0.1:3002"
    </Location>
    
    <Directory "/home/ebalo/Desktop/Projects/node/doduet-node-backend/public">
        AllowOverride All
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```
