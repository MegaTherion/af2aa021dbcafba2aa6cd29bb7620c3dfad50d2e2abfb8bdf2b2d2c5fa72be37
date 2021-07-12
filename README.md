RETO RESOCENTRO
===============

Backend con Laravel para Resocentro.

Instalación
-----------

* Clonar repo
* Instalar dependencias: composer install
* Copiar .env.example a .env
* Generar la key: php artisan key:generate
* Crear la base de datos e importar tablas y datos desde `docker-compose/mysql/init_db.sql`
* Voilá

Demo
----

El demo está disponible en: `https://resocentro.tuxnir.com`

Probar con Postman
------------------

Importar a Postman el archivo `postman/8d374d3f-572f-4932-9faf-95893687fe67.zip`. Existen varios http requests para probar la funcionalidad. El objetivo del reto está en el request con nombre: `Get time production of an item`

Probar manualmente
------------------

Haciendo uso del demo, se puede hacer distintos http request:

### Calcular el tiempo total de producción de un item

Url: GET `https://resocentro.tuxnir.com/api/items/144/calculateTimeProduction`. Se observa el tiempo total en la variable response.data.time

### Información de un item, con un nivel de profundidad de insumos

Url: GET `https://resocentro.tuxnir.com/api/items/145`.

### Ejecutar producción

Url: POST `https://resocentro.tuxnir.com/api/items/169/executeProduction`. Crea un item que se haya agotado.

### Información de una impresora

Url: GET `https://resocentro.tuxnir.com/api/impresoras/1`. Obtiene información de una impresora. Si se encuentra ocupada, muestra el item que está siendo fabricado. Si el tiempo de ocupación fue sobrepasado al momento de obtener información, el item que estaba siendo fabricado queda como TERMINADO incrementandose el stock del item en una unidad.

### Forzar a terminar el trabajo de una impresora

Url: POST `https://resocentro.tuxnir.com/api/impresoras/1/terminarProduccion`. Fuerza la culminación de una impresión, liberando a la impresora e incrementando en una unidad el stock del item que estaba siendo fabricado.





