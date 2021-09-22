# DigitalOceanAPI
Una pseudo librería creada para administrar droplets de DigitalOcean

### Detalles Adicionales
Todas las ejecuciones devuelven un array como resultado.
```json
{ "status": true, "content": "según la función y el estado, puede devolver un string o un array" }
```

---

### Cuenta
> Obtener información de la cuenta
```php
$do->viewAccountInfo()
```

### Droplets
> Listar todos los droplets
```php
$do->listDroplets()
```

> Recuperar la información de un droplet específico
```php
$do->viewDropletInfo('id_droplet')
```

> Eliminar un droplet
```php
$do->deleteDroplet('xxxxxxxx')
```

> Modificar estado del droplet. (Acciones disponibles: `power_on, power_off, shutdown, reboot`)
```php
$do->energyDroplet('id_droplet', 'accion')
```

> Crear nuevo droplet
```php
$do->createDroplet('nombre', 'region', 'plan', 'imagen_so')
```

> Cambiar plan de un droplet:
```php
$do->resizeDroplet('id_droplet', 'nuevo_plan', true|false)
```

### BackUps
> Listar backups creados de un droplet
```php
$do->listBackups('id_droplet')
```

> Activar/Desactivar backup automático (Acciones disponibles: `enable_backups` y `disable_backups`)
```php
$do->backupChangeStatus('id_droplet', 'accion')
```

### Snapshots
> Crear snapshot del droplet
```php
$do->createSnapshot('id_droplet');
```
> Listar snapshots del droplet
```php
$do->listSnapshots('id_droplet');
```
> Ver información de una snapshot
```php
$do->viewSnapshotInfo('id_droplet');
```
> Eliminar snapshot
```php
$do->deleteSnapshot('id_droplet');
```

