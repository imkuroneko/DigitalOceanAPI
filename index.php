<?php
require 'do.class.php';

$key = 'digital_ocean_api_key';

$DO = new DigitalOcean();
$digitalocean = $DO->connect($key);

$action =	$DO->poweroffDroplet('108048621');
var_dump($action['content']);


/* ===========================

	##	https://developers.digitalocean.com/documentation/changelog/api-v2/new-size-slugs-for-droplet-plan-changes/
	##	lista de planes + "slug" (parametro de medida de droplet)


[Cuenta]
	* ver información de la cuenta	=====	$DO->viewAccountInfo();


[Droplets]

	* crear droplet					=====	$DO->createDroplet('sv-api.test', 'nyc1', 's-1vcpu-2gb	', 'ubuntu-16-04-x64');
			1)	ID del droplet
			2)	ID Región
			3)	Slug del plan
			4)	ID de la imagen a utilizar

	* ver información del droplet	=====	$DO->viewDropletInfo('108048621');
			1)	ID del droplet

	* listar droplets				=====	$DO->listDroplets();

	* eliminar droplet				=====	$DO->deleteDroplet('108048621');
			1)	ID del droplet

	* modificar droplet (plan)		=====	$DO->resizeDroplet('108048621', 's-1vcpu-2gb', false);
			1)	ID del droplet
			2)	Slug del plan nuevo
			3)	Modificar SSD (dejar en 'false' si solo se necesita mas recursos y el espacio en SSD no importa o se cuente con blockstorage)

	* resetear pass del droplet		=====	$DO->resetDropletPassword('108048621');
			1)	ID del droplet

	* apagar droplet				=====	$DO->energyDroplet('108048621', 'reboot');
			1)	ID del droplet
			2)	Acción a realizar:	power_on, power_off, shutdown, reboot

[Snapshots]
	* crear snapshot del droplet	=====	$DO->createSnapshot('108048621');
			1)	ID del droplet
	* listar snapshot del droplet	=====	$DO->listSnapshots('108048621');
			1)	ID del droplet
	* ver información del droplet	=====	$DO->viewSnapshotInfo('108048621');
			1)	ID del droplet
	* eliminar snapshot				=====	$DO->deleteSnapshot('108048621');
			1)	ID del droplet


[Backups]
	* act/des backup automatico		=====	$DO->backupChangeStatus('108048621', disable_backups);
			1)	ID del droplet
			2)	Posibles acciones:	enable_backups, disable_backups 

	* listar backups del droplet	=====	$DO->listBackups('108048621');
			1)	ID del droplet


[Dominios]
	* crear dominio					=====	$DO->createDomain('dominioprueba', 'ip_droplet');
			1)	Dominio
			2)	IP del droplet

	* ver información del dominio	=====	$DO->viewDomainInfo('108048621');
			1) Dominio

	* listar dominios				=====	$DO->listDomains();

	* eliminar dominio				=====	$DO->deleteDomain('dominioprueba.com');
			1) Dominio

=========================== */
?>