FROM ubuntu:python3
MAINTAINER byjeon <mysummit@gmail.com>

# Install the flask package
RUN pip3 install flask

# Add a service file
ADD hello.py /apps/hello.py

# Expose a network port
EXPOSE 5000

# Running the service
CMD ["python3", "/apps/hello.py"]
