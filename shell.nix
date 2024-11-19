let pkgs = import <nixpkgs> {};
in pkgs.mkShell {
  buildInputs = [ pkgs.mariadb ];
  shellHook = ''
    MYSQL_BASEDIR=${pkgs.mariadb}
    MYSQL_HOME=$PWD/mysql
    MYSQL_DATADIR=$MYSQL_HOME/data
    MYSQL_UNIX_PORT=$MYSQL_HOME/mysql.sock
    MYSQL_PID_FILE=$MYSQL_HOME/mysql.pid
    MYSQL_PORT=3309
    export MYSQL_UNIX_PORT MYSQL_HOME MYSQL_DATADIR MYSQL_PID_FILE MYSQL_PORT
    alias mysql='mysql -u root -P $MYSQL_PORT --socket=$MYSQL_UNIX_PORT'

    if [ ! -d "$MYSQL_HOME" ]; then
      mysql_install_db --auth-root-authentication-method=normal \
        --datadir=$MYSQL_DATADIR --basedir=$MYSQL_BASEDIR \
        --pid-file=$MYSQL_PID_FILE
    fi

    cat > $MYSQL_HOME/my.cnf <<EOF
    [mysqld]
    secure_file_priv=""
    port = $MYSQL_PORT
    bind-address = 0.0.0.0
    socket = $MYSQL_UNIX_PORT
    datadir = $MYSQL_DATADIR
    pid-file = $MYSQL_PID_FILE
    EOF

    mysqld --defaults-file=$MYSQL_HOME/my.cnf 2> $MYSQL_HOME/mysql.log &
    MYSQL_PID=$!

    finish()
    {
      mysqladmin -u root --socket=$MYSQL_UNIX_PORT shutdown
      kill $MYSQL_PID
      wait $MYSQL_PID
    }
    trap finish EXIT
  '';
}
