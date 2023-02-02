<?php

namespace App\Orchid\Fields;

use Orchid\Screen\Field;

class CenteredCheckbox extends Field {
    protected $view = 'system.fields.centeredCheckbox';

    protected $attributes = [
        'class' => 'form-check-input',
        'type' => 'checkbox',
        'value' => false,
        'novalue' => 0,
        'yesvalue' => 1,
        'indeterminate' => false,
    ];

    protected $inlineAttributes = [
        'accesskey',
        'autofocus',
        'checked',
        'disabled',
        'form',
        'formaction',
        'formenctype',
        'formmethod',
        'formnovalidate',
        'formtarget',
        'name',
        'placeholder',
        'readonly',
        'required',
        'tabindex',
        'value',
        'type',
        'novalue',
        'yesvalue',
    ];
}