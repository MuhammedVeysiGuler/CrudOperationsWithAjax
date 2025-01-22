@extends('panel.layout.app')
@section('content')
    @include('panel.components.datatable', [
        'title' => 'News List',
        'tableId' => 'newsTable',
        'fetchUrl' => route('news.fetch'),
        'deleteUrl' => route('news.delete'),
        'getUrl' => route('news.get'),
        'modelName' => 'News',
        'columns' => [
            ['data' => 'title', 'title' => 'Title'],
            ['data' => 'author', 'title' => 'Author'],
            ['data' => 'content', 'title' => 'Content'],
            ['data' => 'is_published', 'title' => 'Status'],
            ['data' => 'actions', 'title' => 'Actions', 'orderable' => false]
        ]
    ])

    @include('panel.components.modals.news')
@endsection
