# docker build

Dockerfile

```Bash
# vi Dockerfile
```

```Dockerfile
FROM ubuntu:16.04
MAINTAINER byjeon <mysummit@gmail.com>

RUN apt-get update && apt-get install -y default-jdk
RUN tar zxfv elasticsearch-5.2.0.tar.gz
```


## List
 1. FROM
 2. MAINTAINER
 3. RUN
 4.
 
