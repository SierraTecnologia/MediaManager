<?php

namespace MediaManager\Traits;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Log;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Pedreiro\Models\Base;

trait HasPhoto
{
    use InteractsWithMedia;

    public static function bootHasPhoto()                                                                                                                                                             
    {

        static::deleting(
            function (self $user) {
                optional($user->photos)->each(
                    function (Photo $photo) {
                        $photo->delete();
                    }
                );
            }
        );
    }
    
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->getMedia();
    }

    public function getProfileUrl($altura = false, $largura = false)
    {
        if ($avatar = $this->getMedia('avatars')->first()) {
            return $avatar->getUrl('thumb');
        }
        // @todo diferenciar homem de mulher
        return 'https://connectbuncombe.org/wp-content/uploads/2019/05/woman.png';
    }

}
