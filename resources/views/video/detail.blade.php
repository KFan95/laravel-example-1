@extends('layouts.app')

@section('content')
    <div id="app">
        <div class="d-flex flex-row">
            <div class="d-flex flex-fill justify-content-start pl-2">
                <a href="{{ route('video.detail', $video->id) }}">Вернуться в список</a>
            </div>
        </div>
    </div>
@endsection
