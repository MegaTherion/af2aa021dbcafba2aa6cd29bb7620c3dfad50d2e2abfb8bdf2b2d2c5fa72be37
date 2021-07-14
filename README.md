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

El demo backend está disponible en: `https://resocentro.tuxnir.com`
El demo frontend está disponible en: `https://frontresocentro.tuxnir.com/dashboard`

Estructura del código realizado
-------------------------------

Rutas: `routes/api.php`

Controladores: `app/Http/Controllers`
`app/Http/Controllers/ImpresoraController.php` Controlador para impresoras
`app/Http/Controllers/ItemController.php` Controlador para items

Repositorios: `app/Repositories`
`app/Repositories/ImpresoraRepository.php` Repositorio para impresoras
`app/Repositories/ItemRepository.php` Repositorio para items

Modelos: `app/Models`
`app/Models/Impresora.php` Modelo para impresora
`app/Models/Item.php` Modelo para item

Mapeadores: `app/Mapper`
`app/Mapper/ImpresoraMapper.php` Tranforma objetos del modelo de impresora a DTO para el cliente.
`app/Mapper/ItemMapper.php` Tranforma objetos del modelo de item a DTO para el cliente.

Lógica de negocio: `app/BusinessLogic

`app/BusinessLogic/ItemRules.php` Reglas de negocio para el manejo de los items, cálculo de tiempo de producción, etc.

Migraciones: `database/migrations` Esta carpeta contiene las definiciones de la base de datos y las tablas utilizadas.

Algunas carpetas tienen archivos con código adicional como interfaces.

Probar con Postman
------------------

Importar a Postman el archivo `postman/8d374d3f-572f-4932-9faf-95893687fe67.zip`. Existen varios http requests para probar la funcionalidad. El objetivo del reto está en el request con nombre: `Get time production of an item`

Probar manualmente
------------------

Haciendo uso del demo, se puede hacer distintos http request:

### Calcular el tiempo total de producción de un item

Url: GET `https://resocentro.tuxnir.com/api/items/144/calculateTimeProduction`. Se observa el tiempo total en la variable response.data.time Recibe Id de item como parámetro en la url.

### Información de un item, con un nivel de profundidad de insumos

Url: GET `https://resocentro.tuxnir.com/api/items/145`. Recibe Id de item como parámetro en la url.

### Ejecutar producción

Url: POST `https://resocentro.tuxnir.com/api/items/169/executeProduction`. Crea un item que se haya agotado. Recibe Id de item como parámetro en la url.

### Información de una impresora

Url: GET `https://resocentro.tuxnir.com/api/impresoras/1`. Obtiene información de una impresora. Si se encuentra ocupada, muestra el item que está siendo fabricado. Si el tiempo de ocupación fue sobrepasado al momento de obtener información, el item que estaba siendo fabricado queda como TERMINADO incrementandose el stock del item en una unidad. Recibe Id de impresora como parámetro en la url.

### Forzar a terminar el trabajo de una impresora

Url: POST `https://resocentro.tuxnir.com/api/impresoras/1/terminarProduccion`. Fuerza la culminación de una impresión, liberando a la impresora e incrementando en una unidad el stock del item que estaba siendo fabricado. Recibe Id de impresora como parámetro en la url.

### Tomar cierta cantidad de stock de un item

Url: PUT `https://resocentro.tuxnir.com/api/impresoras/169/tomar` Recibe mediante PUT un parámetro `cantidad` indicando la cantidad de stock que disminuirá en un determinado item. Recibe Id de item como parámetro en la url.



