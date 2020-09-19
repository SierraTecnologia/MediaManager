@extends('layouts.app')

@section('pageTitle') Dashboard @stop

@section('content')


    <style type="text/css">
      .image {
        float: left;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        border: 1px solid #ebebeb;
        margin: 5px;
      }
    </style>


    <div id="galleryfile">
    <gallery 
    :images="images" 
    :index="index" 
    :options="{youTubeVideoIdProperty: 'youtube', youTubePlayerVars: undefined, youTubeClickToPlay: true}"
    @close="index = null"
    ></gallery>
    <div
        class="image"
        v-for="image, imageIndex in images"
        @click="index = imageIndex"
        :style="{ backgroundImage: 'url(' + image.poster + ')', width: '300px', height: '200px' }"
    ></div>
    </div>
@stop

@section('javascript')

<script type="text/javascript" src="{{ route('media-manager.assets').'?path='.urlencode('js/app.js') }}"></script>


<script>
new Vue({
  el: '#galleryfile',
  data: function () {
    return {
      images: {!! json_encode($results->map(function($result) {
        return [
          'title' => $result->getFullUrl(),
          'href' => $result->getFullUrl(),
          'type' => 'image/jpeg',
          'poster' => $result->getFullUrl()
        ];
      })) !!},
      // [
      //   {
      //     title: 'A YouYube video',
      //     href: 'https://www.youtube.com/watch?v=hNdlUHBJDKs',
      //     type: 'text/html',
      //     youtube: 'hNdlUHBJDKs',
      //     poster: 'https://img.youtube.com/vi/hNdlUHBJDKs/maxresdefault.jpg'
      //   },
      //   {
      //     title: 'A YouYube video 2',
      //     href: 'https://www.youtube.com/watch?v=s5iUsaPPtnk',
      //     type: 'text/html',
      //     youtube: 's5iUsaPPtnk',
      //     poster: 'https://img.youtube.com/vi/s5iUsaPPtnk/maxresdefault.jpg'
      //   },
      //   {
      //     title: 'Image',
      //     href: 'https://dummyimage.com/1600/ffffff/000000',
      //     type: 'image/jpeg',
      //     poster: 'https://dummyimage.com/350/ffffff/000000'
      // 	}
      // ],
      index: null
    };
  },

  components: {
    'gallery': VueGallery
  }
});
</script>
@endsection
