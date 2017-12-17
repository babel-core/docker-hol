# 2장 Docker CLI

현재까지 Docker를 실무에 사용하지 않는 나는 일관성, 유연함, 에코시스템 등에서 Docker의 매력에 푹빠졌다. 1년동안 Docker를 이용할 기회가 생길 때마다 패키지를 설치하는 대신 과감하게(?) Github과 Dockerhub에 공개된 Docker 이미지를 적용해보곤 하였다. 그대로 따라하는 경우 잘 동작하지만, 환경을 스스로 구축하게 되면, 내가 의도한 것과 다른 상황에 직면하게 되었다. 누군가에게는 일관성을 무기로 적용하는 시스템이 나를 외면하는 이유는 무엇일까? 이런 상황이 반복된다면, Docker를 이용하여 개발환경을 구축하기도 어렵고, 서비스를 구축하는 것은 불가능할 것이다.

이런 경험 속에서 최근에 내가 무지했다는 것을 깨달았다. 나는 `Docker CLI`의 기본적인 사용법도 명확하게 파악하지 못하고 있다는 것을... 터미널에 수많은 Docker 커맨드를 입력하였지만, 내가 입력한 커맨드가 어떠한 컨테이너를 생성하는지? 내가 입력한 인자가 어떠한 의도를 가지는지? 잘 모른채 동작자체에만 집중하고 있었던 것이다. 그래서 남들이 만들어놓은 이미지와 남들이 만들어준 커맨드를 이용하면 문제없이 동작하지만, 내가 머릿 속으로 그린 관점을 그대로 옮기려고 하면, 운이 좋으면 잘 동작하고, 어딘가 다르게 동작하는 경우도 있었던 것이다. 이제는 동작자체보다는 정확하게 동작시키는 것에 집중해보자.

우선 Docker 컨테이너 1개를 잘 동작시키는 방법에 대해서 알아볼 것이다. 그러기 위해서는 `Docker CLI`에 대해 실습해 볼 것이다.

## Docker CLI 구성

Docker CLI을 잘 모른다는 가정하에, 아무 생각하지 않고 터미널을 열고 `docker` 커맨드를 입력한다.  

```Bash
$ docker # docker --help와 동일한 결과

Usage:  docker COMMAND

A self-sufficient runtime for containers

Options:      
      --help               Print usage  
  -v, --version            Print version information and quit

Management Commands:
  ......      ......................

Commands:
  attach      Attach local standard input, output, and error streams to a running container
  ......      ......................
  wait        Block until one or more containers stop, then print their exit codes

Run 'docker COMMAND --help' for more information on a command.
```

터미널에 `docker`만을 입력하면, 위의 그림에서 보는 것과 같이 `Options`, `Management Commands`, `Commands`라는 3가지 커맨드 카테고리만 나타날 뿐 `docker` 커맨드만으로는 아무 동작도 수행할 수 없다. 그래서 docker는 `docker --help`에 해당하는 명령이 출력되고 수행을 종료한다.

 `docker`의 실제 수행은 `Options` 카테고리에 있는 tag들을 이용하여 docker 엔진의 설정 정보를 변경할 수 있으며, `Management Commands`와 `Commands` 카테고리에 나열된 보조 커맨드(sub-command)를 수행해야 한다.

docker 커맨드에 구성되어 있는 Tag들과 보조 커맨드에 대하여 간략히 살펴보도록 한다.

### Options

Docker 엔진의 운영모드, 로깅수준 설정, TLS(Transport Layer Secure) 설정 등을 지원한다.

```Bash
Options:
      --config string      Location of client config files (default
                           "C:\Users\titicaca\.docker")
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

여기에서 눈여겨 볼 점은 TLS(Transport Layer Secure) 설정이 있는 것이다. TLS는 네트워크 통신에서 전송되는 데이터의 위변조를 막기 위한 전송 방식인데, 설정을 하는 이유는 무엇일까? ~~제가 이정도 알면 여기 없죠.~~ 아직은 모른다. 명확한건 ~~알면 제가 여기 없다는 겁니다.~~ docker의 또다른 커맨트들 살펴보니 version이라는 보조 커맨드가 있는 것이 보인다. 어떤 출력을 보여줄 지 터미널에 `docker version`을 입력하여 유추해보도록 한다.

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

위의 그림을 보면, Docker는 Client와 Server구조로 이원화되어 동작된다는 것을 알 수 있다. ~~아마도 Server인 Docker Engine과 Client인 Docker CLI가 아닐까 싶다.~~ 우리는 Client와 Server가 하나의 호스트에서 동작하여 인식하지 못하지만, Client와 Server는 분리가 가능할 것으로 보인다. ~~스터디에 이런 내용이 담길 수 있기를 기대합니다.~~

### Management Commands

Docker Engine의 동작을 제어하는 명령어를 가진다.

```Bash
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
### Commands

