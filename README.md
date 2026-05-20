# Biblioteca Fusalmo MVC

Sistema web de gestion de biblioteca en PHP (MVC) con MySQL.

## Requisitos para despliegue

- Repositorio en GitHub con este proyecto
- Servicio de base de datos MySQL en la nube (Railway, Render o similar)
- Servicio web con Docker (Render o Railway)

## Variables de entorno

El proyecto ya soporta estas variables:

- `DB_HOST`
- `DB_PORT`
- `DB_USER`
- `DB_PASS`
- `DB_NAME`
- `DB_CHARSET` (opcional, por defecto `utf8mb4`)
- `DATABASE_URL` (opcional, prioridad alta si existe)

Ejemplo de `DATABASE_URL`:

```env
mysql://usuario:clave@host:3306/biblioteca_fusalmo
```

## Opcion recomendada para la defensa (Render)

1. Sube este proyecto a GitHub.
2. Crea una base de datos MySQL administrada (en Railway o proveedor que prefieras).
3. Importa el esquema SQL desde `database/biblioteca_fusalmo_completo.sql`.
4. En Render crea un nuevo `Web Service` desde tu repo.
5. Render detectara el `Dockerfile` automaticamente.
6. En `Environment` agrega:
	- `DB_HOST`
	- `DB_PORT`
	- `DB_USER`
	- `DB_PASS`
	- `DB_NAME`
	o solo `DATABASE_URL`.
7. Despliega y abre la URL publica que te entregue Render.

## Opcion alternativa (Railway)

1. Crea un proyecto en Railway y conecta el repo.
2. Agrega servicio MySQL en Railway o usa uno externo.
3. Configura variables de entorno (igual que arriba).
4. Despliega el servicio web y prueba la URL publica.

## Verificacion antes de defender

1. Abre la URL publica en ventana privada y verifica que cargue el login.
2. Inicia sesion y navega al catalogo de libros.
3. Verifica alta/edicion/eliminacion de al menos un registro de prueba.
4. Comprueba que no haya errores de conexion en `test-conexion.php`.
5. Ten a mano la URL publica final y un usuario de prueba.

## Nota importante

Para evitar que el servicio quede caido durante la defensa, entra 10-15 minutos antes y valida que el proveedor no haya pausado la instancia por inactividad.
