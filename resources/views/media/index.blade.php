
    <div class="page-content container-fluid">
        <?php /*@include('facilitador::alerts')*/?>
        <div class="row">
            <div class="col-md-12">

                <div class="admin-section-title">
                    <h3><i class="facilitador-images"></i> {{ __('pedreiro::generic.media') }}</h3>
                </div>
                <div class="clear"></div>
                <div id="filemanager">
                    <media-manager
                        base-path="{{ \Illuminate\Support\Facades\Config::get('master.media-manager.media.path', '/') }}"
                        :show-folders="{{ \Illuminate\Support\Facades\Config::get('master.media-manager.media.show_folders', true) ? 'true' : 'false' }}"
                        :allow-upload="{{ \Illuminate\Support\Facades\Config::get('master.media-manager.media.allow_upload', true) ? 'true' : 'false' }}"
                        :allow-move="{{ \Illuminate\Support\Facades\Config::get('master.media-manager.media.allow_move', true) ? 'true' : 'false' }}"
                        :allow-delete="{{ \Illuminate\Support\Facades\Config::get('master.media-manager.media.allow_delete', true) ? 'true' : 'false' }}"
                        :allow-create-folder="{{ \Illuminate\Support\Facades\Config::get('master.media-manager.media.allow_create_folder', true) ? 'true' : 'false' }}"
                        :allow-rename="{{ \Illuminate\Support\Facades\Config::get('master.media-manager.media.allow_rename', true) ? 'true' : 'false' }}"
                        :allow-crop="{{ \Illuminate\Support\Facades\Config::get('master.media-manager.media.allow_crop', true) ? 'true' : 'false' }}"
                        :details="{{ json_encode(['thumbnails' => \Illuminate\Support\Facades\Config::get('master.media-manager.media.thumbnails', []), 'watermark' => \Illuminate\Support\Facades\Config::get('master.media-manager.media.watermark', (object)[])]) }}"
                        ></media-manager>
                </div>
            </div><!-- .row -->
        </div><!-- .col-md-12 -->
    </div><!-- .page-content container-fluid -->

@include('media-manager::media.manager')
<script>
new Vue({
    el: '#filemanager'
});
</script>
