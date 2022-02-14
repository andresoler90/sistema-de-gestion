<?php

namespace App\Http\Controllers\Libs;
use App\Models\RegisterSalesForce;
use Carbon\Carbon;
use GuzzleHttp\Client;

class SalesForce
{
    /**
     * Variables de autenticacion API
     * @var string Token publico
     * @var integer tipo de tocken
     * @var string Url de peticion
     * @var string Usuario
     * @var string Contraseña
     */
    public $access_token;
    public $token_type;
    public $instance_url;
    public $client;
    private $user = "santiago.suarez@parservicios.com";
    private $password = "Ssr17287712Kvf29CvGgVF0ie71RtrCNhBDb";

    public function __construct()
    {
        //Nos autenticamos para obtener el access token junto con el resto de la informacion
        $auth = $this->auth();
        $this->access_token = $auth->access_token;
        $this->token_type = $auth->token_type;
        $this->instance_url = $auth->instance_url;
        //Unica autenticación por instancia.
        $this->client = new Client([
            'base_uri' => $this->instance_url,
            'headers' => [
                'Authorization' => $this->token_type . ' ' . $this->access_token,
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    public function auth()
    {
        $params = http_build_query([
            "grant_type" => "password",
            "client_id" => "3MVG9l2zHsylwlpTEJ61c5nUTBzNEUB.yzmXR2V3MMoYsYYJ4TIGhIH4LPU1VxpqhoasF8TbYQLVQfy6dhP7y",
            "client_secret" => "DDB634C5B3E22373CB36883EB0073AA863C0FA3A8554CEDC60DDEA2DC89080CC",
            "username" => $this->user,
            "password" => $this->password,
        ]);

        $client = new Client([
            'base_uri' => 'https://login.salesforce.com',
        ]);
        $response = $client->post('services/oauth2/token?' . $params);

        return json_decode((string)$response->getBody());
    }

    /**
     * @param $data
     * @type Collection
     */
    public function create($data)
    {
        $verifyAccount =
            $this->findAccount($data->account['Identificaci_n__c'])->filter(function ($item) {
                return $item->Id;
            })->first();

        $verifyContact =
            $this->findContact($data->account['Correo__c'])->filter(function ($item) {
                return $item->Id;
            })->first();

        if ($data->cl1->cl1_id != 86 && $data->cl1->cl1_id != 46) {
            //VERIFICACION DE CUENTA
            if (empty($verifyAccount) && $verifyAccount == null) {
                $accountData = $this->prepareAccount($data);
                $account = $this->createAccount($accountData);
            } else {
                $account = $verifyAccount;
                $account->id = $account->Id;
                unset($account->Id);
            }

            $data->accountResult = $account;
            //VERIFICACION DE CONTACTO
            if (empty($verifyContact) && $verifyContact == null) {
                if (!empty($data->contact['firstName'])) {
                    $contactData = $this->prepareContact($data);
                    $contact = $this->createContact($contactData);
                } else {
                    $contact = null;
                }
            } else {
                $contact = $verifyContact;
                $contact->id = $contact->Id;
                unset($contact->Id);
            }

            $data->contactResult = $contact;

            //CREACION DE OPORTUNIDAD EN SALEFORCE
            $contactData = $this->prepareOpportunity($data);
            $opportunity = $this->createOpportunity($contactData);
            $data->opportunityResult = $opportunity;

            // Creacion de producto lista de precios
            if (count($data->priceList)) {
                foreach ($data->priceList as $list) {
                    $priceBook = $this->getPriceBook($list->salesforce_id); // Consulta lista existente
                    if ($priceBook->count()) {
                        $this->createProduct($priceBook, $opportunity->id); // Crea lista de precios de la oportunidad
                    }
                }
            }

            $this->saveLog($data);
        }

    }

    /**
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Crecion de cuenta en saleforce
     */
    public function createAccount(array $data)
    {
        $response = $this->client->post("services/data/v51.0/sobjects/Account", [
            "json" => $data
        ]);

        return json_decode((string)$response->getBody());
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Crecion de contacto en saleforce
     */
    public function createContact(array $data)
    {
        $response = $this->client->post("services/data/v51.0/sobjects/Contact", [
            "json" => $data
        ]);

        return json_decode((string)$response->getBody());
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Crecion de oportunidad en saleforce
     */
    public function createOpportunity(array $data)
    {
        $response = $this->client->post("services/data/v51.0/sobjects/Opportunity", [
            "json" => $data
        ]);

        return json_decode((string)$response->getBody());
    }


    public function findOpportunity($opportunity)
    {
        $query = "SELECT id,StageName FROM opportunity WHERE id='$opportunity'";
        $params = http_build_query([
            "q" => $query
        ]);
        $response = $this->client->get("services/data/v51.0/query/?" . $params);

        $json = json_decode((string)$response->getBody());
        return collect($json->records);
    }
    /**
     * @param $identification
     * @return \Illuminate\Support\Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Verificacion de existencia de cuenta por identificacion
     */
    public function findAccount($identification)
    {
        $query = "SELECT id FROM Account WHERE Identificaci_n__c='$identification'";
        $params = http_build_query([
            "q" => $query
        ]);
        $response = $this->client->get("services/data/v51.0/query/?" . $params);

        $json = json_decode((string)$response->getBody());
        return collect($json->records);
    }

    /**
     * @param $email
     * @return \Illuminate\Support\Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Verificacion de existencia de Contacto por identificacion
     */
    public function findContact($email)
    {
        $query = "SELECT id FROM Contact WHERE Email='$email'";
        $params = http_build_query([
            "q" => $query
        ]);
        $response = $this->client->get("services/data/v51.0/query/?" . $params);
        $json = json_decode((string)$response->getBody());
        return collect($json->records);
    }

    /**
     * @param $identification
     * @return \Illuminate\Support\Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Verificacion de existencia de cliente ancla por identificacion
     */
    public function findClientAncla($identification)
    {
        $query = "SELECT Id FROM Account WHERE Identificaci_n__c='$identification'";
        $params = http_build_query([
            "q" => $query
        ]);
        $response = $this->client->get("services/data/v51.0/query/?" . $params);

        $json = json_decode((string)$response->getBody());
        return collect($json->records);
    }

    // Se obtine informacion del contacto por id cuenta
    public function getContactByIdAccount($account_id): \Illuminate\Support\Collection
    {
        $query = "SELECT Id,AccountId,LastName,Name,FirstName,Salutation,Suffix,Phone,Fax,MobilePhone,Email,Title,Department,Pais__c,Ciudad__c,Direcci_n__c FROM Contact WHERE AccountId='$account_id'";

        $params = http_build_query([
            "q" => $query
        ]);

        $response = $this->client->get("services/data/v51.0/query/?" . $params);
        $json = json_decode((string)$response->getBody());
        return collect($json->records);

    }

    // Se obtine informacion de actividadedes de una oportunidad
    public function getActivitiesByIdOpportunity($opportunity): \Illuminate\Support\Collection
    {

        $query = "SELECT Id,WhatId,AccountId,WhoId,WhoCount,WhatCount,Subject,ActivityDate,Status,Priority,IsHighPriority,OwnerId,
            Description,CurrencyIsoCode,IsDeleted,IsClosed,CreatedDate,CreatedById,LastModifiedDate,LastModifiedById,SystemModstamp,
            IsArchived,CallDurationInSeconds,CallType,CallDisposition,CallObject,ReminderDateTime,IsReminderSet,RecurrenceActivityId,IsRecurrence,
            RecurrenceStartDateOnly,RecurrenceEndDateOnly,RecurrenceTimeZoneSidKey,RecurrenceType,RecurrenceInterval,RecurrenceDayOfWeekMask,
            RecurrenceDayOfMonth,RecurrenceInstance,RecurrenceMonthOfYear,RecurrenceRegeneratedType,TaskSubtype,CompletedDateTime,
            zoom_app__Additional_Emails__c,zoom_app__Customer_Duration_of_Meeting__c,zoom_app__Customer_Start_Time__c,zoom_app__Customer_Time_Zone__c,
            zoom_app__ICS_Sequence__c,zoom_app__Join_before_host__c,zoom_app__Make_it_Zoom_Meeting__c,zoom_app__Use_personal_Zoom_meeting_Id__c,
            zoom_app__Zoom_Call_Log__c,zoom_app__Zoom_Event__c
            FROM Task WHERE WhatId='$opportunity'";

        $params = http_build_query([
            "q" => $query
        ]);

        $response = $this->client->get("services/data/v51.0/query/?" . $params);
        $json = json_decode((string)$response->getBody());
        return collect($json->records);

    }

    /**
     * @param $metodo
     * @param $args
     * @return array|string[]
     * TRATAMIENTO DE LA DATA, DEPENDIENDO DEL METODO proccessAccount (CUENTA) - proccessContact (CONTACTO) -
     * proccessOpportunity (OPORTUNIDAD)
     */
    public function __call($metodo, $args)
    {
        $data = [];
        $args = current($args);

        if ($metodo == 'prepareAccount') {
            $data = [
                "Razon_social__c" => $args->account['Razon_social__c'],
                "Name" => $args->account['Name'],
                "Departamento__c" => self::maxLength($args->account['Departamento__c']),
                "Phone" => $args->account['Phone'],
                "Correo__c" => $args->account['Correo__c'],
                "Identificaci_n__c" => $args->account['Identificaci_n__c'],
                "Tipo_de_identificaci_n__c" => $args->account['Tipo_de_identificaci_n__c'],
                "Digito_de_verificaci_n_Nit__c" => $args->account['Digito_de_verificaci_n_Nit__c'],
                "Tipo_de_persona__c" => "Natural",
                "Tipo_de_proveedor__c" => $args->account['Tipo_de_proveedor__c'],
                "Pais__c" => self::maxLength($args->account['Pais__c']),
                "Ciudad__c" => self::maxLength($args->account['Ciudad__c']),
                "Direcci_n__c" => $args->account['Direcci_n__c'],
            ];
        }

        if ($metodo == 'prepareContact') {

            $data = [
                "salutation" => "Mr.",
                "firstName" => $args->contact['firstName'],
                "lastName" => "-",
                "Suffix" => strtoupper(substr($args->contact['Suffix'], 0, 2)),
                "Title" => "N/A",
                "Email" => $args->contact['Email'],
                "Department" => self::maxLength($args->contact['Department']),
                "Fax" => "",
                "Phone" => $args->contact['Phone'],
                "MobilePhone" => $args->contact['MobilePhone'],
                "Pais__c" => self::maxLength($args->contact['Pais__c']),
                "Ciudad__c" => self::maxLength($args->contact['Ciudad__c']),
                "Direcci_n__c" => $args->contact['Direcci_n__c'],
                "AccountId" => $args->accountResult->id,
            ];
        }


        if ($metodo == 'prepareOpportunity') {
            $country = self::maxLength($args->opportunity['Pais__c']);
            $client = $args->cl1;
            $clientAncla = $this->getClientAncla($client->cl1_id);
            $date = Carbon::now()->addMonths(2);
            $date = $date->toDate()->format('Y-m-d');
            $StageName = "Prospecto con oppty";

            if ($client->cl1_id == 86 || $client->cl1_id == 46) {
                $date = Carbon::now()->addDay(1)->format('Y-m-d');
                $StageName = "En ejecución y facturado. Oppty ganada";
            }

            $data = [
                "Name" => $args->opportunity['Name'],
                "StageName" => $StageName,
                "CloseDate" => $date,
                "RecordTypeId" => "0124W0000007breQAA",
                "ACCOUNTID" => $args->accountResult->id,
                "Description" => "SGPAR",
                "Origen_de_oportunidad__c" => "Solicitud_del_cliente",
                "Nombre_del_cliente_ancla__c" => $clientAncla, //SE DEBE A FUTURO AJUSTAR A CLIENTE ANCLA SALEFORCE (POR DEFECTO QUEDA ID PAR)
                "Pais__c" => $args->opportunity['Pais__c'],
                "Ciudad__c" => $args->opportunity['Ciudad__c'],
                "Direcci_n__c" => $args->opportunity['Direcci_n__c'],
                "Generador_de_pago__c" => "No",
                "Pricebook2Id" => "01s4W000006mDcgQAE", // Lista de precio Colombia
                "ContactId" => $args->contactResult->id,
            ];

            if (isset($country) && !empty($country)) {
                switch ($country) {
                    case "Chile":
                        $data = $data + ["Territory2Id" => "0MI4W000000PotRWAS"];
                        break;
                    case "Colombia":
                        $data = $data + ["Territory2Id" => "0MI4W000000PotCWAS"];
                        break;
                    case "Mexico":
                        $data = $data + ["Territory2Id" => "0MI4W000000PotMWAS"];
                        break;
                    case "Peru":
                        $data = $data + ["Territory2Id" => "0MI4W000000Pot7WAC"];
                        break;
                }
            }
        }

        return $data;
    }

    private static function maxLength($register)
    {
        if (strlen($register) > 20)
            $register = substr($register, 0, 20);
        return $register;
    }

    /**
     * @param $client
     * @return string
     * Asignacion de id de cliente ancla
     */
    public function getClientAncla($client)
    {
        $IdAncla = "0014W00002Kpta1QAB";

        $clientByReference = [

            "46" => "0014W00002HRybZQAT",
            "81" => "0014W00002HRyxJQAT",
            "137" => "0014W00002HS05mQAD",
            "9" => "0014W00002HS08BQAT",
            "96" => "0014W00002HRybFQAT",
            "111" => "0014W00002HRzuoQAD",
            "112" => "0014W00002HRz3OQAT",
            "90" => "0014W00002HRypoQAD",
            "116" => "0014W00002HS08W",
            //"88"=>"0014W00002HS08L",
            "82" => "0014W00002HRzxUQAT",
            "41" => "0014W00002HS08z",
            "19" => "0014W00002HS05SQAT",
            "152" => "0014W00002HS09d",
            "110" => "0014W00002HRzR9QAL",
            "29" => "0014W00002HS08f",
            "123" => "0014W00002HS08GQAT",
            "55" => "0014W00002HRyxKQAT",
            "17" => "0014W00002HS08k",
            "1" => "0014W00002HRyppQAD",
            "66" => "0014W00002HRzmGQAT",
            "78" => "0014W00002HS06QQAT",
            "154" => "0014W00002HS06VQAT",
            "24" => "0014W00002HRzuBQAT",
            "10" => "0014W00002HRz1HQAT",
            "51" => "0014W00002HS08p",
            "15" => "0014W00002HRzaGQAT",
            "42" => "0014W00002HS079QAD",
            "95" => "0014W00002HRznPQAT",
            "114" => "0014W00002HS082QAD",
            "85" => "0014W00002HRz7V",
            "101" => "0014W00002HS08QQAT",
            "138" => "0014W00002HS09J",
            "72" => "0014W00002HS08u",
            "79" => "0014W00002HRzMXQA1",
            "83" => "0014W00002HRzwbQAD",
            "80" => "0014W00002HRzK8QAL",
            "117" => "0014W00002HS08bQAD",
            "5" => "0014W00002HRzHYQA1",
            "77" => "0014W00002HS08VQAT",
            "88" => "0014W00002HS08LQAT",
            "94" => "0014W00002HRzwbQAD",
            "74" => "0014W00002HRzwbQAD",
            "167" => "0014W00002HRzQFQA1",
            "168" => "0014W00002HS08CQAT",
            "155" => "0014W00002HRz9RQAT",
            "129" => "0014W00002HRzUCQA1",
            "121" => "0014W00002HRzDpQAL",
            "127" => "0014W00002HRyr6QAD",
            "150" => "0014W00002HRzlnQAD",
            "75" => "0014W00002HS01BQAT",
            "48" => "0014W00002HRzZPQA1",
            "69" => "0014W00002HRzJPQA1",
            "108" => "0014W00002HRzqSQAT",
            "23" => "0014W00002HRzplQAD",
            "143" => "0014W00002HS01LQAT",
            "149" => "0014W00002HRzLbQAL",
            "56" => "0014W00002HRzIfQAL",
            "86" => "0014W00002HS08LQAT",
        ];

        if (array_key_exists($client, $clientByReference))
            $IdAncla = $clientByReference[$client];
        else
            $IdAncla = "0014W00002Kpta1QAB"; //Par servicios

        return $IdAncla;
    }

    public function saveLog($data)
    {
        $salesForce = new RegisterSalesForce();
        $salesForce->register_id = $data->register->id;
        $salesForce->status = 'P';
        $salesForce->account = $data->accountResult->id;
        $salesForce->contact = $data->contactResult->id;
        $salesForce->opportunity = $data->opportunityResult->id;
        $salesForce->save();
    }

    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Consulta un producto de la lista de precios
     */
    public function getPriceBook($id): \Illuminate\Support\Collection
    {
        try {
            $response = $this->client->get("services/data/v51.0/sobjects/PricebookEntry/$id");

            $json = json_decode((string)$response->getBody());
            return collect($json);

        } catch (\Exception $exception) {
            return collect();
        }

    }

    /**
     * @return \Illuminate\Support\Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Consulta toda la lista de precios colombia
     */
    public function getProductList(): \Illuminate\Support\Collection
    {
        $pricebook2Id = '01s4W000006mDcgQAE'; // Lista de precios colombia

//      Id,Name,Pricebook2Id,Product2Id,CurrencyIsoCode,UnitPrice,CreatedDate,CreatedById,LastModifiedDate,LastModifiedById,ProductCode
        $query = "SELECT Id,Name FROM PricebookEntry where Pricebook2Id ='$pricebook2Id' AND IsActive=true";

        $params = http_build_query([
            "q" => $query
        ]);
        $response = $this->client->get("services/data/v51.0/query/?" . $params);
        $json = json_decode((string)$response->getBody());
        return collect($json->records);
    }

    /**
     * @param $priceBook
     * @param $opportunity_id
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Creacion y asociacion de lista de precios de una oportunidad
     */
    public function createProduct($priceBook,$opportunity_id)
    {
        $data =
            [
                "OpportunityId" => $opportunity_id,
                "SortOrder" => null,
                "PricebookEntryId" => $priceBook['Id'],
                "Product2Id" => $priceBook['Product2Id'],
                "CurrencyIsoCode" => $priceBook['CurrencyIsoCode'],
                "Quantity" => 1.0,
                "Discount" => null,
                "TotalPrice" => $priceBook['UnitPrice'],
                "ServiceDate" => now()->format('Y-m-d'),
                "Description" => "Creación SGPAR",
            ];

        $response = $this->client->post("services/data/v51.0/sobjects/OpportunityLineItem", [
            "json" => $data
        ]);

        return json_decode((string)$response->getBody());
    }

    /**
     * @param $contactId
     * @return \Illuminate\Support\Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * Consulta funciones de contactos que se encuentran relacionados
     */
    public function getContactsRoleById($contactId): \Illuminate\Support\Collection
    {
        $query = "SELECT Id,OpportunityId,ContactId,Role,IsPrimary FROM OpportunityContactRole where ContactId ='$contactId'";

        $params = http_build_query([
            "q" => $query
        ]);
        $response = $this->client->get("services/data/v51.0/query/?" . $params);
        $json = json_decode((string)$response->getBody());
        return collect($json->records);
    }

    /**
     * @param $opportunity
     * @return \Illuminate\Support\Collection
     * @throws \GuzzleHttp\Exception\GuzzleException
     * consulta toda la informacion de la oportunidad
     */
    public function getOpportunityAll($opportunity)
    {

        $query = "SELECT  Id,IsDeleted,AccountId,RecordTypeId,Name,Description,StageName,Amount,Probability,CloseDate,Type,NextStep,LeadSource,IsClosed,IsWon,ForecastCategory,ForecastCategoryName,CurrencyIsoCode,
       CampaignId,HasOpportunityLineItem,Pricebook2Id,OwnerId,Territory2Id,IsExcludedFromTerritory2Filter,CreatedDate,CreatedById,LastModifiedDate,LastModifiedById,SystemModstamp,LastActivityDate,FiscalQuarter,FiscalYear,
       Fiscal,ContactId,LastViewedDate,LastReferencedDate,SyncedQuoteId,ContractId,HasOpenActivity,HasOverdueTask,LastAmountChangedHistoryId,LastCloseDateChangedHistoryId,Budget_Confirmed__c,Discovery_Completed__c,
       ROI_Analysis_Completed__c,Origen_de_oportunidad__c,Loss_Reason__c,Tipo_de_oportunidad__c,Prerregistro__c,OPC__c,Pais__c,Ciudad__c,Direcci_n__c,Estimado_proveedores_b_sicos__c,Motivo_de_no_interes__c,
       Origen_de_oportunidad_ancla__c,Nombre_del_cliente_ancla__c,Generador_de_pago__c,Admin_mi_proveedor__c,Estimado_proveedores_integrales__c,Estimado_proveedores_parciales__c,Descuento_aplicado__c,
       Enlace_a_documentos__c,ID_Cuenta__c,CuentaparaTemplate__c,OpportunityProductTemplate__c,Descripci_n__c,Fecha_de_Renovacion__c,Renovado__c FROM opportunity WHERE id='$opportunity'";
        $params = http_build_query([
            "q" => $query
        ]);
        $response = $this->client->get("services/data/v51.0/query/?" . $params);

        $json = json_decode((string)$response->getBody());
        return collect($json->records);
    }

}
