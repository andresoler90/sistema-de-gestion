<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Libs\SalesForce;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Libs\MiProveedor;
use App\Http\Controllers\Libs\ProcessFlow;
use App\Models\MiProveedor\Cl1Clients;
use App\Models\MiProveedor\Contact;
use App\Models\MiProveedor\Pv15StatesRelations;
use App\Models\MiProveedor\Pv2Infoproviders;
use App\Models\PriceList;
use App\Models\Register;
use Illuminate\Http\Request;

class CommercialManagementController extends Controller
{
    //
    private $register;
    private $salesForce;
    private $miProveedor;
    private $provider;
    private $processFlow;
    private $client;
    private $data;

    public function __construct(Register $register)
    {
        $this->register = $register;
        $this->salesForce = new SalesForce();
        $this->processFlow = new ProcessFlow();
        $this->miProveedor = new MiProveedor();
        $this->provider = $this->miProveedor->getProvider($this->register->identification_number, $this->register->check_digit);
        $this->process();
        $this->salesForce->create($this->data);
    }

    public function process()
    {
        $data = collect([]);
        $this->client = $this->register->client;// Cliente asociado al registro
        $data->register = $this->register;
        $data->cl1 = Cl1Clients::where('cl1_id', $this->client->mp_clients_id)->first();
        $provider_type = ($this->register->countries_id == $this->register->client_country_id)? 'N':'I';
        $data->priceList = PriceList::where([
            ['clients_id', $this->register->clients_id],
            ['register_type', $this->register->register_type],
            ['provider_type', $provider_type]
        ])->whereNotNull('salesforce_id')->get();

        if ($this->provider) {
            $pv1 = $this->provider;
            $pv2 = Pv2Infoproviders::where('pv1_id', $this->provider->pv1_id)->first();

            $data->account = [
                'Razon_social__c' => $pv1->pv1_commercialName,
                'Name' => $pv1->pv1_commercialName,
                'Departamento__c' => $pv1->loc2->loc2_name,
                'Phone' => $pv2->pv2_phone1,
                'Correo__c' => $pv2->pv2_corporateEmail,
                'Identificaci_n__c' => $pv1->pv1_identification,
                'Tipo_de_identificaci_n__c' => $pv1->use1->use1_acronym,
                'Digito_de_verificaci_n_Nit__c' => $pv1->pv1_dv,
                'Tipo_de_proveedor__c' => ($pv1->loc3_id == 13) ? "Nacional" : "Extranjero",
                'Pais__c' => $pv1->loc3->loc3_shortName,
                'Ciudad__c' => $pv1->loc1->loc1_name,
                'Direcci_n__c' => $pv2->pv2_address,
            ];
            $data->opportunity =
                [
                    'Name' => $pv1->pv1_commercialName . " " . $data->cl1->cl1_name . " – SGPAR",
                    'Pais__c' => $pv1->loc3->loc3_shortName,
                    'Ciudad__c' => $pv1->loc1->loc1_name,
                    'Direcci_n__c' => $pv2->pv2_address,
                ];

        } else {
            $data->account =
                [
                    'Razon_social__c' => $this->register->business_name,
                    'Name' => $this->register->business_name,
                    'Departamento__c' => '-',
                    'Phone' => $this->register->telephone_contact,
                    'Correo__c' => $this->register->email_contact,
                    'Identificaci_n__c' => $this->register->identification_number,
                    'Tipo_de_identificaci_n__c' => $this->register->identification_type,
                    'Digito_de_verificaci_n_Nit__c' => $this->register->check_digit,
                    'Tipo_de_proveedor__c' => ($this->register->countries_id == 13) ? "Nacional" : "Extranjero",
                    'Pais__c' => $this->register->country->name,
                    'Ciudad__c' => '-',
                    'Direcci_n__c' => 'N/A',
                ];
            $data->opportunity =
                [
                    'Name' => $this->register->business_name . " " . $data->cl1->cl1_name . " – SGPAR",
                    'Pais__c' => $this->register->country->name,
                    'Ciudad__c' => '-',
                    'Direcci_n__c' => 'N/A',
                ];
        }

        $data->contact =
            [
                'firstName' => $this->register->name_contact,
                'Suffix' => strtoupper(substr($this->register->name_contact, 0, 2)),
                'Email' => $this->register->email_contact,
                'Department' => '-',
                'Phone' => $this->register->telephone_contact,
                'MobilePhone' => $this->register->telephone_contact,
                'Pais__c' => $this->register->country->name,
                'Ciudad__c' => '-',
                'Direcci_n__c' => 'N/A',
            ];

        $this->data = $data;

    }
}
