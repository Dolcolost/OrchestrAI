# Base image with Python 3.9
FROM python:3.9

RUN apt-get update
RUN apt-get install -y software-properties-common
RUN apt-get install -y ffmpeg
RUN apt-get install -y bash


# Download the files
COPY predict/ app/
COPY saved_model/ app/saved_model/

# Set the working directory
WORKDIR /app

# Install the Python dependencies
RUN pip3 install -r requirement.txt
