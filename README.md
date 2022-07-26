# Docker WordPress Testing Environment

Use this repository to create a fast and simple testing environment for those times you just want to test a function, a hook or a filter.

## Usage

```bash
cd docker-wordpress-testing-environment
docker-compose up -d
```

- The `plugins/` folder will be mapped to `/var/www/html/wp-content/plugins`
- the `themes/` folder will be mapped to `/var/www/html/wp-content/themes`

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

## Proxy remote files

When you are setting up a local development environment for a client that already has a _production_ environment, the common workflow is:

- Create a backup of the db and uploads in the **remote server**
  - Export the PROD database with `wp db export /path/to/export.sql`
  - Compress the media files with `cd /path/to/wp/wp-content/ && zip -r uploads.zip uploads`
- Download the files using _rsync_, _ftp_ or _scp_
- Import the db and files on the local development environment
  - Replace the PROD domain with the local dev domain with `sed -i 's/https:\/\/example.com/http:\/\/localhost:800/g' /path/to/export.sql`
  - Import the database with `pv export.sql | wp db query` (you can use `cat` instead of `pv` if you don't have it installed)
  - Extract the uploads with `cd /path/to/local/wp/wp-content && unzip -o /path/to/uploads.zip`

The problem is that the **uploaded files folder can take GIGS of space** making the compression and download of files unviable.

To circumvent that, you could configure the **local proxy** (which is a nginx server) on the docker development environment to retrieve the media files from the remote server IF the file is not present locally. For that, just create a `.env` file, using the [`env.example`](.env.example) as a starting point, and configure the `WORDPRESS_PROD_URL` to point to the PROD server's domain:

```bash
WORDPRESS_PROD_URL=https://my-prod-site.com
```

Now, when your browser tries to get a file that is not on your dev environment, it will tell your browser to get the file from the remote server transparently.
