#!/bin/bash

MYSQL="mysql -hlocalhost -utest test -A -v -v -v"

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
    ## You should choose good numbers for this section by yourself 
    ## They should be large enough
    for i in $(seq 10 -1 1)
    do  
        echo $i
    
        for j in $(seq 1 1000)
        do    
            user1="floor(Rand()*20)"
            user2="floor(Rand()*20)"
            v="(0,  ${user1}, ${user2}, now(), 'messagemessagemessage')"
            values=$(for tmp in $(seq 1 10000); do echo -n "${v},"; done; echo ${v};)
            echo "INSERT INTO messages VALUES ${values}" | $MYSQL
        done
    done
}



createTable
populateTable