컨테이너의 동작을 제어하는 명령어를 가진다.

```Bash
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
### 마치며

~~저걸 다 알아야 한다니~ 삶이 고달프다.~~갈길이 멀긴 하지만, 천리도 한걸음 부터.~~아재인가 속담을..~~


## Docker 이미지와 컨터이너 다루기

일단 무작정 화면에 Hello world를 찍어보자.

### `docker run` 기본실습 I

여기서는 `busybox`, `elasticsearh` 이미지를 이용하여 기본 동작을 이해한다. 실행에 앞서 터미널에 `docker run --help`을 입력하여 세부적인 사용 방법을 확인한다.

1. 터미널에 `docker run --help` 입력

```Bash
$ docker run --help                                                           

Usage:  docker run [OPTIONS] IMAGE [COMMAND] [ARG...]

Run a command in a new container

Options:
      --add-host list                  Add a custom host-to-IP mapping
                                       (host:ip)
  -a, --attach list                    Attach to STDIN, STDOUT or STDERR
      --blkio-weight uint16            Block IO (relative weight),
                                       between 10 and 1000, or 0 to
                                       disable (default 0)
      --blkio-weight-device list       Block IO weight (relative device
                                       weight) (default [])
      --cap-add list                   Add Linux capabilities
      --cap-drop list                  Drop Linux capabilities
      --cgroup-parent string           Optional parent cgroup for the
                                       container
      --cidfile string                 Write the container ID to the file
      --cpu-period int                 Limit CPU CFS (Completely Fair
                                       Scheduler) period
      --cpu-quota int                  Limit CPU CFS (Completely Fair
                                       Scheduler) quota
      --cpu-rt-period int              Limit CPU real-time period in
                                       microseconds
      --cpu-rt-runtime int             Limit CPU real-time runtime in
                                       microseconds
  -c, --cpu-shares int                 CPU shares (relative weight)
      --cpus decimal                   Number of CPUs
      --cpuset-cpus string             CPUs in which to allow execution
                                       (0-3, 0,1)
      --cpuset-mems string             MEMs in which to allow execution
                                       (0-3, 0,1)
  -d, --detach                         Run container in background and
                                       print container ID
      --detach-keys string             Override the key sequence for
                                       detaching a container
      --device list                    Add a host device to the container
      --device-cgroup-rule list        Add a rule to the cgroup allowed
                                       devices list
      --device-read-bps list           Limit read rate (bytes per second)
                                       from a device (default [])
      --device-read-iops list          Limit read rate (IO per second)
                                       from a device (default [])
      --device-write-bps list          Limit write rate (bytes per
                                       second) to a device (default [])
      --device-write-iops list         Limit write rate (IO per second)
                                       to a device (default [])
      --disable-content-trust          Skip image verification (default true)
      --dns list                       Set custom DNS servers
      --dns-option list                Set DNS options
      --dns-search list                Set custom DNS search domains
      --entrypoint string              Overwrite the default ENTRYPOINT
                                       of the image
  -e, --env list                       Set environment variables
      --env-file list                  Read in a file of environment variables
      --expose list                    Expose a port or a range of ports
      --group-add list                 Add additional groups to join
      --health-cmd string              Command to run to check health
      --health-interval duration       Time between running the check
                                       (ms|s|m|h) (default 0s)
      --health-retries int             Consecutive failures needed to
                                       report unhealthy
      --health-start-period duration   Start period for the container to
                                       initialize before starting
                                       health-retries countdown
                                       (ms|s|m|h) (default 0s)
      --health-timeout duration        Maximum time to allow one check to
                                       run (ms|s|m|h) (default 0s)
      --help                           Print usage
  -h, --hostname string                Container host name
      --init                           Run an init inside the container
                                       that forwards signals and reaps
                                       processes
  -i, --interactive                    Keep STDIN open even if not attached
      --ip string                      IPv4 address (e.g., 172.30.100.104)
      --ip6 string                     IPv6 address (e.g., 2001:db8::33)
      --ipc string                     IPC mode to use
      --isolation string               Container isolation technology
      --kernel-memory bytes            Kernel memory limit
  -l, --label list                     Set meta data on a container
      --label-file list                Read in a line delimited file of labels
      --link list                      Add link to another container
      --link-local-ip list             Container IPv4/IPv6 link-local
                                       addresses
      --log-driver string              Logging driver for the container
      --log-opt list                   Log driver options
      --mac-address string             Container MAC address (e.g.,
                                       92:d0:c6:0a:29:33)
  -m, --memory bytes                   Memory limit
      --memory-reservation bytes       Memory soft limit
      --memory-swap bytes              Swap limit equal to memory plus
                                       swap: '-1' to enable unlimited swap
      --memory-swappiness int          Tune container memory swappiness
                                       (0 to 100) (default -1)
      --mount mount                    Attach a filesystem mount to the
                                       container
      --name string                    Assign a name to the container
      --network string                 Connect a container to a network
                                       (default "default")
      --network-alias list             Add network-scoped alias for the
                                       container
      --no-healthcheck                 Disable any container-specified
                                       HEALTHCHECK
      --oom-kill-disable               Disable OOM Killer
      --oom-score-adj int              Tune host's OOM preferences (-1000
                                       to 1000)
      --pid string                     PID namespace to use
      --pids-limit int                 Tune container pids limit (set -1
                                       for unlimited)
      --privileged                     Give extended privileges to this
                                       container
  -p, --publish list                   Publish a container's port(s) to
                                       the host
  -P, --publish-all                    Publish all exposed ports to
                                       random ports
      --read-only                      Mount the container's root
                                       filesystem as read only
      --restart string                 Restart policy to apply when a
                                       container exits (default "no")
      --rm                             Automatically remove the container
                                       when it exits
      --runtime string                 Runtime to use for this container
      --security-opt list              Security Options
      --shm-size bytes                 Size of /dev/shm
      --sig-proxy                      Proxy received signals to the
                                       process (default true)
      --stop-signal string             Signal to stop a container
                                       (default "15")
      --stop-timeout int               Timeout (in seconds) to stop a
                                       container
      --storage-opt list               Storage driver options for the
                                       container
      --sysctl map                     Sysctl options (default map[])
      --tmpfs list                     Mount a tmpfs directory
  -t, --tty                            Allocate a pseudo-TTY
      --ulimit ulimit                  Ulimit options (default [])
  -u, --user string                    Username or UID (format:
                                       <name|uid>[:<group|gid>])
      --userns string                  User namespace to use
      --uts string                     UTS namespace to use
  -v, --volume list                    Bind mount a volume
      --volume-driver string           Optional volume driver for the
                                       container
      --volumes-from list              Mount volumes from the specified
                                       container(s)
  -w, --workdir string                 Working directory inside the container
