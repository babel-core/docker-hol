# Lab 3. 나만의 Docker 이미지 만들기

## `docker build` 맛보기
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
