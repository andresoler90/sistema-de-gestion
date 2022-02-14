<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        truncate('documents');

        $documents =[
            'CÉDULA REPRESENTANTE LEGAL',
            'CÁMARA COMERCIO',
            'ACTA CONSTITUCIONES',
            'RUT',
            'CERTIFICACIÓN BANCARIA',
            'ARL',
            'ACCIDENTALIDAD',
            'SG-SST',
            'CERTIFICACIONES DE CALIDAD',
            'RESOLUCIONES',
            'CERTIFICADO PAGO PARAFISCALES',
            'FORMATOS CLIENTE',
            'DOCUMENTOS DEPENDIENTES DEL SERVICIO',
            'ESTADOS FINANCIEROS',
            'EXPERIENCIAS',
            'Formatos',
            'Emails',
        ];

        foreach($documents as $value) {
            $document = new Document();
            $document->name = $value;
            $document->created_users_id = 1;
            $document->save();
        }
    }
}