```
지금 우리는 이것을 알 수 없다. 기본 과정에서는 내가 지금까지 한번이라도 본 적있는 Tag들만 추려서 기술하고,
진행해나가면서 필요한 부분들을 개별적으로 파악하는 방식으로 해보자.

Name | Command Options | Description
-------- | ------------------------------ | ---------------------------------
detach |-d, --detach              |           Run container in background and print container ID
env |  -e, --env list           |            Set environment variables              
interactive | -i, --interactive           |         Keep STDIN open even if not attached   
link |    --link list              |        Add link to another container          
name |    --name string             |       Assign a name to the container         
rm |    --rm                       |      Automatically remove the container when it exits
tty | -t, --tty                       |     Allocate a pseudo-TTY                  
volume | -v, --volume list                |    Bind mount a volume          
port | -p, --publish list       |           Publish a container's port(s) to the host
workdir | -w, --workdir list       |           Working directory inside the container


2. 터미널에 `docker images` 입력
`busybox`를 실행하기에 앞서 `docker images`의 결과를 살펴보면, 이미지 로컬 저장소에 `busybox` 이미지가 없다는 것을 확인한다.

```Bash
$ docker images
REPOSITORY       TAG       IMAGE ID       CREATED       SIZE
elasticsearch    latest    7a047c21aa48   2 weeks ago   581MB
```

3. 터미널에 `docker run busybox` 입력
`busybox` 이미지가 로컬저장소에 없는 경우, 자동으로 `pull`을 수행한 뒤에 `run` 실행한다.

* `busybox`이미지가 로컬 저장소에 없는 경우

```Bash
$ docker run busybox
Unable to find image 'busybox:latest' locally      # if not exist in your local, automatically pull the image before running
latest: Pulling from library/busybox
0ffadd58f2a6: Pull complete
Digest: sha256:bbc3a03235220b170ba48a157dd097dd1379299370e1ed99ce976df0355d24f0
Status: Downloaded newer image for busybox:latest
```

* `busybox` 이미지가 로컬 저장소에 있는 경우

```Bash
$ docker run busybox
```

~~뭐가 방금 지나갔냐? 순식간에 일어난 일이라!~~

4. `docker ps` 커맨드로 실행 확인
이런 경우, `docker ps`를 이용한다. ~~현재로서(?) 적절한 모니터링 방법이 없는 우리는 `docker ps`랑 친해져야 한다. 그런 다음 슬프게도 살아 있는지 육안검사를 수행해야한다.~~

```Bash
$ docker ps
CONTAINER ID  IMAGE     COMMAND   CREATED          STATUS                     PORTS   NAMES
```
"어랏~ 내가 커맨드를 잘못 입력했나? 실행이 안된 것 같은데." 라고 생각하시지만, 아래의 명령어를 입력하면, 실행이 되자마자 바로 종료되었다는 것을 알 수 있다. `docker ps`은 현재 구동 중인 컨테이너가 표시 대상이기 때문에 종료된 컨테이너는 표시되지 않는 것이다.

만약 종료된 컨테이너의 확인이 필요하다면, 터미널에 `docker ps -a`라고 입력해야 한다.

```Bash
$ docker ps -a
CONTAINER ID  IMAGE     COMMAND   CREATED          STATUS                     PORTS   NAMES
4a601734ce26  busybox   "sh"      11 seconds ago   Exited (0) 10 seconds ago          hopeful_dijkstra
```

그렇다면, Docker 에서는 `docker ps`와 `docker ps -a`를 구분할까? 그 이유를 확인하기 위해서는 또다른 실습을 진행하자.

### `docker run` 기본실습 II (`--rm`)

```Bash
$ docker run --rm b01 busybox
```

```Bash
$ docker ps
CONTAINER ID  IMAGE     COMMAND   CREATED          STATUS                     PORTS   NAMES
```

```Bash
$ docker ps -a
CONTAINER ID  IMAGE     COMMAND   CREATED          STATUS                     PORTS   NAMES
```

내가 웹서비스를 운영하고 특정 시간에 트래픽이 갑자기 몰려드는 시간이 있어 미리 서비스를 위한 웹서버를 증설할 필요가 있다고 가정하자. 이 서버는 유연한 확장을 위해서 내부에 데이터를 저장하지 않는다. 로그도 외부 스토리지에 저장하고, 보여주는 데이터도 외부의 데이터베이스나 캐시를 이용한다. 이런 서비스를 Docker로 구성하는 경우 트래픽이 증가되는 시점에 미리 서비스를 구축하고, 트래픽이 감소하면 웹서버도 줄여나가야 한다. 이런 서비스라면, 웹서버에 대한 컨테이너가 없애는 즉시 컨테이너의 이력 또한 가지고 있을 필요가 없다.    
그렇다면 여기서 추가적으로 짚고 넘어갈 부분이 있는데, 만약 컨테이너가 동작을 수행하고 종료되면, 컨테이너를 유지 되는 것을 원하지 않는다면, `--rm` 옵션을 사용하면 된다.

### `docker run` 기본실습 III (`--name`)

`--name`은 컨테이너의 이름을 명시적으로 정의한다. 이름을 명시하지 않아도  Docker 엔진에서 명명한 랜덤한 이름으로 컨테이너의 이름을 지정하고 동작한다.

#### 이름을 명시하는 방법

```Bash
$ docker run --rm --name b02 busybox
```

```Bash
$ docker ps -a
CONTAINER ID  IMAGE    COMMAND   CREATED         STATUS                     PORTS  NAMES
177ac385ab9f  busybox  "sh"      13 seconds ago  Exited (0) 12 seconds ago         b02
```

#### 이름을 명시하지 않는 방법

```Bash
$ docker run busybox
```

```Bash
$ docker ps -a
CONTAINER ID  IMAGE    COMMAND   CREATED         STATUS                     PORTS  NAMES
177ac385ab9f  busybox  "sh"      13 seconds ago  Exited (0) 12 seconds ago         hopeful_dijkstra
```

#### 컨테이너 정리 (복습)

```Bash
$ docker rm `docker ps -aq`
```

### `docker run` 기본실습 IV (`-i`, `-t`, `-d`)

#### `-i`를 사용

`-t` 옵션이 설정되지 않아 터미널 없이 컨테이너가 생성되어 키보드 입력 불가

```Bash
$ docker run --rm -i --name b01 busybox
_
```

```Bash
$ docker ps
CONTAINER ID  IMAGE    COMMAND   CREATED         STATUS          PORTS  NAMES
177ac385ab9f  busybox  "sh"      13 seconds ago  Up 12 seconds          b01
```

#### `-t`를 사용

interactive 옵션이 설정되지 않아 터미널은 열렸지만, 입력 불가

```Bash
$ docker run --rm -t --name b01 busybox
/ #
```

```Bash
$ docker ps
CONTAINER ID  IMAGE    COMMAND   CREATED         STATUS          PORTS  NAMES
177ac385ab9f  busybox  "sh"      13 seconds ago  Up 12 seconds          b01
```

#### `-it` 또는 `-t -i`를 사용

```Bash
$ docker run --rm -t --name b01 busybox
/ # ls
bin   dev   etc   home  proc  root  sys   tmp   usr   var
```


```Bash
$ docker run --rm -t --name b01 busybox sh
/ # ls
bin   dev   etc   home  proc  root  sys   tmp   usr   var
```


```Bash
$ docker ps
CONTAINER ID  IMAGE    COMMAND   CREATED         STATUS          PORTS  NAMES
177ac385ab9f  busybox  "sh"      13 seconds ago  Up 12 seconds          b01
```

#### `docker start`
이 부분을 살펴보기 위해서 `docker start`를 커맨드를 확인하자.

```Bash
$ docker start --help

