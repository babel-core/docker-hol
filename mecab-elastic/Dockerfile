FROM ubuntu:16.04
MAINTAINER byjeon <byjeon@miracom.co.kr>

RUN apt-get update
RUN apt-get install -y wget build-essential autotools-dev automake libmecab2 libmecab-dev default-jdk
RUN wget -O - https://bitbucket.org/eunjeon/mecab-ko/downloads/mecab-0.996-ko-0.9.2.tar.gz | tar zxfv - 
RUN cd mecab-0.996-ko-0.9.2 ; ./configure ; make ; make install ; ldconfig
RUN wget -O - https://bitbucket.org/eunjeon/mecab-ko-dic/downloads/mecab-ko-dic-2.0.1-20150920.tar.gz | tar zxfv -
RUN cd mecab-ko-dic-2.0.1-20150920 ; ./configure ; make ; make install ; ldconfig
RUN wget -O - https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-5.2.0.tar.gz | tar xzvf -

CMD ["/bin/bash"]
