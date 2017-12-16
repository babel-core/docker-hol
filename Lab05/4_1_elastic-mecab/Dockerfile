FROM ubuntu:16.04
MAINTAINER byjeon <mysummit@gmail.com>

# Essentials
RUN apt-get update
RUN apt-get install -y wget build-essential autotools-dev automake libmecab2 libmecab-dev default-jdk

# Mecab
RUN wget -O - https://bitbucket.org/eunjeon/mecab-ko/downloads/mecab-0.996-ko-0.9.2.tar.gz | tar zxfv -
RUN cd mecab-0.996-ko-0.9.2; ./configure; make; make install; ldconfig

# Mecab-Ko-Dic
RUN wget -O - https://bitbucket.org/eunjeon/mecab-ko-dic/downloads/mecab-ko-dic-2.0.1-20150920.tar.gz | tar zxfv -
RUN cd mecab-ko-dic-2.0.1-20150920; sh ./autogen.sh
RUN cd mecab-ko-dic-2.0.1-20150920; ./configure; make; make install; ldconfig

# ElasticSearch
RUN wget -O - https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-5.2.0.tar.gz | tar zxfv -

EXPOSE 9200

CMD ["/bin/bash"]
