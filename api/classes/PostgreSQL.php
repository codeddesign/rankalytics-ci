<?php

class PostgreSQL
{
    protected $config, $connection;

    function __construct()
    {
        $db = config_item('database');
        $pg = $db['pgsql'];

        //sets:
        $this->config = array(
            "host" => $pg['hostname'],
            "port" => 5432,
            "dbname" => $pg['database'],
            "user" => $pg['username'],
            "password" => $pg['password'],
        );

        $this->connection = false;

        //Connect:
        $this->make_connection();
    }

    function make_connection()
    {
        $connect_q = '';
        foreach($this->config as $key => $value) {
            $connect_q .= $key.'='.$value.' ';
        }

        if($link = pg_connect($connect_q)) {
            $this->connection = $link;

            #todo - more documentation regarding this:
            pg_query($this->connection, "SET CLIENT_ENCODING TO 'UTF8'");
            /*echo 'DB: PostGreSQL Connected'."\n";*/
        } else {
            exit( 'DB: PostGreSQL failed to connect'."\n" );
        }
    }

    function __destruct()
    {
        $this->end_connection();
    }

    public function end_connection()
    {
        if ($this->connection) {
            pg_close($this->connection);
            $this->connection = false;
            /*echo 'DB: PostGreSQL finished & closed connection.'."\n";*/
        }
    }

    public function connection_is_ok()
    {
        if($this->connection !== false) {
            $status = pg_ping($this->connection);
        } else {
            $status = false;
        }

        if ($status) {
            return true;
        } else {
            //just in case:
            $this->end_connection();

            //create new one:
            $this->make_connection();
            if ($this->connection !== false) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function runQuery($query)
    {
        if ($this->connection_is_ok()) {
            if (!pg_query($this->connection, $query)) {
                echo "\n\n".$query."\n\n";
                echo pg_last_error();
                exit("Connection not OK #Q\n");
            }

            return true;
        } else {
            return false;
        }
    }

    public function getResults($query)
    {
        $out = array();
        if ($this->connection_is_ok()) {
            if( !($handle = pg_query($this->connection, $query) )) {
                echo "\n\n".$query."\n\n";
                echo pg_last_error();
                exit("Connection not OK #R\n");
            } else {
                /*echo "Ran ".$query."\n";*/
                if(pg_num_rows($handle) > 0) {
                    $out = pg_fetch_all($handle);
                }
            }
        }

        return $out;
    }
}