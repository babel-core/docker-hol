# Lab 3. 나만의 Docker 이미지 만들기

지난 실습에서는 docker 커맨드를 이용하여 [Docker 허브](https://hub.docker.com/)에 공개된 이미지들을 가져와 실행하는 방법을 알아보았다.

우리는 Docker 생태계의 테두리 안에서 복잡한 엔지니어링 지식이 없이 한 줄의 docker 커맨드 한줄을 작성하는 수준으로 설치, 구축, 배포와 같이 번거로운 작업들을 간소화할 수 있었다.

하지만 운영하는 서비스는 시시각각 변한다. 대부분의 서비스는 사람들이 잠든시간과 활동 시간의 서비스가 변화할 것이고, 오픈 마켓은 이벤트 시간에 트래픽이 몰릴 것이다. 중국의 관군제나 블락 프라이데이 같은 시즌에 영향을 받을 수 있다. 이렇듯 사용자를 대상으로 하는 서비스는 시시각각 최적화된 서비스를 유연하게 구성하는 것이 경쟁력일 것이다. 어떤 서비스는 사용자의 접속 패턴이 다양하여 사용자의 패턴에 맞게 서비스를 확장/축소하는 유연한 시스템을 구성해야할 수 있고,

또한 시간이 지남에 서비스를 사용하는 대상 또한 변해간다. 어떤 서비스는 최종 사용자(end-user)가 인간이 아닌 사물인 IoT(Internet of Things) 환경일 수도 있다. 이와 같은 서비스는 현재의 서비스는 시작에 불과하기 때문에 수많은 가능성을 열어두는 시스템을 구축하지 않는다면,확장의 제약에 묶여 다양한 서비스를 수행하지 못하여 도태될 것이다.

따라서 실제 서비스에 컨테이너 기반의 관리 정책을 도입하기 위해서는 변화되는 환경에 맞춰 적절한 이미지를 구성할 수 있는 기술과 자기만의 서비스를 운영하는 전략과 노하우가 필요하다. 서비스에 의도에 최적화된 이미지를 생성하고, 수정할 수 있어야 하며, 테스트 환경과 배포 환경의 유기적으로 연계할 수 있는 환경과 배포/롤백의 체계를 수립할 수 있는 환경과 기술이 수반되어야 한다. 또한 모니터링도 중요하다. 손가락 숫자 수준의 티어를 가진 기존의 IT 환경과는 다르게 컨테이너 기반의 서비스 운영은 컨테이너간의 연결관계와 클러스터링되어 있고, 병목을 명확하게 파악하여 개선하는 수준의 모니터링 기술이 수반되어야 할 것이다.  

이번 실습에서는 그 시작 단계인 나만의 이미지를 생성하는 방법에 대해 알아본다. 이미지를 생성하는 방법은 여러 가지가 있다.
- `docker commit` 방식 : 커맨드를 이용하여 이미지를 생성할 수 있고,
- `docker save` 방식 : 커맨드를 이용하여 저장된 이미지를 복구하여 이미지를 생성할 수도 있다.
- `docker build` 방식 :

`docker commit`과 `docker save`를 이용한 이미지 생성 방법은 이미지의 세부적인 내부 구조를 이해없이 이미지를 구성하거나 복원하는 방식이다. 따라서 이미지를 유연하게 운영하는 것에는 한계를 가질 것이다. 여기서는 `docker build`커맨드를 이용하여 Docker 이미지를 생성하는 방법에 대하여 넓고 얕게 알아본다. 서비스의 관리수준을 향상하기 위해서는 `docker build`를 기반으로 이미지를 관리하는 것이 좋을 것으로 생각한다.

## docker build 커맨드 시작하기

여기서는 가장 기본이라 할 수 있는 scratch 이미지를 이용하여 간단한 이미지를 생성하는 실습을 진행한다.

- Target Image Name : genesis

`docker build` 커맨드를 사용하기 위해서는 폴더를 생성하고, 폴더에 3가지 구성요소가 포함된다. 내용이 포함된다.

실습에 앞서 구성요소 3가지에 대하여 알아보자.
- **1.(필수) Dockerfile 작성 방법** Dockerfile은 이미지를 생성하기 위한 시나리오가 기록된 있는 파일로서 `docker build`에서 필수적인 파일이다. 정의된 규칙에 맞춰 작성하는 것이 필요하다.
- **2. (선택) 이미지 내부로 전달 예정인 파일** 호스트에 가진 파일을 생성될 이미지에 전달하고자하는 경우에 사용된다.
- **3. (선택) .dockerignore 파일** 호스트의 대상 파일 중 전달되지 않아야 하는 대상을 선정하는 경우에 사용한다. 이미지에 보안에 방해요소들을 제거하여 운영하는 경우 유용할 것이다.

이제 본격적인 실습에 들어가보자.
다음은 [busybox](https;//github.com/)라는 이미지를 생성하는 것을 Dockerfile 이용하여 `genesis`라는 이미지를 생성할 것이다.

먼저 디렉토리의 구성정보를 알아보자.

```tree
$ tree
.
├── Dockerfile
└── busybox.tar.xz
```
`busybox.tar.xz`는 우리가 이미지 생성시 추가하여 이미지를 구성에 활용할 것이다 Dockerfile의 작성할 것이다.
Dockerfile의 작성 내용은 다음과 같다.

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


나만의 이미지를 생성하여  관리 영역으로 서비스를 운영하다
컨테이너 기반으로 서비스를 운영하게 되면 유연하게 조정이 가능하다. 서비스의 부하량를 예측하여 최적화된 서비스를 확장하거나 축소하는 것이 가장 대표적이다. 또한 이미지를 이용하여 빠르게 컨테이너를 어떻게 구성하느냐에 따라서 빠르게 배포를  연계되는 서비스가 변화되거나 특정 될 수 있다. 따라서 컨테이너 기반으로 서비스를 운영한다면,  이에 맞게 컨테이너의 환경도 변화해야하고, 이미지의  가져야할 필요가 생기게 된다. 하지만 하고 하기 마련이다. 이런 경우 내가 원하는 docker 이미지를 생성하는 방법은 여러가지가 있다. docker
