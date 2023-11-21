FROM python:3.9-slim

RUN apt-get update \
    && apt-get install -y software-properties-common \
    && apt-get install -y ffmpeg \
    && apt-get install -y bash \
    && apt-get install -y curl gnupg


RUN apt-get install ca-certificates \
	&& install -m 0755 -d /etc/apt/keyrings \
	&& curl -fsSL https://download.docker.com/linux/debian/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg \
	&& chmod a+r /etc/apt/keyrings/docker.gpg \
	&& echo \
		"deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/debian \
		"$(. /etc/os-release && echo "$VERSION_CODENAME")" stable" | \
		tee /etc/apt/sources.list.d/docker.list > /dev/null \
	&& apt-get update \
	&& apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
	 

COPY predict/ app/predict/
COPY saved_model/ app/saved_model/
COPY learning/ app/learning/

WORKDIR /app

RUN curl -sL https://deb.nodesource.com/setup_19.x | bash -
RUN apt-get install -y nodejs

# Install the Python dependencies
RUN pip3 install --no-cache-dir -r /app/learning/requirement.txt

RUN npm install dl-librescore@latest

CMD ["python", "/app/learning/task.py", "--link", "https://www.youtube.com/watch?v=ZRtdQ81jPUQ"]