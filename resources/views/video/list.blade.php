@extends('layouts.app')

@section('content')
    <div id="app">
        <div class="d-flex flex-row">
            <div class="d-flex flex-fill justify-content-start pl-2">
                Все видео
            </div>
            <div class="d-flex flex-fill justify-content-end pr-2">
                <label for="uploadFile">
                    <span class="btn btn-primary">Загрузить</span>
                    <input id="uploadFile" type="file" hidden>
                </label>
            </div>
        </div>
    </div>
@endsection
