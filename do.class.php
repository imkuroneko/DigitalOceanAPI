<?php

class DigitalOcean {

    function __construct() {}

    public function connect($key) {
        $this->apiKey = $key;
    }

    /**
     * Conexión a la API de DigitlOcean
     * 
     * @since version 1.0beta
     * @param string	$uri
     * @param array		$data
     * @param string	$method
     * @return object|array
     */
    public function callAPI($uri, $data = '', $method) {
        $api = 'https://api.digitalocean.com/v2/';
        $uri = $api . $uri;

        $headers = [ "Authorization: Bearer ".$this->apiKey, "Content-Type: application/json" ];
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if(is_array($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $result = json_decode(curl_exec($ch));
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($method === 'DELETE') {
            return $httpcode;
        }
        return $result;
    }

    /**
     * Obtener información de la cuenta
     * 
     * @since version 1.0beta
     * @return array
     */
    public function viewAccountInfo() {
        $exec = $this->callAPI('account', '', 'GET');

        if(array_key_exists('account', $exec)) {
            $info = $exec->account;
            return array(
                'status' => true,
                'content' => array(
                    'user_id' => $info->uuid,
                    'email' => $info->email,
                    'status_account' => $info->status,
                    'status_msg' => $info->status_message,
                    'droplet_limit' => $info->droplet_limit,
                    'floating_ip_limit' => $info->floating_ip_limit
                )
            );
        } else {
            return array( 'status' => false, 'content' => $exec->message);
        }
    }

    /**
     * Crear un droplet
     * 
     * @since version 1.0beta
     * @param string	$name
     * @param array		$region
     * @param string	$size
     * @param string	$image
     * @param string	$ssh_keys
     * @return array
     */
    public function createDroplet($name, $region, $size, $image, $ssh_keys = null) {
        $exec = $this->callAPI(
            'droplets',
            array(
                'name' => $name,
                'region' => $region,
                'size' => $size,
                'image' => $image,
                'ssh_keys' => $ssh_keys,
                'backups' => true,
                'ipv6' => true,
            ),
            'POST'
        );

        if(array_key_exists('droplet', $exec)) {
            return array( 'status' => true, 'content' => $exec->droplet);
        } else {
            return array( 'status' => false, 'content' => $exec->message);
        }
    }

    /**
     * Listar todos los droplets creados
     * 
     * @since version	1.0beta
     * @param int		$per_page
     * @return array
     */
    public function listDroplets($per_page = '100') {
        $exec = $this->callAPI(
            'droplets/?page=1&per_page='.$per_page,
            '',
            'GET'
        );

        if(array_key_exists('droplets', $exec)) {
            return array( 'status' => true, 'content' => $exec->droplets);
        } else {
            return array( 'status' => false, 'content' => $exec->message);
        }
    }

    /**
     * Obtener información del droplet
     * 
     * @since version	1.0beta
     * @param int		$droplet_id
     * @return array
     */
    public function viewDropletInfo($droplet_id) {
        $exec = $this->callAPI(
            'droplets/'.$droplet_id,
            '',
            'GET'
        );

        if(array_key_exists('droplet', $exec)) {
            return array( 'status' => true, 'content' => $exec->droplet);
        } else {
            return array( 'status' => false, 'content' => $exec->message);
        }
    }

    /**
     * Obtener nombre del droplet
     * 
     * @since version	1.0beta
     * @param int		$droplet_id
     * @return string
     */
    public function getDropletName($droplet_id) {
        $exec = $this->callAPI(
            'droplets/'.$droplet_id,
            '',
            'GET'
        );

        if(array_key_exists('droplet', $exec)) {
            return $exec->droplet->name;
        }
    }

    /**
     * Eliminar droplet
     * 
     * @since version	1.0beta
     * @param int		$droplet_id
     * @return array
     */
    public function deleteDroplet($droplet_id) {
        $exec = $this->callAPI( 'droplets/'.$droplet_id, '', 'DELETE' );

        if($exec == '204') {
            return array( 'status' => true, 'content' => 'Droplet eliminado');
        } elseif($exec == '404') {
            return array( 'status' => false, 'content' => 'No se ha encontrado el droplet con el ID proveido');
        }
    }

    /**
     * Crear snapshot del droplet
     * 
     * @since version	1.0beta
     * @param int		$droplet_id
     * @return array
     */
    public function createSnapshot($droplet_id) {
        $exec = $this->callAPI(
            'droplets/'.$droplet_id.'/actions',
            array(
                'type' => 'snapshot',
                'name' => date('Y-m-d H:i').' - '.$this->getDropletName($droplet_id)
            ),
            'POST'
        );

        if(array_key_exists('action', $exec)) {
            return array( 'status' => true, 'content' => $exec->action);
        } else {
            return array( 'status' => false, 'content' => $exec->message);
        }
    }

    /**
     * Listar snapshots existentes del droplet indicado
     * 
     * @since version	1.0beta
     * @param int		$droplet_id
     * @param int		$per_page
     * @return array
     */
    public function listSnapshots($droplet_id, $per_page = 100) {
        $exec = $this->callAPI(
            'droplets/'.$droplet_id.'/snapshots/?page=1&per_page='.$per_page,
            '',
            'GET'
        );

        if(array_key_exists('snapshots', $exec)) {
            return array( 'status' => true, 'content' => $exec->snapshots);
        } else {
            return array( 'status' => false, 'content' => $exec->message);
        }
    }

    /**
     * Obtener información de un snapshot
     * 
     * @since version	1.0beta
     * @param int		$snapshot_id
     * @return array
     */
    public function viewSnapshotInfo($snapshot_id) {
        $exec = $this->callAPI(
            'snapshots/'.$snapshot_id,
            '',
            'GET'
        );

        if(array_key_exists('snapshot', $exec)) {
            return array( 'status' => true, 'content' => $exec->snapshot);
        } else {
            return array( 'status' => false, 'content' => $exec->message);
        }
    }

    /**
     * Eliminar snapshot
     * 
     * @since version	1.0beta
     * @param int		$snapshot_id
     * @return array
     */
    public function deleteSnapshot($snapshot_id) {
        $exec = $this->callAPI(
            'snapshots/'.$snapshot_id,
            '',
            'DELETE'
        );

        if($exec == '204') {
            return array( 'status' => true, 'content' => 'Snapshot eliminado');
        } elseif($exec == '404') {
            return array( 'status' => false, 'content' => 'No se ha encontrado el snapshot con el ID proveido');
        }
    }

    /**
     * Listar backups existentes del droplet indicado
     * 
     * @since version	1.0beta
     * @param int		$droplet_id
     * @return array
     */
    public function listBackups($droplet_id) {
        $exec = $this->callAPI(
            'droplets/'.$droplet_id.'/backups',
            '',
            'GET'
        );

        if(array_key_exists('backups', $exec)) {
            return array( 'status' => true, 'content' => $exec->backups);
        } else {
            return array( 'status' => false, 'content' => $exec->message);
        }
    }

    /**
     * Modificar plan del droplet
     * 
     * @since version	1.0beta
     * @param int		$droplet_id
     * @param string	$new_size
     * @param boolean	$fixed_ssd
     * @return array
     */
    public function resizeDroplet($droplet_id, $new_size, $fixed_ssd = false) {
        $exec = $this->callAPI(
            'droplets/'.$droplet_id.'/actions',
            array(
                'type' => 'resize',
                'disk' => $fixed_ssd,
                'size' => $new_size
            ),
            'POST'
        );

        if(array_key_exists('action', $exec)) {
            return array( 'status' => true, 'content' => $exec->action);
        } else {
            return array( 'status' => false, 'content' => $exec->message);
        }
    }

    /**
     * Administrar actividades energeticas (?) en el droplet
     * 
     * @since version	1.0beta
     * @param int		$droplet_id
     * @param string	$action
     * @return array
     */
    public function energyDroplet($droplet_id, $action) {
        $exec = $this->callAPI('droplets/'.$droplet_id.'/actions', [ 'type' => $action ], 'POST');

        if(array_key_exists('action', $exec)) {
            return array( 'status' => true, 'content' => $exec->action);
        } else {
            return array( 'status' => false, 'content' => $exec->message);
        }
    }

    /**
     * Cambiar estado de la generación automática de backups
     * 
     * @since version	1.0beta
     * @param int		$droplet_id
     * @param string	$action
     * @return array
     */
    public function backupChangeStatus($droplet_id, $action) {
        $exec = $this->callAPI('droplets/'.$droplet_id.'/actions', [ 'type' => $action ], 'POST');

        if(array_key_exists('action', $exec)) {
            return array( 'status' => true, 'content' => $exec->action);
        } else {
            return array( 'status' => false, 'content' => $exec->message);
        }
    }

}
