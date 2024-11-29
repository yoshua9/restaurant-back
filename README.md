# Proyecto de Restaurante

¡Bienvenido al Proyecto de Restaurante! Este proyecto está diseñado para ayudar a gestionar y optimizar las operaciones de un restaurante.

## Características

- Gestión de Escandallos

## Instalación

1. Clona el repositorio:
    ```bash
    git clone https://github.com/yourusername/restaurant.git
    ```
2. Navega al directorio del proyecto:
    ```bash
    cd restaurant
    ```
3. Copia el archivo `.env.example` a `.env`:
    ```bash
    cp .env.example .env
    ```
4. Instala las dependencias:
    ```bash
    composer install
    ```
5. Genera la clave de la aplicación:
    ```bash
    php artisan key:generate
    ```
6. Lanza las migraciones:
    ```bash
    php artisan migrate
    ```

## Uso

1. Inicia el servidor de desarrollo:
    ```bash
    php artisan serve
   ```
2. Abre tu navegador y navega a `http://127.0.0.1:8000`. deberia iniciar la aplicación basica de laravel

## API

### Introducir Receta

**Endpoint:** `POST /api/recetas`

**Ejemplo práctico:**

Petición #1:

```json
{
    "name": "Guacamole",
    "sale_price": 0,
    "ingredients": [
        {
            "name": "Aguacate",
            "cost": 2
        },
        {
            "name": "Cebolla",
            "cost": 1
        },
        {
            "name": "Tomate",
            "cost": 1
        },
        {
            "name": "Limón",
            "cost": 0.5
        },
        {
            "name": "Sal",
            "cost": 0.01
        }
    ]
}
```

Petición #2:

```json
{
    "name": "Nachos con guacamole",
    "sale_price": 10,
    "ingredients": [
        {
            "name": "Totopos",
            "cost": 1
        },
        {
            "name": "Guacamole",
            "cost": 4.51
        }
    ]
}
```

Petición #3:

```json
{
    "name": "Ron Cola",
    "sale_price": 8,
    "ingredients": [
        {
            "name": "Ron",
            "cost": 2
        },
        {
            "name": "Coca Cola",
            "cost": 0.5
        }
    ]
}
```

Respuesta Final tras la petición #3:

```json
{
    "Receta con mayor coste: ": "Nachos con guacamole 5.51",
    "Receta con menor coste: ": "Ron Cola 2.50",
    "Receta con mayor margen de beneficio: ": "Ron Cola 68.75%",
    "Receta con menor margen de beneficio: ": "Nachos con guacamole 44.90%"
}
```


Como recomendación se puede utilizar Postman para enviar los datos a la Api


## Testing

Para lanzar las pruebas, ejecuta el siguiente comando:

```bash
php artisan test
```
