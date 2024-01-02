alter table
    request_log
add
    payload json null
after
    agent;
