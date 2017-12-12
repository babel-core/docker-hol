FROM myubuntu:16.04

RUN apt-get install -y wget default-jdk g++ make build-essential

RUN useradd -ms /bin/bash dbuser
USER dbuser
WORKDIR /home/dbuser
