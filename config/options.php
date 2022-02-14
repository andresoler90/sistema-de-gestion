<?php
return [
    "identification_type" => [
        "CC" => "Cédula de ciudadania",
        "CE" => "Cédula de Extranjeria",
        "NIT" => "Número de identificación (NIT)",
        "PP" => "Pasaporte",
        "RG" => "Registro General",
        "DNI" => "Documento de identificación",
        "DPI" => "Documento personal de identificación",
        "RFC" => "Registro Federal de Contribuyentes",
        "RUC" => "RUC",
        "RUT" => "RUT",
        "RUN" => "RUN",
        "CNPJ" => "CNPJ",
        "CPF" => "CPF",
        "CUIT" => "CUIT",
        "TAX ID" => "TAX ID",
        "OT" => "Otro",
    ],
    "register_type" => [
        "L" => "Liviano",
        "I" => "Integral",
    ],
    "register_assumed_by" => [
        "C" => "Cliente",
        "P" => "Proveedor",
    ],
    "currency" => [
        'COP' => 'COP',
        'PEN' => 'PEN',
        'MXN' => 'MXN',
        'CLP' => 'CLP',
        'BRL' => 'BRL',
        'USD' => 'USD',
    ],
    "provider_type" => [
        'N' => 'Nacional',
        'I' => 'Internacional',
    ],
    "priority" => [
        'CPC' => 'Crítica por Calidad',
        'CPM' => 'Crítica por Modificación',
        'CRI' => 'Crítica',
        'ALT' => 'Alta',
        'MED' => 'Media',
        'BAJ' => 'Baja',
    ],
    "status" => [
        'A' => 'Abierta',
        'C' => 'Cerrada',
    ],
    "management_status" => [
        'ATI' => 'A tiempo',
        'ATR' => 'Atrasada',
    ],
    "document_type" => [
        'OP' => 'Opcional',
        'OB' => 'Obligatorio',
    ],
    "management_type" => [
        'T' => 'Teléfono',
        'C' => 'Correo',
        'A' => 'Ambos',
    ],
    "management" => [
        'PRO' => 'Proveedor',
        'CLI' => 'Cliente',
        'PAR' => 'Par',
    ],
    "decision" => [
        'SN' => 'Seguimiento nuevo',
        // 'ES' => 'Enviar soporte al proveedor',
        'CS' => 'Cancelar solicitud',
    ],
    'configuration_alert' => [
        'alerts:client_resume' => 'Resumen de clientes'
    ],
    'periodicity' => [
        "none" => 'Ninguno',
        "daily" => 'Diario',
        "weekly" => 'Semanal',
        "monthly" => 'Mensual',
    ]
];
