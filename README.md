# Drupal

This project is based on 💦 [Drupal 10](https://drupal.org) and 👓 [Claro](https://www.drupal.org/project/claro) as Admin UI.
It uses 🐳 [Docker](http://docker.com/) for running.

## 🐳 Docker Install

### Project setup

```bash
cp docker-compose.override-example.yml docker-compose.override.yml
```

Update any values as needed, example when you already use the 8080 port:

```yml
services:
  # Drupal development server
  dev:
    hostname: dev
    ports:
      - "8082:80"
```

Another example when you already have a local MySQL server using port 3306:

```yml
# Database
db:
  ports:
    - "13306:3306"
```

### Windows specific

Define the build `User` on `docker-compose.override.yml`

```yml
services:
  # Drupal development server
  dev:
    # ...
    build:
      args:
    # Set your user id (like 1000) to run the container with the same user as your system and ensure
    # files have the right permissions (default is to "www-data", doesn't work on OSX)
    user: 1000
```

### Project bootstrap

    docker compose up --build -d
    docker compose exec dev drush site-install demo_umami --db-url="mysql://drupal:drupal@db/drupal_development" --site-name=Example -y
    # (get another coffee, this will take some time...)
    docker composer exec dev drush user-password admin admin

### Project setup

Once the project up and running via Docker, you may need to setup some configurations in the `web/sites/default/setting.php`.

### When it's not the first time

    docker compose up --build -d
    docker compose exec dev drush cr # (or any other drush command you need)
    docker compose exec dev drush cim -y

## Authors

👤 **Antistatique**

Author and maintainers since 2022.

* Web: [antistatique.net](https://antistatique.net)
* Twitter: [@antistatique](https://twitter.com/antistatique)
* Github: [@antistatique](https://github.com/antistatique)
