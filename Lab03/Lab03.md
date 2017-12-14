# Lab 3. 나만의 Docker 이미지 만들기

지난 실습에서는 [hub.docker.com](https://hub.docker.com/)에 공개된 다양한 형태의 이미지들을 이용하여 Docker 컨테이너를 실행하는 방법을 커맨드를 기준으로 알아보았다. 이와 같이 잘 조성된 에코시스템이 있다면, 우리는 Docker 컨테이너를 실행하는 명령어 한 줄이면, 복잡한 엔지니어링 지식이 없이 수많은 서비스를 큰 노력없이 실행할 수 있었다.
하지만 실제 서비스 수준으로 끌어올리기 위해서는 내가 구축하고자 하는 의도에 맞게 이미지를 생성하고, 수정할 수 있어야 하며, 배포할 수 있는 환경을 구축할 수 있어야 한다.

이번 실습에서는 `docker build`을 이용하여 Docker 이미지를 생성하는 방법에 대하여 알아본다. 사실, Docker 이미지를 생성하는 방법은 지난 실습에서 `docker commit`을 이용하는 방법에 대하여 살펴보았고, 아카이브한 이미지를 복원하는 방법 등 이미지를 생성하는 다양한 방법이 존재한다. 하지만 `docker build`를 이용하는 방법 이외의 방법들은 이미지가 가지고 있는 내부 구성에 대하여 명확히 파악하기 어려운 단점이 존재한다.

서비스의 관리수준을 향상하기 위해서는 `docker build`를 기반으로 이미지를 관리하는 것이 좋을 것으로 생각한다.

## `docker build` 커맨드 시작하기
docker의 가장 근원이 되는 이미지는 `scratch` 이미지이다.
여기서는 scratch 이미지를 이용하여 가장 기본적인 이미지를 생성하는 실습을 진행한다.

- Image Name : genesis

```Dockerfile
FROM scratch
MAINTAINER byjeon <mysummit@gmail.com>

# Add file
ADD busybox.tar.xz /

CMD ["sh"]
```

```tree
$ tree
.
├── Dockerfile
└── busybox.tar.xz
```

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

```bash
$ sudo docker images
REPOSITORY     TAG       IMAGE ID         CREATED          SIZE
genesis        latest    c5de1cf67f83     29 seconds ago   1.13 MB
```



나만의 이미지를 생성하여  관리 영역으로 서비스를 운영하다
컨테이너 기반으로 서비스를 운영하게 되면 유연하게 조정이 가능하다. 서비스의 부하량를 예측하여 최적화된 서비스를 확장하거나 축소하는 것이 가장 대표적이다. 또한 이미지를 이용하여 빠르게 컨테이너를 어떻게 구성하느냐에 따라서 빠르게 배포를  연계되는 서비스가 변화되거나 특정 될 수 있다. 따라서 컨테이너 기반으로 서비스를 운영한다면,  이에 맞게 컨테이너의 환경도 변화해야하고, 이미지의  가져야할 필요가 생기게 된다. 하지만 하고 하기 마련이다. 이런 경우 내가 원하는 docker 이미지를 생성하는 방법은 여러가지가 있다. docker
