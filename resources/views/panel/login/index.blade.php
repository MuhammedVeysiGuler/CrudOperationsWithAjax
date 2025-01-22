@extends("panel.layout.app")
@section("content")
    <div id="forPdf">
        @include('panel.components.datatable', [
            'title' => 'Sign In List',
            'tableId' => 'signInTable',
            'fetchUrl' => route('sign_in.fetch'),
            'deleteUrl' => route('sign_in.delete'),
            'getUrl' => route('sign_in.get'),
            'modelName' => 'SignIn',
            'columns' => [
                ['data' => 'name', 'title' => 'Name'],
                ['data' => 'surname', 'title' => 'Surname'],
                ['data' => 'city', 'title' => 'City'],
                ['data' => 'email', 'title' => 'Email'],
                ['data' => 'actions', 'title' => 'Actions', 'orderable' => false]
            ]
        ])

        @include('panel.components.modals.sign_in')
    </div>
@endsection

@section('scripts')

@endsection
