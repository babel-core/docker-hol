FROM ubuntu:16.04
MAINTAINER byjeon <byjeon@miracom.co.kr>
RUN apt-get update && \
    apt-get install -y python3 python3-pip && \
    apt-get clean && \
    pip3 install flask
ADD hello.py /apps/hello.py
EXPOSE 80
CMD ["python3", "/apps/hello.py"]
