
### Options

Docker 엔진의 운영모드, 로깅수준 설정, TLS(Transport Layer Secure) 설정 등을 지원한다.

```
Options:
      --config string      Location of client config files (default
                           "C:\Users\babel-core\.docker")
  -D, --debug              Enable debug mode
      --help               Print usage
  -H, --host list          Daemon socket(s) to connect to
  -l, --log-level string   Set the logging level
                           ("debug"|"info"|"warn"|"error"|"fatal")
                           (default "info")
      --tls                Use TLS; implied by --tlsverify
      --tlscacert string   Trust certs signed only by this CA (default
                           "C:\Users\titicaca\.docker\ca.pem")
      --tlscert string     Path to TLS certificate file (default
                           "C:\Users\titicaca\.docker\cert.pem")
      --tlskey string      Path to TLS key file (default
                           "C:\Users\titicaca\.docker\key.pem")
      --tlsverify          Use TLS and verify the remote
  -v, --version            Print version information and quit
```

이미 `docker --help`에 관해서는 언급하였고, --version을 한번 살펴보자.

```Bash
$ docker --version
Docker version 17.09.0-ce, build afdb6d4
```

여기에서 눈여겨 볼 점은 TLS(Transport Layer Secure) 설정이 있는 것이다. TLS는 네트워크 통신에서 전송되는 데이터의 위변조를 막기 위한 전송 방식이다. 아직은 정확히 모르겠지만 언젠가 `docker engine`이 `docker CLI`가 동일한 컴퓨터에서 동작할 필요는 없을 것이라는 생각이 든다. 일단 터미널에 `docker version`을 입력해보자.

```Bash
$ docker version
Client:
 Version:      17.09.0-ce
 API version:  1.32
 Go version:   go1.8.3
 Git commit:   afdb6d4
 Built:        Tue Sep 26 22:40:09 2017
 OS/Arch:      windows/amd64

Server:
 Version:      17.09.0-ce
 API version:  1.32 (minimum version 1.12)
 Go version:   go1.8.3
 Git commit:   afdb6d4
 Built:        Tue Sep 26 22:45:38 2017
 OS/Arch:      linux/amd64
 Experimental: true
```

위의 그림을 보면, 우리는 Client와 Server가 하나의 호스트에서 동작하여 인식하지 못하지만, Client와 Server는 분리하여 운영할 수 있을 것으로 유추할 수 있는 대목이다.

### Commands

Commands 컨테이너의 동작을 제어하는 명령어를 가진다.

```
Commands:
  attach      Attach local standard input, output, and error streams to a running container
  build       Build an image from a Dockerfile
  commit      Create a new image from a container's changes
  cp          Copy files/folders between a container and the local filesystem
  create      Create a new container
  deploy      Deploy a new stack or update an existing stack
  diff        Inspect changes to files or directories on a container's filesystem
  events      Get real time events from the server
  exec        Run a command in a running container
  export      Export a container's filesystem as a tar archive
  history     Show the history of an image
  images      List images
  import      Import the contents from a tarball to create a filesystem image
  info        Display system-wide information
  inspect     Return low-level information on Docker objects
  kill        Kill one or more running containers
  load        Load an image from a tar archive or STDIN
  login       Log in to a Docker registry
  logout      Log out from a Docker registry
  logs        Fetch the logs of a container
  pause       Pause all processes within one or more containers
  port        List port mappings or a specific mapping for the container
  ps          List containers
  pull        Pull an image or a repository from a registry
  push        Push an image or a repository to a registry
  rename      Rename a container
  restart     Restart one or more containers
  rm          Remove one or more containers
  rmi         Remove one or more images
  run         Run a command in a new container
  save        Save one or more images to a tar archive (streamed to STDOUT by default)
  search      Search the Docker Hub for images
  start       Start one or more stopped containers
  stats       Display a live stream of container(s) resource usage statistics
  stop        Stop one or more running containers
  tag         Create a tag TARGET_IMAGE that refers to SOURCE_IMAGE
  top         Display the running processes of a container
  unpause     Unpause all processes within one or more containers
  update      Update configuration of one or more containers
  version     Show the Docker version information
  wait        Block until one or more containers stop, then print their exit codes
```

### Management Commands

Management Commands 카테고리에는 Docker Engine의 동작을 제어하는 명령어를 가진다.

```
Management Commands:
  checkpoint  Manage checkpoints
  config      Manage Docker configs
  container   Manage containers
  image       Manage images
  network     Manage networks
  node        Manage Swarm nodes
  plugin      Manage plugins
  secret      Manage Docker secrets
  service     Manage services
  stack       Manage Docker stacks
  swarm       Manage Swarm
  system      Manage Docker
  volume      Manage volumes
```