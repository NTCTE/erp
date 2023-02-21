<?php

namespace App\Orchid\Layouts\System;

use Dadata\DadataClient;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Listener;
use Orchid\Support\Facades\Layout;

class AddressListener extends Listener
{
    /**
     * List of field names for which values will be joined with targets' upon trigger.
     *
     * @var string[]
     */
    protected $extraVars = [];

    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'address_promt'
    ];

    /**
     * What screen method should be called
     * as a source for an asynchronous request.
     *
     * The name of the method must
     * begin with the prefix "async"
     *
     * @var string
     */
    protected $asyncMethod = 'asyncAddressPromt';

    /**
     * @return Layout[]
     */
    protected function layouts(): iterable
    {
        if ($this -> query -> has('address_promt')) {
            $dadata = new DadataClient(env('DADATA_API_KEY'), env('DADATA_SECRET_KEY'));
            $options = [];
            foreach ($dadata -> suggest('address', $this -> query -> get('address_promt')) as $value)
                $options[] = $value['unrestricted_value'];
            return [
                Layout::rows([
                    Select::make('address_promt')
                        -> title('Адрес')
                        -> placeholder('Введите адрес...')
                        -> allowAdd()
                        -> options($options),
                ]),
            ];
        } else return [
            Layout::rows([
                Select::make('address_promt')
                    -> title('Адрес')
                    -> placeholder('Введите адрес...')
                    -> allowAdd()
                    -> options([])
            ]),
        ];
    }
}
