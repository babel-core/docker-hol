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
1. **(필수) Dockerfile 작성 방법** Dockerfile은 이미지를 생성하기 위한 시나리오가 기록된 있는 파일로서 `docker build`에서 필수적인 파일이다. 정의된 규칙에 맞춰 작성하는 것이 필요하다.
2. **(선택) 이미지 내부로 전달 예정인 파일** 호스트에 가진 파일을 생성될 이미지에 전달하고자하는 경우에 사용된다.
3. **(선택) .dockerignore 파일** 호스트의 대상 파일 중 전달되지 않아야 하는 대상을 선정하는 경우에 사용한다. 이미지에 보안에 방해요소들을 제거하여 운영하는 경우 유용할 것이다.

### 기본 이미지 생성하기

이제 본격적인 실습에 들어가보자.
다음은 [busybox](https;//github.com/)라는 이미지를 생성하는 것을 Dockerfile 이용하여 `genesis`라는 이미지를 생성할 것이다.

먼저 디렉토리의 구성정보를 알아보자.

```tree
$ tree
.
├── Dockerfile
└── busybox.tar.xz
```
`busybox.tar.xz`는 우리가 이미지 생성시 추가하여 이미지를 구성하는 데에 사용할 것이다 Dockerfile에 파일이 이미지의 구성에 활용할 수 있도록 정의할 것이다. Dockerfile의 작성 내용은 다음과 같다.

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

### busybox 

이번 절에서는 Docker의 공식 이미지인 busybox와 유사한 이미지를 만들 것이다. 
여기서는 기본 이미지에 `ENV`과 `WORKDIR` 명령어의 사용사례를 알아본다.
폴더의 구성은 다음과 같다.

```Bash
$ tree
.
|____Dockerfile
```

이미지 생성할 폴더에는 Dockerfile만을 이용하여 빌드를 진행할 것이다. Dockerfile의 내용은 다음과 같다.   

```Dockerfile
FROM genesis
MAINTAINER byjeon <mysummit@gmail.com>

# set env
ENV HOME_DIR=/home
WORKDIR $HOME_DIR

CMD ["/bin/sh"]
```

이 Dockerfile은 방금 전에 우리가 만든 `genesis`이미지를 활용할 것이다. 그런데 우리는 시작하는 동시에 HOME 디렉토리에서 시작하고자 한다. 그리고 Docker 내부에서도 홈 디렉토리의 정보를 환경변수로 정의하여 사용한다고 가정하자. 그렇다면 우리는 `ENV`명령어를 이용하여 HOME_DIR를 환경변수를 정의하고, `WORKDIR` 명령어를 이용하여 이미지가 실행되는 초기 디렉토리 위치를 정의할 수 있다. 환경변수와 시작 디렉토리를 정의하는 방법을 위의 Dockerfile에서 볼 수 있다.  

이제 원하는 설정을 모두 마쳤으니, `busybox:custom`이라는 이미지로 `build`를 진행하자.
`busybox`은 이미지 이름이고, `custom`은 태그 이름이다.  

```Bash
$ docker build -t busybox:custom .
(...)
```

여기서 주목할 점은 busybox:`custom`이라고 이미지를 생성할 때, 이미지의 태그를 정의했다는 것이다.
이제 이미지를 실행해보자. 

```Bash
$ docker run -it --rm busybox:custom 
/home # cd /
/ # echo $HOME_DIR
/home
```

위의 쉘이 수행되는 것을 보면, Dockerfile에서 정의된 `ENV`가 컨테이너에서도 활용할 수 있다는 것을 알 수 있다. 

```Bash
$ docker images
REPOSITORY          TAG                 IMAGE ID            CREATED             SIZE
busybox             custom              a4c850b3cb5a        29 minutes ago      1.13MB
```

### Flask 애플리케이션 이미지 생성하기

우리는 이제 Flask 애플리케이션을 Docker로 구축하고자한다. 우분투 16.04를 기반으로 서비스를 구축하려고 한다.
이미지를 구성하는 데에 필요한 구성요소는 다음과 같다. 
여기서 `EXPOSE`명령어를 알아본다. 

```Bash
.
|____Dockerfile
|____hello.py
```

우리의 Flask 서비스는 `hello.py`에 모두 구성되어 있다.  
서비스 환경을 구축하기 위한 Dockerfile은 다음과 같다. 

```Dockerfile
FROM ubuntu:16.04
MAINTAINER byjeon <mysummit@gmail.com>

# Install Packages
RUN apt-get update && \
    apt-get install -y python3 python3-pip && \
    apt-get clean && \
    pip3 install flask

# Add a Service File
ADD hello.py /apps/hello.py

# Expose Network Port
EXPOSE 5000

# Running the service
CMD ["python3", "/apps/hello.py"]
```

서비스의 기반 환경이 우분투 16.04이기 때문에, 우리는 우분투 이미지를 기반으로 시작한다.
그리고 우투분에는 python3는 설치되어 있지 않기 때문에 `python3`과 파이썬 패키지 설치를 위하여 `pip`를 설치한다. 그리고 pip를 이용하여 `Flask`를 설치한다. 

> 여기서 주목할 점은 `&&` 를 이용하여 4개의 커맨드를 병렬적으로 수행했다는 점이다. 만약 커맨드를 개별적으로 수행하게 되면, build 과정에서 Step이 분리되며, 이미지를 구성하는 Layer가 분리된다. 개인적인 생각으로는 Layer를 단순화 시키는 것이 실력이지 않을까 생각한다.  

여기서 알아보고자 하는 것은 `EXPOSE` 명령어이다. 이 명령어는 컨테이너의 포트를 호스트 컴퓨터에 노출한다고 명시적으로 정의하는 것이다. 물론 정의한다고 실행할 때, 호스트와 연동되는 것은 아니며, 정의를 안한다고 호스트와 연결하는 것이 가능하다. `docker run` 커맨드를 수행할 때, `-P`를 이용하여 쉽게 노출시킬 수 있고, Dockerfile을 구성원들이 해석하는 데에 도움이 될 수 있다고 생각한다. 

자, 그럼 이제 이미지를 빌드해보자.

```Bash
$ docker build -t ubuntu:flask0to1 .
(...)
```

패키지를 설치하는 데에 상당히 많은 시간이 걸려 빌드가 완료되었다.
이제 이미지를 다양한 방식으로 실행하여 어떻게 컨테이너가 생성되었는 지 확인해보자.

#### 퍼블리쉬 없이 컨테이너 실행하기

```Bash
$ docker run -d --rm --name flask1 ubuntu:flask0to1
bd751dfb9f6769b825109760d7965a14fd366605142b1685f2814f55a8b34f08
```

#### `-P`로 퍼블리쉬하여 컨테이너 실행하기

```Bash
$ docker run -d --rm --name flask2 -P ubuntu:flask0to1
f1f7b43a73ef3bb57a0cb3206abb3ed0dfb209f7ad2e84292c119f204ca08d36
```

#### `-p`로 퍼블리쉬하여 컨네이너 실행하기 

```Bash
$ docker run -d --rm --name flask3 -p 5000:5000 ubuntu:flask0to1
f1f7b43a73ef3bb57a0cb3206abb3ed0dfb209f7ad2e84292c119f204ca08d36
```

컨테이너를 실행되는 상태를 확인하면 다음과 같다. 

```Bash
$ docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                     NAMES
a535029d73ec        ubuntu:flask0to1    "python3 /apps/hel..."   2 seconds ago       Up 2 seconds        0.0.0.0:5000->5000/tcp    flask3
bd751dfb9f67        ubuntu:flask0to1    "python3 /apps/hel..."   4 minutes ago       Up 4 minutes        5000/tcp                  flask2
f1f7b43a73ef        ubuntu:flask0to1    "python3 /apps/hel..."   7 minutes ago       Up 7 minutes        0.0.0.0:32772->5000/tcp   flask1
a535029d73ec        ubuntu:flask0to1    "python3 /apps/hel..."   2 seconds ago       Up 2 seconds                                  flask0
```

- `flask1`는 퍼블리쉬 인자 없이 컨테이너를 실행한 것으로 호스트 포트가 연결되어 있지 않아 외부에서 서비스의 접근이 불가능하다. 
- `flask2`는 `-P`인자를 이용하여 암묵적으로 호스트 포트와 명시된 포트를 연결한 것이다. 
- `flask3`는 `-p`인자를 이용하여 명시적으로 호스트 포트와 컨테이너 포트를 연결한 것이다. 서비스 운영상에서 가장 권장되는 방식이다.
- `flask0`는 Dockerfile에 EXPOSE가 없이 `flask1`과 같이 실행시킨 것이다. 그렇지만 `flask3`과 같은 방식으로 컨테이너를 실행하다면, 외부에서 서비스의 접근이 가능하다.


## 마무리
