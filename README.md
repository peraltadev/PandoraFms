# Prueba técnica - Pandora

Este proyecto es la resolución completa de la prueba técnica de desarrollo, que consta de dos ejercicios: un script de decodificación de puntuaciones de juego y una mini aplicación PHP para gestionar reservas de citas.

## Explicación general

He organizado el proyecto en dos carpetas principales:

- `/ejercicio1`: Contiene el código del ejercicio de decodificación de puntuaciones.
- `/ejercicio2`: Contiene la aplicación web de reservas, desarrollada con PHP puro y MySQL, y dockerizada para facilitar su despliegue.

Ambos proyectos son independientes y se pueden ejecutar por separado siguiendo los pasos que detallo a continuación.

---

## Ejercicio 1 - Decodificación de puntuaciones

El primer ejercicio consiste en decodificar un archivo CSV que contiene puntuaciones de jugadores cifradas en diferentes sistemas de numeración personalizados.

### Cómo se ha resuelto

He creado un script PHP (`decode.php`) que lee el archivo `datos.csv`, analiza cada línea y, utilizando la segunda columna como sistema numérico personalizado, calcula la puntuación real de cada jugador. Finalmente, el script muestra por consola cada usuario con su puntuación decodificada.

### Cómo ejecutarlo

1. Abrir la terminal y navegar hasta la carpeta del ejercicio:

```bash
cd ejercicio1
```
2. Ejecutar el siguiente comando:
```bash
php decode.php
```
3. Resultado esperado:

Patata, 0
ElMejor, 3
BoLiTa, 23
Azul, 12
OtRo, 38
Manolita, 544
PiMiEnTo, 30

## Ejercicio 2 - Reserva de citas

## Requisitos previos

- Docker
- Docker Compose

---

## Cómo ejecutar el proyecto

1. Abrir la terminal y navegar hasta la carpeta del proyecto:

```bash
cd ejercicio2
```

2. Levantar el entorno Docker:

```bash
docker-compose up -d --build
```

Docker levantará los siguientes servicios:

Aplicación web: http://localhost:8080

phpMyAdmin: http://localhost:8081

Acceso a phpMyAdmin:

Usuario: root

Contraseña: admin1234



