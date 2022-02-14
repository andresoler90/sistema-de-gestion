@extends('mails.layout.layout')

@section('content')

    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <p><img width="100px"
                        src="{{ asset('assets/images/logo.png') }}" alt="logo-sgpar">
                        
                </p>
                <p>
                <h2>{{ $title }}<br></h2></p>
                <p style="text-align: justify; line-height: 15px">
                    {{ $body }}
                    <br>
                    <br>
                    <br>
                </p>
            </td>
        </tr>
    </table>

@endsection
