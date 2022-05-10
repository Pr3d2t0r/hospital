create schema if not exists hospital;

use hospital;

create table address
(
    id   int(4) auto_increment primary key,
    name text not null,
    city varchar(100) null
);

create table consultations
(
    id         int(4) auto_increment primary key,
    doctor_id  int(4) not null,
    patient_id int(4) not null,
    recipe_id  int(4) not null,
    state      enum ('ESPERA', 'OCORRENDO', 'CONCLUIDA', 'DESMARCADA', 'REMARCADA') not null,
    date       date null,
    created_at datetime null,
    updated_at datetime null
);

create table contacts
(
    id      int auto_increment primary key,
    subject varchar(200) not null,
    content text null,
    email   varchar(100) not null,
    name    varchar(80)  not null,
    sent_at datetime null
);

create table doctor
(
    id         int(4) auto_increment primary key,
    name       varchar(80)  not null,
    nib        varchar(25)  not null,
    nif        varchar(9) not null,
    specialty  varchar(100) not null,
    address_id int(4) not null,
    birthday   date null,
    image_path varchar(250) null,
    created_at datetime null
);

create table nurse
(
    id         int(4) auto_increment primary key,
    name       varchar(80)  not null,
    nib        varchar(25)  not null,
    nif        int(9) not null,
    specialty  varchar(100) not null,
    address_id int(4) not null,
    birthday   date null,
    image_path varchar(250) null,
    created_at datetime null
);

create table nurse_consultation
(
    id              int(4) auto_increment primary key,
    consultation_id int(4) not null,
    nurse_id        int(4) not null
);

create table patient
(
    id         int(4) auto_increment primary key,
    name       varchar(80) not null,
    nib        varchar(25) not null,
    n_utente   int(9) not null,
    address_id int(4) not null,
    birthday   date null,
    image_path varchar(250) null,
    created_at datetime null
);

create table products
(
    id         int(4) auto_increment primary key,
    name       varchar(200) not null,
    image_path varchar(250) null
);

create table recipe_product
(
    id         int(4) auto_increment primary key,
    recipe_id  int(4) not null,
    product_id int(4) not null
);

create table recipes
(
    id          int(4) auto_increment primary key,
    notes       text null,
    name        varchar(200) not null,
    recipe_path varchar(250) null
);

create table users
(
    id          int(4) auto_increment primary key,
    username    varchar(50) not null,
    password    char(40)    not null,
    permissions longtext null,
    active      tinyint(1) null,
    nurse_id    int(4) null,
    doctor_id   int(4) null,
    patient_id  int(4) null,
    constraint doctor_id unique (doctor_id),
    constraint nurse_id unique (nurse_id),
    constraint patient_id unique (patient_id)
);

create table users_tokens
(
    id      int auto_increment primary key,
    user_id int(4) not null,
    token   varchar(255) null,
    constraint token unique (token)
);