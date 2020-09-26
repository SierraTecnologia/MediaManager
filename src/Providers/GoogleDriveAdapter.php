<?php

namespace MediaManager\Providers;

class GoogleDriveAdapter extends \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter
{
    /**
     * @return \Google_Service_Drive
     */
    public function getService()
    {
            return $this->service;
    }
}
