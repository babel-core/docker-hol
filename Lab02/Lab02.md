# Lab 2. Docker CLI를 이용하여 1개의 컨테이너 실행하기

현재까지 Docker를 실무에 사용하지 않는 나는 일관성, 유연함, 에코시스템 등에서 Docker의 매력에 푹빠졌다. 1년동안 Docker를 이용할 기회가 생길 때마다 패키지를 설치하는 대신 과감하게(?) Github과 Dockerhub에 공개된 Docker 이미지를 적용해보곤 하였다. 그대로 따라하는 경우 잘 동작하지만, 환경을 스스로 구축하게 되면, 내가 의도한 것과 다른 상황에 직면하게 되었다. 누군가에게는 일관성을 무기로 적용하는 시스템이 나를 외면하는 이유는 무엇일까? 이런 상황이 반복된다면, Docker를 이용하여 개발환경을 구축하기도 어렵고, 서비스를 구축하는 것은 불가능할 것이다.

이런 경험 속에서 최근에 내가 무지했다는 것을 깨달았다. 나는 `Docker CLI`의 기본적인 사용법도 명확하게 파악하지 못하고 있다는 것을... 터미널에 수많은 Docker 커맨드를 입력하였지만, 내가 입력한 커맨드가 어떠한 컨테이너를 생성하는지? 내가 입력한 인자가 어떠한 의도를 가지는지? 잘 모른채 동작자체에만 집중하고 있었던 것이다. 그래서 남들이 만들어놓은 이미지와 남들이 만들어준 커맨드를 이용하면 문제없이 동작하지만, 내가 머릿 속으로 그린 관점을 그대로 옮기려고 하면, 운이 좋으면 잘 동작하고, 어딘가 다르게 동작하는 경우도 있었던 것이다. 이제는 동작자체보다는 정확하게 동작시키는 것에 집중해보자.

우선 Docker 컨테이너 1개를 잘 동작시키는 방법에 대해서 알아볼 것이다. 그러기 위해서는 `Docker CLI`에 대해 실습해 볼 것이다.

## Docker CLI 구성

무작정 터미널을 열고 `docker` 커맨드를 입력하여 Docker CLI의 구성을 파악해보자.  

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

터미널에 `docker`만을 입력하면 동작은 하지 않고, `help`와 동일한 동작을 수행한다. 그리고 CLI는 3가지 커맨드 카테고리-`Options`, `Management Commands`, `Commands`-을 볼 수 있다. `docker engine`의 설정은 `Option`에 있는 인자를 이용하여 사용하는 것이고, `docker`의 실제 수행은 `Commands`와 `Management Commands`에 나열된 서브 커맨드(sub-command)를 조합하여 입력해야 수행된다.

docker 커맨드에 구성되어 있는 Tag들과 보조 커맨드에 대하여 간략히 살펴보도록 한다.

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

알아야 하는 것이 너무 많아 보인다. 일단 우리는 1개의 컨테이너를 동작시키는 것에 집중하고, 이에 필요한 요소가 무엇인지를 파악해보도록 하자.

## Docker 컨터이너 실행하기

Docker 컨테이너를 실행하기 위해서는 `docker run (blah)(blah)(blah)` 커맨드를 입력해야 한다.
정확한 사용법을 파악하기 위하여 터미널에 `docker run --help` 입력한다.

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

스크롤의 압박이 굉장한 커맨드라는 것을 알 수 있다. 지금 우리는 1개의 컨테이너를 잘 동작하도록 하는 것에만 집중하려고 한다. 따라서 1개의 컨테이너를 동작시키는 기본 인자만을 정리해보도록 한다.

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

여기서는 `busybox`라는 Docker에서 제공하는 공식 이미지를 이용하여 기본 동작을 이해할 것이다.

