@extends('panel.layout.app')
@section('content')
    <div class="container mt-5">
        <h2>Published News</h2>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($publishedNews as $news)
                            <tr>
                                <td>{{ $news->title }}</td>
                                <td>{{ $news->author }}</td>
                                <td>{{ $news->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection 