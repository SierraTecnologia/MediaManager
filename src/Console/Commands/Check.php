<?php

namespace MediaManager\Console\Commands;

use Illuminate\Console\Command;
use MediaManager\Models\Video;

class Check extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:medias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repara os dados corrompidos das tabelas.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mediaService = new \App\Services\MediaService();
        $files = $mediaService->allFiles();

        $indiceVideo = 0;
        $videos = Video::where('url', null)->get();

        foreach ($videos as $video) {

            $video->name = $files[$indiceVideo]['name'];
            $video->url = $files[$indiceVideo]['path'];
            $video->path = $files[$indiceVideo]['relative_path'];
            $video->type = $files[$indiceVideo]['type'];
            $video->filename = $files[$indiceVideo]['filename'];
            $video->size = $files[$indiceVideo]['size'];
            $video->last_modified = $files[$indiceVideo]['last_modified'];

            $video->save();

            // Incrementa
            $indiceVideo = $indiceVideo + 1;
            if (!isset($files[$indiceVideo])) {
                $indiceVideo = 0;
            }
        }
    }

    protected function moreThenZero($array)
    {
        $count = 0;
        foreach ($array as $arrayResult) {
            $count += (int) $arrayResult['result'];
        }

        if ($count>0) {
            return true;
        }
        return false;
    }
}