### 1. 터미널에 `docker images` 입력
`busybox`를 실행하기에 앞서 내가 보유한 이미지를 확인하기 위한 선행학습을 시작할 것이다. 현재 내가 보유하고 있는 이미지를 살펴보기 위해서는 터미널에 `docker images`를 입력하면 된다.

```Bash
$ docker images
REPOSITORY       TAG       IMAGE ID       CREATED       SIZE
elasticsearch    latest    7a047c21aa48   2 weeks ago   581MB
elasticsearch    5.2.0     7a047c21aa48   2 weeks ago   581MB
```

현재 내가 보유하고 있는 이미지는 elasticsearch라는 이미지가 2개 있다는 것을 알 수 있다. 동일한 이름이 여러 개가 있을 수 있을까? 동일한 이름은 얼마든지 있을 수 있다. 왜냐하면 Docker는 원래 IMAGE ID로 관리하고, IMAGE_NAME:TAG의 형태로 저장소에서 유일하게 가질 수 있다. 여기서 또 하나 특이한 점은 위의 두 이미지는 동일한 IMAGE ID를 가지고 있다. 이름은 다르지만 동일한 이미지라는 것을 유추해 볼 수 있다.

### 2. 터미널에 `docker run busybox` 입력

이제 우리는 busybox를 실행할 것이다. 이미지가 저장소에 존재하는 경우와 존재하지 않는 경우가 다르다. 경우에 따라 동작하는 것을 살펴보자.

#### `busybox`이미지가 로컬 저장소에 없는 경우

```Bash
$ docker run busybox
Unable to find image 'busybox:latest' locally      # if not exist in your local, automatically pull the image before running
latest: Pulling from library/busybox
0ffadd58f2a6: Pull complete
Digest: sha256:bbc3a03235220b170ba48a157dd097dd1379299370e1ed99ce976df0355d24f0
Status: Downloaded newer image for busybox:latest
```
`busybox` 이미지가 저장소에 존재하지 않으면, 이미지를 내려받아 실행한다. 이미지를 내려받는 것을 `pull`이고, 이미지를 실행하는 것은 `run`이다. 다시 말해 이미지가 존재하지 않으면, pull을 실행한다음, run이 실행되는 것이다.

#### `busybox` 이미지가 로컬 저장소에 있는 경우

```Bash
$ docker run busybox
```

다시 한번 실행해보면, 이런 기본적인 명령어로는 아무런 동작도 하지 않는 것처럼 보인다. 실제 동작이 하지 않은 것일까? 실제 동작은 하였지만 눈여 띄지 않는다. 그 이유는 컨테이너에 아무런 인자가 존재하지 않는다면, 컨테이너 시작과 동시에 종료되도록 구성되어 있기 때문이다.
이런 경우 컨테이너가 동작하였는지 확인하는 과정이 필요하다.

### 3. 컨테이너 동작여부 확인하기

컨테이너가 동작여부를 파악하기 위하여 `docker ps`를 이용한다. 컨테이너가 우리가 의도한 데로 동작하고 있는지를 판단하기 위하여 `docker ps`를 많이 사용하게 될 것이다. 우리는 docker ps와 친해져야 한다.

```Bash
$ docker ps
CONTAINER ID  IMAGE     COMMAND   CREATED          STATUS                     PORTS   NAMES
```
커맨드를 입력하여도 아무런 정보도 출력되지 않는다. 앞서 언급하였듯이 컨테이너는 시작과 동시에 종료되었을 것이다. docker ps는 현재 실행 중인 컨테이너를 출력하는 커맨드이다. 종료된 컨테이너까지 모두 확인하기 위해서는 다음과 같이 인자를 추가해야 한다.

```Bash
$ docker ps -a
CONTAINER ID  IMAGE     COMMAND   CREATED          STATUS                     PORTS   NAMES
4a601734ce26  busybox   "sh"      11 seconds ago   Exited (0) 10 seconds ago          hopeful_dijkstra
```

만약 종료된 컨테이너의 리스트를 보기 위해서는 다음과 같이 필터링하는 인자 `-f`를 추가한다.

