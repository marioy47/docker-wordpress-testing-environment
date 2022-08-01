# Docker WordPress Testing Environment

Use this repository to create a fast and simple WordPress testing environment for those times you just want to test a function, a hook or a filter.

## Usage

```bash
cd docker-wordpress-testing-environment
docker-compose up -d
```

- The `plugins/` folder will be mapped to `/var/www/html/wp-content/plugins`
- the `themes/` folder will be mapped to `/var/www/html/wp-content/themes`

Note: **Both folders will be ignored by git**

## Configuration

By default:

- WordPress <http://localhost:8000>
- MailHog <http://localhost:8001>
- PhpMyAdmin <http://localhost:8002>

You can change this values by creating a [`.env`](.env.example) file:

```bash
cp .env.example .env
nano .env
```

## Debuggin

The environment has XDebug enabled by default. If you use _Visual Studio Code_ a `.vscode/launch.json` is provided pre configured for the `plugins/` and `themes/` folders.

Is recommended that you install the [Xdebug Helper](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc) chrome extension for faster debugging.

## Importing a remote site

This are the required steps to import a remote site in this dev environment:

- On the remote/production site
  - Create a db dump of the database
  - (optional) Create a compressed file of the uploads folder
- Download both files
- On this dev environment
  - Import the database
  - Extract the uploads

### On the remote/production site

Create a backup of the db and uploads in the **remote server**. Here we're using [`wp`](https://make.wordpress.org/cli/handbook/) to export the database:

```bash
ssh user@my-prod-site.com
cd /path/to/wordpress
wp db export --add-drop-table | gzip -c > /tmp/export.sql.gz
### Next 2 commandss are optional
cd wp-content
tar cfz /tmp/uploads.tar.gz uploads
```

> One reason to use `gzip` over `bzip2` is compression speed.

### Download both files

On your local machine:

```bash
cd /path/to/docker/dev-env
scp user@my-prod-site.com:/tmp/export.sql.gz .
scp user@my-prod-site.com:/tmp/uploads.tar.gz .
```

### On this dev environment

Import the database in the local docker environment:

```bash
cd /path/to/docker/dev-env
docker-compose run --rm wp-cli -v $PWD:/exports sh -c "zcat /exports/export.sql.gz | sed 's/https:\/\/my-prod-site.com/http:\/\/localhost:8000/g' wp db query"
```

**Note**: If you modified the `WORDPRES_HOST` variable in the `.env` file, then you have to change the `localhost` section.

> The `sed` command is for replacing the remote domain with the local one.

Extract the uploads folder:

```bash
cd /path/to/docker/dev-env
docker-compose exec -v $PWD:/exports wp bash -c "cd wp-content && tar xfz /exports/uploads.tar.gz"
```

## Proxy remote files (Uploads folder)

The problem wit the previous approach is that the **uploaded files folder can take GIGS of space** depending on the site you want to import. Making the compression and download of files unviable.

To circumvent that, you could configure the **local proxy** (which is a nginx server) on the docker development environment to retrieve the media files from the remote server **IF** the file is not present locally. For that, just create a `.env` file, using the [`env.example`](.env.example) as a starting point, and configure the `WORDPRESS_PROD_URL` to point to the PROD server's domain:

```bash
WORDPRESS_PROD_URL=https://my-prod-site.com
```

Now, when your browser tries to get a file that is not on your dev environment, it will tell your browser to get the file from the remote server transparently.
