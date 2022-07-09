<?php
/**
 * @todo fazer api desse modulo
 */

namespace MediaManager\Http\Api;

use MediaManager\Http\Controllers\Controller as BaseController;
use MediaManager\Models\Computer;
use MediaManager\Models\User;
use Illuminate\Support\Facades\Log;
use Response;

class Controller extends BaseController
{
    public ?Computer $_computer = null;

    // @todo Na versao 0.4.X foi retirado , verificar pq
    public function __construct()
    {
        parent::__construct();
        // $this->_computer = $this->getComputer();
    }

    /**
     * Tenta capturar um token para o computer via SERVER, POST, ou GET
     * Caso não ache ele usa o token padrão da passepague
     */
    public function getComputer($request = null)
    {
        if ($this->_computer) {
            return $this->_computer;
        }

        $this->_computer = Computer::getViaParamsToken($request);
        
        if (!empty($this->_computer)) {
            if (!is_null($this->_computer->blocked_at)) {
                $this->_computer->blocked_at = null;
                $this->_computer->is_active = 0;
                $this->_computer->save();
            }
        }

        
        return $this->_computer;
    }

    public function getDefaultPlaylist()
    {
        return '{"data":{"id":1,"name":"endotera defaout","is_active":true,"status":1,"videos":[{"id":16,"name":"endotera","description":null,"url":"https:\/\/media.endotera.com.br\/default.mp4","type":"video\/mp4","filename":"endotera","size":"1836047","last_modified":"1599077422","created_at":"2020-09-02 17:10:22","updated_at":"2020-09-02 17:10:22"}],"created_at":"2020-09-02 17:08:37","updated_at":"2020-09-02 19:10:52"},"success":true}';
    }
}