```Bash
$ docker ps -f status=exited
CONTAINER ID  IMAGE     COMMAND   CREATED          STATUS                     PORTS   NAMES
4a601734ce26  busybox   "sh"      11 seconds ago   Exited (0) 10 seconds ago          hopeful_dijkstra
```

- **CONTAINER ID** : 컨테이너의 고유식별자
- **IMAGE** : 실행한 이미지 이름
- **COMMAND** : 컨테이너에 수행된 커맨드
- **CREATED** : 컨테이너 생성된 시점
- **STATUS** : 컨테이너의 상태
- **PORTS** : 컨테이너의 연결된 포트 (7절)
- **NAMES** : 컨테이너의 이름, 근데 왜 NAMES일까? (6절)

그런데 `docker ps -a`와 `docker ps -f status=exited`를 입력하여도 표시되지 않는 경우가 있다.
이를 명확하게 파악하기 위하여 종료된 컨테이너를 제거하는 명령어를 알아보자.

```Bash
$ docker rm `docker ps -aq`
4a601734ce26
```

이제 모든 컨테이너를 리스트를 확인하더라도 존재하지 않는 것을 확인할 수 있다.

```Bash
$ docker ps -a
CONTAINER ID  IMAGE     COMMAND   CREATED          STATUS                     PORTS   NAMES
```

### 4. docker run에 인자 `--rm` 추가하기

앞에서 docker ps -a를 수행하여도 남지 않는 경우가 존재한다고 언급하였다. 이 경우가 컨테이너를 수행하는 경우에 인자 `--rm`를 사용하는 경우이다. 이 인자는 컨테이너가 종료되면 자동적으로 컨테이너를 제거하기 때문이다. 실습으로 들어가보자.

```Bash
$ docker run --rm busybox
```

방금 전에 컨테이너를 실행했던 것과 같이 컨테이너가 실행하자마자 종료되는 것을 알 수 있다. 컨테이너가 수행되었는지 리스트를 출력해보자.

```Bash
$ docker ps
CONTAINER ID  IMAGE     COMMAND   CREATED          STATUS                     PORTS   NAMES
```
이 리스트에는 없는 것이 당연하다고 생각되고, 모든 컨테이너의 리스트를 출력해보자.

```Bash
$ docker ps -a
CONTAINER ID  IMAGE     COMMAND   CREATED          STATUS                     PORTS   NAMES
```

이 리스트에 역시 존재 않는 것을 볼 수 있다. 예전에 컨테이너를 지속적으로 사용하기 위하여 많은 시간을 할애하여 패키지를 설치하고 종료하였더니, 컨테이너가 온데 간데 없어 낭패를 본 경우가 있었다. 이런 경험이 존재한다면, 이 본이도 모르게 인자가 커맨드에 끼어 있었을 것이다.
만약 특정 시간에 트래픽이 몰리는 서비스에서 데이터를 외부에 저장하거나, 데이터를 전달하는 서비스의 경우에 스케일링이 요구된다고 가정하자.  이런 사례에서 Docker로 구성되어 있다면, 이 인자를 활용하면 유용할 것이다. 트래픽이 증가되기 이전에 이미 생성된 이미지를 준비하고, 트래픽이 증가하는 시점에 맞춰 컨테이너를 동작시킨다. 트래픽이 점차 감소하면 컨테이너를 종료한다. 컨테이너의 이력이 남아 있을 이유도 없다. 물론 로그도 외부에 존재해야 한다.
결론적으로 만약 컨테이너가 동작을 수행하고 종료되면, 컨테이너를 유지가 필요없는 서비스에는 `--rm` 옵션을 사용하자.

### 5. 컨테이너 이름 지정하기(`--name`)

위에서 우리는 컨테이너를 구성할 때, 이름을 명시하지 않아도 Docker 엔진에서 명명한 랜덤한 이름으로 컨테이너의 이름을 지정하고 동작한다.

