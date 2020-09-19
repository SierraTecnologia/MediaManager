@section('vlc-player')
    <vue-core-video-player :controls="true" :src="currentMovie.url" @canplay="canplayFunc" @ended="endedFunc" ref="videoplayer" autoplay></vue-core-video-player>
@endsection
<script>
    // Similarly, you can also introduce the plugin resource pack you want to use within the component
    // import 'some-videojs-plugin'
    Vue.component('vlc-player', {
        template: `@yield('vlc-player')`,
        props: {
            selectMovie: {
                type: Number,
                default: 0
            },
            movieList: {
                type: Array,
                default: function() {
                    return {!! \App\Models\Computer::getVideosViaParamsToken() !!};
                }
            },
        },
        data: function() {
            return {
                title: '',
                // movieList: ,
                // recommendList: [DATA[2], DATA[4], DATA[1], DATA[0]],
                currentMovie: this.movieList[this.selectMovie]
            }
        },
        methods: {
            canplayFunc () {
                this.$refs.videoplayer.play();
                this.$refs.videoplayer.enterFullscreen();
                console.log('Plaindo');
                // this.play
            },
            endedFunc () {
                this.selectMovie = this.selectMovie+1;
                this.currentMovie = this.movieList[this.selectMovie];
                this.$refs.videoplayer.play();
                this.$refs.videoplayer.enterFullscreen();
            },
        }
    });
</script>