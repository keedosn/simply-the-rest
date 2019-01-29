FROM mariadb:10.1

ENV MYSQL_DATABASE jazzy
ENV MYSQL_USER jazzy
ENV MYSQL_PASSWORD jazzy
ENV MYSQL_ROOT_PASSWORD jazzy

ADD sql/ /docker-entrypoint-initdb.d

EXPOSE 3306

CMD ["mysqld"]