```Bash
$ docker run busybox

$ docker ps -a
CONTAINER ID  IMAGE    COMMAND   CREATED         STATUS                     PORTS  NAMES
177ac385ab9f  busybox  "sh"      13 seconds ago  Exited (0) 12 seconds ago         hopeful_dijkstra
```
위의 결과를 보면, 여기서는 `NAMES`에 hopeful_dijkstra이라는 이름으로 되어 있다. 아마 모두 다른 결과로 출력될 것이다. 우리는 지금까지 컨테이너의 이름을 정의하지 않고 실행하였다. 이런 경우 Docker Engine에서 임의의 이름을 발행하여 컨테이너의 이름으로 사용한다. 인자를 `--rm`를 사용하는 경우를 제외하고, 대부분의 경우에는 이름을 지정하여 운영하는 것이 좋을 것이다. 이름을 명시적으로 사용하기 위해서는 어떤 인자를 사용할까?

우리는 인자 `--name`를 이용하여 명시적으로 컨테이너의 이름을 정의한다.

```Bash
$ docker run --name mybusybox busybox
```

```Bash
$ docker ps -a
CONTAINER ID  IMAGE    COMMAND   CREATED         STATUS                     PORTS  NAMES
177ac385ab9f  busybox  "sh"      13 seconds ago  Exited (0) 12 seconds ago         mybusybox
```

일반적으로 OS에서 프로세스를 실행하는 방식은 Foreground 모드와 Background 모드로 나눌 수 있다. 도커 컨테이너도 2가지 모드를 실행할 수 있는데, 다음 두 절에서 각각의 모드로 실행하는 것을 살펴본다.

### 6. Foreground 모드로 컨테이너 실행하기 (`--interactive --tty`, `-it`, `-ti`, `-i -t`, `-t -i`)

Foreground 모드로 컨테이너 실행하기 위해서는 인자 `--interactive --tty`를 모두 사용하여 실행한다. 그런데 너무 길어서 대부분의 경우에는 약어로된 인자를 사용하게 된다. 그리고 여기서 유의할 점은 foreground로 정상적으로 동작하기 위해서는 두 인자를 모두 사용하는 것이 필요하다.

#### `-it` 또는 `-t -i` 또는 `-ti`를 동시에 사용

```Bash
$ docker run --rm -t --name b01 busybox
/ # ls
bin   dev   etc   home  proc  root  sys   tmp   usr   var
```

-i와 -t만을 사용하면 약간의 문제가 있다는 것을 알아보기 위하여 실습을 해본다.

#### `-i`만 사용

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

#### `-t`만 사용

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

### 7. Background 모드로 컨테이너 실행하기 (`--detach, -d`)

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





### `docker start`
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

### `docker exec`

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

### 호스트와 컨테이너의 파일시스템 연동하기 (`-volumes`,`-v`)

```Bash
docker run --rm -it --name p09 -v /home/dbuser/materials:/home python /bin/bash
root@0b797705326e:/# _
```

### 컨테이너 기본 디렉토리 설정하기 (`--workdir`, `-w`)

```Bash
docker run --rm -it --name p09 -v /home/dbuser/materials:/home -w /home python /bin/bash
root@0b797705326e:/home# pwd
/home
```

### 컨테이너에 환경변수 설정하기 (`--workdir`, `-w`)

```Bash
docker run --rm -it --name p09 -v /home/dbuser/materials:/home -w /home python /bin/bash
root@0b797705326e:/home# _
```

### 마치며

`docker run`의 Check List
- [x] detach (-d, --detach)
- [x] env (-e, --env list)
- [x] interactive (-i, --interactive)
- [x] name (--name string)
- [x] rm (--rm)
- [x] tty (-t, --tty)
- [x] volume (-v, --volume list)
- [x] port (-p, --publis list)
- [x] workdir (-w, --work list)
