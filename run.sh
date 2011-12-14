#!/bin/bash

MYSQL="mysql -hlocalhost -utest test -A"
RESTART_MYSQL="sudo service mysqld restart"

function createTable(){
    echo "Dropping table"
    echo "DROP TABLE IF EXISTS messages" | $MYSQL
    echo "Creating table"
    echo 'CREATE TABLE messages ( 
            message_id int not null auto_increment, 
            user1 int not null, 
            user2 int not null, 
            ts timestamp not null default current_timestamp, 
            body longtext not null, 
            PRIMARY KEY (message_id), 
            KEY (user1, user2, ts) 
          ) ENGINE=InnoDB' | $MYSQL
}

function populateTable(){
    echo "Populating table"
    for i in $(seq 10 -1 1)
    do  
        echo $i
    
        for j in $(seq 1 100)
        do    
            user1="floor(Rand()*100000)"
            user2="floor(Rand()*100000)"
            v="(0,  ${user1}, ${user2}, now(), md5(now()))"
            values=$(for tmp in $(seq 1 10000); do echo -n "${v},"; done; echo ${v};)
            echo "INSERT INTO messages VALUES ${values}" | $MYSQL
        done
    done
}


function performOffsetTest(){
    echo "Performing offset benchmarking"
}

function performSequentialAccessTest(){
    echo "Performing sequential access benchmarking"
}

createTable
populateTable

performOffsetTest
performSequentialAccessTest