Usage:  docker start [OPTIONS] CONTAINER [CONTAINER...]

Start one or more stopped containers

Options:
  -a, --attach                  Attach STDOUT/STDERR and forward signals
      --checkpoint string       Restore from this checkpoint
      --checkpoint-dir string   Use a custom checkpoint storage directory
      --detach-keys string      Override the key sequence for detaching a
                                container
      --help                    Print usage
  -i, --interactive             Attach container's STDIN
```

#### `-p`

여기서 elasticsearch를 한번 사용해보자.

* -d 옵션을 설정하는 경우
```Bash
$ docker run --rm -d --name es01 elsaticsearch
...
$ _
```

* -d 옵션을 설정하지 않은 경우
```Bash
$ docker run --rm --name es01 elsaticsearch
...
```

위에 보면, elasticsearch  서비스가 구동되어 있고, 외부에서 수집된 정보를 인덱싱을 하기 위하여 서비스에 전송하려고 한다. 그러나 컨테이너는 격리된 독립된 네트워크를 구성하기 때문에 접근이 불가능하다. 호스트 컴퓨터에 컨테이너의 네트워크에 접근하기 위해서는 `docker run`을 수행하는 시점에 네트워크 포트 수준의 접근경로(?)를 설정해야 한다. 이것이 `-p` 옵션이다. 수행해야할 docker 커맨드는 아래와 같다.

* 옵션을 설정하지 않은 경우
```Bash
$ docker run -d --rm --name es01 -p 9200:9200 elasticsearch
...
```



#### `docker exec`
```Bash
$ docker exec -it b01 sh
Error response from daemon: Container 36bc12d4906331074010071c7d7ec46f4cb7b3f81875c89fdc289f1a8b981077 is not running
```

```Bash
> docker ps
CONTAINER ID  IMAGE    COMMAND   CREATED         STATUS        PORTS  NAMES
177ac385ab9f  busybox  "sh"      15 seconds ago  Up 13 seconds        b01
```

```Bash
$ docker exec -i b01 sh
```

```Bash
$ docker exec -t b01 sh
```

```Bash
$ docker exec -i -t b01 sh
$ docker exec -it b01 sh
/ # ls
bin   dev   etc   home  proc  root  sys   tmp   usr   var
```

#### `-d`

```Bash
$ docker run -d --name b04 busybox sh
01c17c134779813af882c520851721e9fd8584e8c2b1c4834633b9e9df074559
```

```Bash
$ pwd
/home/user
```

#### `-v`

```Bash
docker run --rm -it --name p09 -v /home/dbuser/materials:/home python /bin/bash
root@0b797705326e:/# _
```

#### `-w`

```Bash
docker run --rm -it --name p09 -v /home/dbuser/materials:/home -w /home python /bin/bash
root@0b797705326e:/home# _
```

#### `docker run` Check List
- [x] detach (-d, --detach)
- [ ] env (-e, --env list)
- [x] interactive (-i, --interactive)
- [ ] link (--link list)
- [x] name (--name string)
- [x] rm (--rm)
- [x] tty (-t, --tty)
- [x] volume (-v, --volume list)
- [x] port (-p, --publis list)
- [x] workdir (-w, --work list)


#### `docker commit`


```Bash
$ docker run -it --name p01 python /bin/bash
# pip install flask
...
# exit

