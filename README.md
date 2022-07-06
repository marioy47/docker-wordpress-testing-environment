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
