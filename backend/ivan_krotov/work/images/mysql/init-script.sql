CREATE DATABASE IF NOT EXISTS app;
USE app;

create table organization
(
    id     int auto_increment primary key,
    name   varchar(60)   null,
    parent int default 0 null,
    constraint organization_id_uindex
        unique (id)
);