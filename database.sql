CREATE DATABASE php_login_management;
CREATE DATABASE php_login_management_test;

use php_login_management;
use php_login_management_test;

create table users(
    id varchar(255) not null ,
    name varchar(255) not null,
    password varchar(255) not null ,
    primary key (id)
)engine innodb;

create table sessions(
    id varchar(255) not null ,
    user_id varchar(255) not null ,
    primary key (id)
)engine innodb;

alter table sessions
add constraint fk_session_user
foreign key (user_id) references users(id);

select * from users;
select * from sessions;