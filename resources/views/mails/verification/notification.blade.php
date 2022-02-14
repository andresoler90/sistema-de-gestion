@extends('mails.layout.layout')

@section('content')

    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <p><img width="100px"
                        src="{{ asset('assets/images/logo.png') }}"
                        alt="logo-sgpar">
                </p>
                <p>
                <h2>{{ $title }}<br></h2></p>
                <p style="text-align: justify; line-height: 15px">
                    <br>
                </p>
            </td>
        </tr>
        <tr style="text-align: left">
            <th>{{__('Documento')}}</th>
            <th>{{__('Observaciones')}}</th>
            <th>{{__('Estado')}}</th>
        </tr>
        @foreach($body as $verification)
            <tr style="text-align: left">
                <td>{{$verification->client_document->document->name}}</td>
                <td>{{$verification->outcome}}</td>
                <td>{{$verification->getSatisfyNameAttribute()}}</td>
            </tr>
        @endforeach
    </table>
    <br>
    <br>
    <div class="col-sm-3">
        Ingrese en el siguiente link para más información:
        <a class="btn btn-primary" href="#">Ingresar</a>
    </div>

@endsection
