FROM ubuntu:latest
LABEL authors="bartosz"

ENTRYPOINT ["top", "-b"]
