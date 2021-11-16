# Prueba Quental
Prueba técnica para Quental

## Descripción:
La API se encuentra en la carpeta "api" de este respositorio y, la Web, se encuentra en la carpeta "app".

La API, debe de montarse sobre un servidor o puerto distinto al de la Web. En mi caso, la he levantado sobre el propio servidor web de Symfony.

La BBDD MySQL sobre la que trabaja la API, se puede lanzar a través de Symfony, aunque dejo también el esquema en el fichero "football_db.sql".

La API funciona a través de un token de autorización. Debería de configurarse en el fichero ".env.local" de la API y en el fichero "conf.php" de la Web.
