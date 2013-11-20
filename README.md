Docker infrastructure for Gears Skeleton
========================================

Docker infrastructure for the Gears Skeleton app.

This setup is suitable for development (local) environment as well as for production environment.

This repo is a part of the “gears” project.


Installation for local development environment
----------------------------------------------

* Clone repos of UAC services to corresponding folders in `services/<service>/app`. For example, `php` repo source codes
    copy to `services/php/app` folder. See the links to all services below.
* Copy root `.env.dist` file to the corresponding `.env` and put real values according to your setup needs.      
* Copy all existing `services/*/.env.dist` to the corresponding `services/*/.env` and change them to your needs.
* Build images by `docker-compose build`.
* Before the first run, follow the installation instructions of each service (the root `README.md` file of each service
    repo). Otherwise containers may not start properly. For example, to download Composer dependencies in the `php`
    repo, you can run `docker-compose run --rm php composer install`
* Start the project by `docker-compose up -d`.
* If containers are started successfully, you can enter a container by `docker-compose exec <service> bash` (for
    example, `docker-compose exec php bash`). In containers, you can run `composer install` (if needed), run migrations
    (if needed), and run other instructions provided by each service `README.md` if you updated the service code repo.
* If needed, set local domain names for the branding in your `/etc/hosts` file — for example, `127.0.0.1 gears-be.loc`.
* Open the dashboard in a browser from URL `localhost:80` (or use brand domains — like `gears-be.loc`).

### Xdebug and macOS
[This fix](https://gist.github.com/ralphschindler/535dc5916ccbd06f53c1b0ee5a868c93) should help in case Xdebug can not
 establish back connection for remote debugging. After applying it you should update XDEBUG_CONFIG= variable with 
 the ``remote_host=10.254.254.254`` value inside your root level .env file and recreate php container. 

Installation for production environment
---------------------------------------

* Copy your private SSH key to `services/base/ssh/id_rsa`. This key will be used only for loading the private
    dependencies, and will not be stored in the final image.
* Clone repos of UAC services to corresponding folders in `services/<service>/app`. For example, `php` repo source codes
    copy to `services/php/app` folder. See the links to all services below. **Warning:** For `nginx` service, use the
    source code from the `php` repo and clone it to `services/php/app`, because the `nginx` service uses the assets
    created for the `php` sevice.
* Build images by `docker-compose -f docker-compose.yml build --build-arg BUILD_ENV=prod <service>` for development
    server environment (like staging), Build images by
    `docker-compose -f docker-compose.yml build --build-arg BUILD_ENV=prod --build-arg DEBUG_MODE=0 <service>`
    for production environment (this will turn error display off). To build all images at once, omit `<service>`.
* If you need to tag the image and then push it to your Docker repository, add two environment variables
    (`BUILD_IMAGE_NAME` and `BUILD_IMAGE_TAG`) for the build command from the previous step, for example:
    `BUILD_IMAGE_NAME=<customrepo/imagename> BUILD_IMAGE_TAG=<tag> docker-compose -f docker-compose.yml build --build-arg BUILD_ENV=prod --build-arg DEBUG_MODE=0 <service>`
* Use the built images in your Kubernetes or Docker Swarm claster.
* For each service, pass the correct variables from `services/<service>/.env.dist` to the corresponding containers on
    their start.


Services
--------

* `php` — Main PHP backend service. Repo: https://gitlab.com/Denisenko/gears-backend (`git@gitlab.com:Denisenko/gears-backend.git`)
* `nginx` — Nginx — provides web-access to `php`
* `db` — MySQL
* `mailcatcher` — Mail catcher — catches all email sent in development environment


External URLs for development environment
-----------------------------------------

* http://localhost:80/— Main entry-point for web access
* `localhost:5432` — Main database
* http://localhost:1080/ — Mail catcher UI


Internal URLs
-------------

* http://nginx:80/ — Entry-point for webservices
* `php:9000` — Main repo PHP-FPM
* `db:5432` — Main database
* `mailcatcher:1080` and `mailcatcher:1025` — Mail catcher


Folder structure
----------------

```
/services/
         |_______ base/                 — Contains common files for all services.
                      |___ image-files/ — The files that are common for all services.
                                          They will be included into each image.
                      |___ ssh          — May contain the private key `id_rsa` if an access to private repos needed.
                                          The private key **will not** be included into the final image.
         |______ nginx/                 — `nginx` service.
                      |___ image-files/ — The files needed for `nginx` services.
                                          The files will be added after files from `base/` folder.
                                          If files exist they will be overwritten by the files from this folder.
                      |___ .env.dist    — Contains all the environment variable that should be set.
                                          These variable (but with real values) shoud be passed to the container
                                          if the production environment is used.
                      |___ .env         — Contains the real values for environment variables.
                                          It is used only for the development environment.
         |________ php/                 — `php` service.
                      |___ app/         — This folder should be created manually (or by script).
                                          The source code should be cloned in this folder for both development
                                          and production environments (before the build process).
                      |___ image-files/ — (Same as above)
                      |___ .env.dist    — (Same as above)
                      |___ .env         — (Same as above)
         |___ service1/                 — Any new service will have the save structure.
                      |___ app/         — (Same as above)
                      |___ image-files/ — (Same as above)
                      |___ .env.dist    — (Same as above)
                      |___ .env         — (Same as above)
         |___ service2/
                      |___ ...
         |_ Dockerfile                  — Dockerfile for `php` and `nginx` services.
                                          It can be user for both development and production.
/README.md                              — Documentation.
/docker-compose.yml                     — It is used for both development and production environments.
/docker-compose.override.yml            — It is used only for development.
                                          It adds volumes and other things to `docker-compose.yml`
```
