<?php
/**
 * Importar, exportar, converter e migrar imagens.
 */

namespace MediaManager\Services\Midia;

use CryptoService as CryptoServiceForFiles;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class ManipulatorService
{
    /**
     * Generate a name from the file path.
     *
     * @param string $file File path
     *
     * @return string
     */
    public static function importByStorage()
    {
        $directory = '';
        $connection = new Local();
        $files = $connection->allFiles($directory);

        foreach ($files as $file) {
            // @todo CAdastrar Imagens/Videos
            $content = $connection->get($file);
        }

        return true;
    }

    /**
     * Import imagens from instagram
     *
     * @return string
     */
    public static function importByServices()
    {
        return true; //@todo
    }
}
