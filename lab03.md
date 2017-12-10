# Docker Image 생성하기

```Bash
$ sudo docker images -q -f dangling=true

$ sudo docker ps -q -f status=exited
```

## 첫번째 Docker 이미지 빌드하기

Docker를 빌드하기 위해서는 Dockerfile이 필요하다.
여기서 중요한 포인트는 2가지이다. 파일명이 정화해야 한다는 것이다. Dockerfile

```Bash
$ mkdir 1-dangling
$ cd 1-dangling
$ vi Dockerfile
```

```Dockerfile
FROM ubuntu:16.04

ENTRYPOINT ["/bin/echo"]
```

```Bash
$ mkdir 2-hello
$ cd 2-hello
$ vi Dockerfile
```

```Dockerfile
FROM ubuntu:16.04

CMD ["/bin/echo", "Hello Docker!"]
```


```Bash
$ sudo docker build -t babel:hello .
Sending build context to Docker daemon 2.048 kB
Step 1/2 : FROM ubuntu:16.04
 ---> 20c44cd7596f
Step 2/2 : CMD /bin/echo Hi Docker!
 ---> Running in db52dd9d98fd
 ---> 249cee17323d
Removing intermediate container db52dd9d98fd
Successfully built 249cee17323d
```

```Bash
$ sudo docker run 249cee17323d
Hi Docker!
```

```Bash
$ sudo docker build .
Sending build context to Docker daemon 2.048 kB
Step 1/2 : FROM ubuntu:16.04
 ---> 20c44cd7596f
Step 2/2 : ENTRYPOINT /bin/echo
 ---> Running in ede1e5cbf99d
 ---> 08cde8ab3bdb
Removing intermediate container ede1e5cbf99d
Successfully built 08cde8ab3bdb
```

```Dockerfile
FROM myubuntu:16.04
MAINTAINER byjeon <byjeon@miracom.co.kr>

RUN apt-get install python3
RUN apt-get install python3-pip

CMD ["/bin/bash", "python3"]
```

```Bash
$ sudo docker build -t mypython3:fault .
```

```Dockerfile
FROM myubuntu:16.04
MAINTAINER byjeon <byjeon@miracom.co.kr>
RUN apt-get install -y python3 python3-pip

CMD ["/bin/bash", "python3"]
```

```Bash
$ sudo docker build -t mypython3:base .
```

```Bash
$ sudo docker images
REPOSITORY   TAG     IMAGE ID       CREATED             SIZE
mypython3    base    07abe535eced   12 seconds ago      446 MB
myubuntu     16.04   7f9d552f6819   About an hour ago   162 MB
```

## Application #1 : Flask App 배포하기

```Dockerfile
FROM ubuntu:16.04
MAINTAINER byjeon <byjeon@miracom.co.kr>
RUN apt-get install -y python3 python3-pip && \
    apt-get clean all && \
    pip3 install flask

ADD hello.py /apps/hello.py

EXPOSE 5000

CMD ["python3", "/apps/hello.py"]
```


## 개선하기

```Dockerfile
FROM ubuntu:16.04
MAINTAINER byjeon <byjeon@miracom.co.kr>
RUN apt-get update && \
    apt-get install -y python3 python3-pip && \    
    apt-get clean all
RUN pip3 install flask

ADD hello.py /apps/hello.py

EXPOSE 5000 9200

CMD ["python3", "/apps/hello.py"]
```


 ```Bash
 $ sudo docker images -q -f dangling=true

 $ sudo docker ps -q -f status=exited
 ```

 ## 첫번째 Docker 이미지 빌드하기

 Docker를 빌드하기 위해서는 Dockerfile이 필요하다.
 여기서 중요한 포인트는 2가지이다. 파일명이 정화해야 한다는 것이다. Dockerfile

 ```Bash
 $ mkdir 1-dangling
 $ cd 1-dangling
 $ vi Dockerfile
 ```

 ```Dockerfile
 FROM ubuntu:16.04

 ENTRYPOINT ["/bin/echo"]
 ```

 ```Bash
 $ mkdir 2-hello
 $ cd 2-hello
 $ vi Dockerfile
 ```

 ```Dockerfile
 FROM ubuntu:16.04

 CMD ["/bin/echo", "Hello Docker!"]
 ```


 ```Bash
 $ sudo docker build -t babel:hello .
 Sending build context to Docker daemon 2.048 kB
 Step 1/2 : FROM ubuntu:16.04
  ---> 20c44cd7596f
 Step 2/2 : CMD /bin/echo Hi Docker!
  ---> Running in db52dd9d98fd
  ---> 249cee17323d
 Removing intermediate container db52dd9d98fd
 Successfully built 249cee17323d
 ```

 ```Bash
 $ sudo docker run 249cee17323d
 Hi Docker!
 ```

 ```Bash
 $ sudo docker build .
 Sending build context to Docker daemon 2.048 kB
 Step 1/2 : FROM ubuntu:16.04
  ---> 20c44cd7596f
 Step 2/2 : ENTRYPOINT /bin/echo
  ---> Running in ede1e5cbf99d
  ---> 08cde8ab3bdb
 Removing intermediate container ede1e5cbf99d
 Successfully built 08cde8ab3bdb
 ```

 ```Dockerfile
 FROM myubuntu:16.04
 MAINTAINER byjeon <byjeon@miracom.co.kr>

 RUN apt-get install python3
 RUN apt-get install python3-pip

 CMD ["/bin/bash", "python3"]
 ```

 ```Bash
 $ sudo docker build -t mypython3:fault .
 ```

 ```Dockerfile
 FROM myubuntu:16.04
 MAINTAINER byjeon <byjeon@miracom.co.kr>
 RUN apt-get install -y python3 python3-pip

 CMD ["/bin/bash", "python3"]
 ```

 ```Bash
 $ sudo docker build -t mypython3:base .
 ```

 ```Bash
 $ sudo docker images
 REPOSITORY   TAG     IMAGE ID       CREATED             SIZE
 mypython3    base    07abe535eced   12 seconds ago      446 MB
 myubuntu     16.04   7f9d552f6819   About an hour ago   162 MB
 ```

 ## Application #1 : Flask App 배포하기

 ```Dockerfile
 FROM ubuntu:16.04
 MAINTAINER byjeon <byjeon@miracom.co.kr>
 RUN apt-get install -y python3 python3-pip && \
     apt-get clean all && \
     pip3 install flask

 ADD hello.py /apps/hello.py

 EXPOSE 5000

 CMD ["python3", "/apps/hello.py"]
 ```


 ## 개선하기

 ```Dockerfile
 FROM ubuntu:16.04
 MAINTAINER byjeon <byjeon@miracom.co.kr>
 RUN apt-get update && \
     apt-get install -y python3 python3-pip && \    
     apt-get clean all
 RUN pip3 install flask

 ADD hello.py /apps/hello.py

 EXPOSE 5000 9200

 CMD ["python3", "/apps/hello.py"]
 ```

 ## List
  1. FROM
  2. MAINTAINER
  3. RUN
  4. ENTRYPOINT
  5. CMD
  6. ENV
  7.
