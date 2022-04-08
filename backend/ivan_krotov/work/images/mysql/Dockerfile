FROM mysql:8

ENV MYSQL_ROOT_PASSWORD=secret

ADD ./init-script.sql /docker-entrypoint-initdb.d

EXPOSE 3306
