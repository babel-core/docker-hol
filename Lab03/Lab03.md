# Lab 3. 나만의 Docker 이미지 만들기

지난 실습에서는 docker 커맨드를 이용하여 [hub.docker.com](https://hub.docker.com/)에 공개된 이미지들을 실행하는 방법을 알아보았다. 이와 같이 이미지를 공유하는 생태계가 있다면, 복잡한 엔지니어링 지식이 없이도 수많은 서비스를 큰 노력없이 실행할 수 있었다. (물론, 구축할 의도에 맞게 docker 커맨드를 조작하는 방법에 대한 이해가 필요하다.)
하지만 실제 서비스에 컨테이너 기반의 관리 정책을 도입하기 위해서는 변화되는 환경에 빠르게 변화시킬 수 있는 기술이 필요하다. 이런 환경까지 Docker 허브의 생태계가 보장해주지는 않기 때문이다. 따라서 컨테이너 기반으로 서비스를 운영하는 전략을 수립하기 위해서는 의도에 맞게 이미지를 생성하고, 수정할 수 있어야 하며, 테스트 환경과 배포 환경의 유기적으로 연계할 수 있는 환경과 배포/롤백의 체계를 수립할 수 있는 환경이 필요하다.

이번 실습에서는 `docker build`을 이용하여 Docker 이미지를 생성하는 방법에 대하여 알아본다. 사실, Docker 이미지를 생성하는 방법은 지난 실습에서 `docker commit`을 이용하는 방법에 대하여 살펴보았고, 아카이빙 이미지를 복원하는 방법 등 이미지를 생성하는 다양한 방법이 존재한다. 하지만 `docker build`를 이용하는 방법 이외의 방법들은 이미지가 가지고 있는 내부 구성에 대하여 명확히 파악하기 어려운 단점이 존재한다.

서비스의 관리수준을 향상하기 위해서는 `docker build`를 기반으로 이미지를 관리하는 것이 좋을 것으로 생각한다.

## docker build 커맨드 시작하기

여기서는 가장 기본이라 할 수 있는 scratch 이미지를 이용하여 간단한 이미지를 생성하는 실습을 진행한다.

- Target Image Name : genesis

`docker build` 커맨드를 사용하기 위해서는 폴더를 생성하고, 폴더에 3가지 구성요소가 포함된다. 내용이 포함된다.

실습에 앞서 구성요소 3가지에 대하여 알아보자.
**1.(필수) Dockerfile 작성 방법** Dockerfile은 이미지를 생성하기 위한 시나리오가 기록된 있는 파일로서 `docker build`에서 필수적인 파일이다. 정의된 규칙에 맞춰 작성하는 것이 필요하다.
**2. (선택) 이미지 내부로 전달 예정인 파일** 호스트에 가진 파일을 생성될 이미지에 전달하고자하는 경우에 사용된다.
**3. (선택) .dockerignore 파일** 호스트의 대상 파일 중 전달되지 않아야 하는 대상을 선정하는 경우에 사용한다. 이미지에 보안에 방해요소들을 제거하여 운영하는 경우 유용할 것이다.

이제 본격적인 실습에 들어가보자.
다음은 [busybox](https;//github.com/)라는 이미지를 생성하는 것을 Dockerfile 이용하여 `genesis`라는 이미지를 생성할 것이다.

먼저 디렉토리의 구성정보를 알아보자.

```tree
$ tree
.
├── Dockerfile
└── busybox.tar.xz
```

Dockerfile은 자세히 보면, 아래와 같이 작성되어 있다.

```Dockerfile
FROM scratch
MAINTAINER byjeon <mysummit@gmail.com>

# Add file
ADD busybox.tar.xz /

CMD ["sh"]
```

Dockerfile은 일반적으로 **명령어**와 **정보**로 구성된다.
명령어에 대한 정보는 다음과 같다.
- `FROM`은 생성하고자 하는 기본 이미지를 의미한다.
- `MAINTAINER`는 Dockerfile를 구성하는 조직이나 인물을 기록한다.
- `ADD`는 호스트에 존재하는 파일을 이미지의 특정폴더에 추가한다.
- `CMD`는 컨테이너가 실행되는 경우 실행되는 커맨드를 정의한다.


`docker build` 커맨드의 사용방법은 다음과 같다.

```Bash
$ sudo docker build --help

Usage:	docker build [OPTIONS] PATH | URL | -

Build an image from a Dockerfile

Options:
      --build-arg list             Set build-time variables (default [])
      --cache-from stringSlice     Images to consider as cache sources
      --cgroup-parent string       Optional parent cgroup for the container
      --compress                   Compress the build context using gzip
      --cpu-period int             Limit the CPU CFS (Completely Fair Scheduler) period
      --cpu-quota int              Limit the CPU CFS (Completely Fair Scheduler) quota
  -c, --cpu-shares int             CPU shares (relative weight)
      --cpuset-cpus string         CPUs in which to allow execution (0-3, 0,1)
      --cpuset-mems string         MEMs in which to allow execution (0-3, 0,1)
      --disable-content-trust      Skip image verification (default true)
  -f, --file string                Name of the Dockerfile (Default is 'PATH/Dockerfile')
      --force-rm                   Always remove intermediate containers
      --help                       Print usage
      --isolation string           Container isolation technology
      --label list                 Set metadata for an image (default [])
  -m, --memory string              Memory limit
      --memory-swap string         Swap limit equal to memory plus swap: '-1' to enable unlimited swap
      --network string             Set the networking mode for the RUN instructions during build (default "default")
      --no-cache                   Do not use cache when building the image
      --pull                       Always attempt to pull a newer version of the image
  -q, --quiet                      Suppress the build output and print image ID on success
      --rm                         Remove intermediate containers after a successful build (default true)
      --security-opt stringSlice   Security options
      --shm-size string            Size of /dev/shm, default value is 64MB
  -t, --tag list                   Name and optionally a tag in the 'name:tag' format (default [])
      --ulimit ulimit              Ulimit options (default [])
```
생각보다 복잡한데, 여기서는 --tag(-t)만을 다룬다.
`genesis`이미지를 만드는 명령어와 빌드화면은 다음과 같습니다.

```bash
$ sudo docker build --tag genesis .
Sending build context to Docker daemon 597.5 kB
Step 1/4 : FROM scratch
 --->
Step 2/4 : MAINTAINER byjeon <mysummit@gmail.com>
 ---> Running in b83affeca271
 ---> d15161ed7223
Removing intermediate container b83affeca271
Step 3/4 : ADD busybox.tar.xz /
 ---> f1d06a3e6b5d
Removing intermediate container a944e7f7d7d6
Step 4/4 : CMD sh
 ---> Running in 1d8d7db6ad56
 ---> c5de1cf67f83
Removing intermediate container 1d8d7db6ad56
Successfully built c5de1cf67f83
```
마지막 문구가 출력되는 것으로 보아 이미지가 정상적으로 생성된 것을 알 수 있다.
그렇다면, 이미지가 생성이 정상적으로 완료되었는지를 확인하기 위하여 터미널에서 `docker images`를 생성하자.

```bash
$ sudo docker images
REPOSITORY     TAG       IMAGE ID         CREATED          SIZE
genesis        latest    c5de1cf67f83     29 seconds ago   1.13 MB
```
