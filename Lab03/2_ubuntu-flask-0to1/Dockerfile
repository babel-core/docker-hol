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
