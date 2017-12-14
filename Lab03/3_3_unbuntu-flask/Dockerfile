FROM ubuntu:python3
MAINTAINER byjeon <byjeon@miracom.co.kr>

# flask
RUN pip3 install flask

# Add a service file
ADD hello.py /apps/hello.py

# Expose Network
EXPOSE 5000

# Execute a Service
CMD ["python3", "/apps/hello.py"]
