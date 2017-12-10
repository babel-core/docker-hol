FROM mypython3:base
MAINTAINER byjeon <byjeon@miracom.co.kr>

RUN pip3 install flask

ADD hello.py /apps/hello.py

EXPOSE 80

CMD ["python3", "/apps/hello.py"]
