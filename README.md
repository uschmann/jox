### Build docker image

```
docker build -t phpdev .
```

### Configure XDEBUG in phpstorm for use in docker container

- Hit **ctrl + alt + s** to open the settings and search for **Servers**.
- Add a new Server and give it a name.
- Check use path mappings
- Map the path of the docker container to your local path.
- Run PHP Xdebug enabled:

```bash
PHP_IDE_CONFIG="serverName=SomeName" XDEBUG_SESSION=1 php src/test.php 
```

### Shell function to debug php scripts

You can add this to your **~/.bashrc** or **~/.zshrc**

```shell
function phpdebug()
{
        docker run -it --rm -e PHP_IDE_CONFIG="serverName=SomeName" -e XDEBUG_SESSION=1 -v $PWD:/app -w /app -u $(id -u):$(id -g) --add-host=host.docker.internal:host-gateway phpdev php "$@"
}
```