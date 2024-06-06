# Reproductor de sonidos

Proyecto de desarrollo web con enfoque en uso de bases de datos no relacionales para la Experiencia Educativa Bases de Datos no Convencionales de la [Facultad de Estadística e Informatica](https://www.uv.mx/fei/) de la [Universidad Veracruzana](https://www.uv.mx/) utilizando Redis y replicación

## Documentación

La documentación solicitada está en la carpeta `/documentacion`

## Configuración

Se presupone que la terminal se encuentra en el directorio del proyecto actual

### Configurar red

~~~ docker
docker network create redis-network
~~~

### Ejecutar servidores

Ejecutar el servidor maestro:

~~~ docker
docker run -d --name redis-maestro --network redis-network -p 6379:6379 -v $pwd/redis/redis-maestro.conf:/usr/local/etc/redis/redis.conf redis:alpine redis-server /usr/local/etc/redis/redis.conf
~~~

Ejecutar el servidor esclavo:

~~~ docker
docker run -d --name redis-esclavo --network redis-network -v $pwd/redis/redis-esclavo.conf:/usr/local/etc/redis/redis.conf redis:alpine redis-server /usr/local/etc/redis/redis.conf
~~~

### Verificar conexion

Verificar conexión del servidor maestro

~~~ docker
docker exec -it redis-maestro redis-cli -a redis_password1234 INFO replication
~~~

Debe salir algo parecido a esto:

~~~
# Replication
role:master
connected_slaves:1
slave0:ip=172.18.0.3,port=6379,state=online,offset=126,lag=1
master_failover_state:no-failover
master_replid:c6979a5df39822b601374c5e6d6e7fb9ce56a0e3
master_replid2:0000000000000000000000000000000000000000
master_repl_offset:126
second_repl_offset:-1
repl_backlog_active:1
repl_backlog_size:1048576
repl_backlog_first_byte_offset:1
repl_backlog_histlen:126
~~~

Verificar conexión del servidor esclavo

~~~ docker
docker exec -it redis-esclavo redis-cli INFO replication
~~~

Debe salir algo parecido a:

~~~
# Replication
role:slave
master_host:redis-maestro
master_port:6379
master_link_status:up
master_last_io_seconds_ago:4
master_sync_in_progress:0
slave_read_repl_offset:14
slave_repl_offset:14
slave_priority:100
slave_read_only:1
replica_announced:1
connected_slaves:0
~~~

### Configurar .env

Al archivo .env además de generar un APP_KEY

Comentar todo DB_[]

~~~
# DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
~~~

Asignar el driver de sesión, el queue de conexión, driver de caché a redis,

~~~
SESSION_DRIVER=redis
...
QUEUE_CONNECTION=redis

CACHE_DRIVER=redis
~~~

Y poner los datos necesarios para la conexión a la instancia del contenedor de redis

~~~
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=redis_password1234
REDIS_PORT=6379 
~~~
