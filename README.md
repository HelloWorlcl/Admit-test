# Test task for the AdmitAd company

## Requirements

Make sure that you have installed the following requirements:
- php7.1+ (and common extensions like php-mysql);
- mysql5.6+;
- nginx;

## Getting Started
### Server configuring
To configure the local server go through the next steps:
- create a new file `/etc/nginx/sites-available`:  
`sudo touch /etc/nginx/sites-available/vadym.test.task.com`
 - put to the new created file  
`sudo nano /etc/nginx/sites-available/vadym.test.task.com`  
    this content:
 
    ```
    server {
        listen 80 default_server;
        listen [::]:80 default_server;
    
        root /location/to/installed/project/public/;
    
        index index.html index.php index.htm index.nginx-debian.html;
    
        server_name vadym.test.task.com;
    
        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }
        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
        
            fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
        }
    }
    ```
    Don't forget to replace the root directive with location to the public folder of the installed project on your machine (5 row).
    Change version of php-fpm, if you don't use php7.2-fpm as well (18 row).

- Create a symlink:  
`sudo ln -s /etc/nginx/sites-available/vadym.test.task.com /etc/nginx/sites-enabled/`

- If you want you may change `/etc/hosts` to give an alias to the created site. Just add the following line to end of the file:  
`127.0.0.1 vadym.test.task.com`

- Restart your server:
`sudo service nginx restart`

### Database configuring

Follow the next steps:
- Connect to your mysql (you're also able to use GUI tools);

- Create a database:
`CREATE DATABASE vadym_test_task;`

- Start using the database:
`USE vadym_test_task`

- Create an authors table:  
```
CREATE TABLE authors(
    id int PRIMARY KEY AUTO_INCREMENT,
    full_name varchar(255) NOT NULL
);
```

- Create a books table:  
```
CREATE TABLE books (
    id int PRIMARY KEY AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    author_id int NOT NULL,
    description TEXT,
    picture_path varchar(255),
    FOREIGN KEY (author_id) REFERENCES authors(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
``` 

- Fill the authors table with dummy authors:
```
INSERT INTO authors (full_name) VALUES ('John Doe'), ('Ivanov Ivan Ivanovich'), ('Petrov Petr Petrovich'), ('Victorov Victor Victorovich');
```

That's all what you have to set up.

## Configuring local environment

- Copy .env.example into .env (should be done from the root project folder):  
`cp .env.example .env`

- Set all config variables in the .env file according to your database configuration;

- Run `composer install`;

That's it. Have fun :)