$ docker commit p01 mypython:latest
sha256:79444887fab40186dd6e4ac67a9da2b576821fb1d08fb629334cd145519100a0

$ docker run -it --name mp01 mp python
Python 3.6.3 (default, Nov  4 2017, 22:17:09)
[GCC 4.9.2] on linux
Type "help", "copyright", "credits" or "license" for more information.
>>> import flask
>>> exit()
```

#### `docker build`

```bash
$ vi Dockerfile
```

```vi
[editor]
FROM Ubuntu:14.04
RUN apt-get update
:wq
```

```Bash
$ sudo docker build -t myunbuntu .
Sending build context to Docker daemon 39.66 MB
Step 1/2 : FROM ubuntu:14.04
14.04: Pulling from library/ubuntu
01a4f8387457: Pull complete
c887940e680c: Pull complete
5432573ac160: Pull complete
027ee9a9665e: Pull complete
5611db80430d: Pull complete
Digest: sha256:7b3b72e6a388c24c0cc3e0d1aade3364c52f03e54bd5ab440f8fd93c69203733
Status: Downloaded newer image for ubuntu:14.04
 ---> d6ed29ffda6b
Step 2/2 : RUN apt-get update
 ---> Running in 01c3daffabe2
Get:1 http://security.ubuntu.com trusty-security InRelease [65.9 kB]
Ign http://archive.ubuntu.com trusty InRelease
Get:2 http://archive.ubuntu.com trusty-updates InRelease [65.9 kB]
Get:3 http://archive.ubuntu.com trusty-backports InRelease [65.9 kB]
Get:4 http://archive.ubuntu.com trusty Release.gpg [933 B]
Get:5 http://archive.ubuntu.com trusty Release [58.5 kB]
Get:6 http://security.ubuntu.com trusty-security/universe Sources [80.1 kB]
Get:7 http://archive.ubuntu.com trusty-updates/universe Sources [245 kB]
Get:8 http://security.ubuntu.com trusty-security/main amd64 Packages [859 kB]
Get:9 http://archive.ubuntu.com trusty-updates/main amd64 Packages [1293 kB]
Get:10 http://security.ubuntu.com trusty-security/restricted amd64 Packages [18.0 kB]
Get:11 http://security.ubuntu.com trusty-security/universe amd64 Packages [250 kB]
Get:12 http://archive.ubuntu.com trusty-updates/restricted amd64 Packages [21.4 kB]
Get:13 http://security.ubuntu.com trusty-security/multiverse amd64 Packages [4716 B]
Get:14 http://archive.ubuntu.com trusty-updates/universe amd64 Packages [561 kB]
Get:15 http://archive.ubuntu.com trusty-updates/multiverse amd64 Packages [16.3 kB]
Get:16 http://archive.ubuntu.com trusty-backports/main amd64 Packages [14.7 kB]
Get:17 http://archive.ubuntu.com trusty-backports/restricted amd64 Packages [40 B]
Get:18 http://archive.ubuntu.com trusty-backports/universe amd64 Packages [52.5 kB]
Get:19 http://archive.ubuntu.com trusty-backports/multiverse amd64 Packages [1392 B]
Get:20 http://archive.ubuntu.com trusty/universe Sources [7926 kB]
Get:21 http://archive.ubuntu.com trusty/main amd64 Packages [1743 kB]
Get:22 http://archive.ubuntu.com trusty/restricted amd64 Packages [16.0 kB]
Get:23 http://archive.ubuntu.com trusty/universe amd64 Packages [7589 kB]
Get:24 http://archive.ubuntu.com trusty/multiverse amd64 Packages [169 kB]
Fetched 21.1 MB in 24s (849 kB/s)
Reading package lists...
 ---> bbdd2ef30da8
Removing intermediate container 01c3daffabe2
Successfully built bbdd2ef30da8
```

```Bash
$ docker images
REPOSITORY          TAG                 IMAGE ID            CREATED             SIZE
myubuntu            latest              bbdd2ef30da8        35 seconds ago      242 MB
...
```
