<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'accepted'             => 'O :attribute deve ser aceito.',
    'active_url'           => 'O :attribute não é uma URL válida.',
    'after'                => 'O :attribute deve ser uma data maior que :date.',
    'alpha'                => 'O :attribute deve conter apenas letras.',
    'alpha_dash'           => 'O :attribute deve conter apenas letras, números, e - .',
    'alpha_num'            => 'O :attribute deve conter apenas letras e números.',
    'array'                => 'O :attribute deve ser um array.',
    'before'               => 'O :attribute deve ser uma data menor que :date.',
    'between'              => [
        'numeric' => 'O :attribute deve ser um valor entre :min e :max.',
        'file'    => 'O :attribute deve ser um valor entre :min e :max kilobytes.',
        'string'  => 'O :attribute deve possuir entre :min e :max caracteres.',
        'array'   => 'O :attribute deve ter entre :min e :max itens.',
    ],
    'boolean'              => 'O :attribute campo deve ser true or false.',
    'confirmed'            => 'O :attribute não foi confirmado.',
    'date'                 => 'A :attribute não é uma data válida.',
    'date_format'          => 'A :attribute está com formato inválido :format.',
    'different'            => 'Os valores de :attribute e :other devem ser diverentes.',
    'digits'               => 'O :attribute deve conter :digits digitos.',
    'digits_between'       => 'O :attribute deve conter entre :min e :max digitos.',
    'email'                => 'O :attribute deve ser um endereço de e-mail válido.',
    'filled'               => 'O :attribute é necessário.',
    'exists'               => 'O :attribute selecionado é inválido.',
    'image'                => 'A :attribute deve ser uma imagem.',
    'in'                   => 'O :attribute selecionado é inválido.',
    'integer'              => 'O :attribute deve ser um número inteiro.',
    'ip'                   => 'O :attribute informado deve ser um endereço de IP válido.',
    'max'                  => [
        'numeric' => 'O :attribute não pode ser maior que :max.',
        'file'    => 'O :attribute não pode ser maior que :max kilobytes.',
        'string'  => 'O :attribute não pode conter mais que :max caracteres.',
        'array'   => 'O :attribute não pode conter mais que :max itens.',
    ],
    'mimes'                => 'O :attribute deve conter uma das sequintes extensões: :values.',
    'min'                  => [
        'numeric' => 'O :attribute não pode ser menor que :min.',
        'file'    => 'O :attribute não pode ser menor que :min kilobytes.',
        'string'  => 'O :attribute não pode ter menos de :min caracteres.',
        'array'   => 'O :attribute não pode conter menos de :min itens.',
    ],
    'not_in'               => 'O :attribute selecionado é inválido.',
    'numeric'              => ':attribute deve ser numérico.',
    'regex'                => 'O :attribute possui um formato inválido.',
    'required'             => 'O :attribute não pode ser vazio.',
    'required_if'          => 'O :attribute é necessário quando :other for :value.',
    'required_with'        => 'O :attribute é necessário quando :values for selecionado.',
    'required_with_all'    => 'O :attribute é necessário quando :values forem selecionados.',
    'required_without'     => 'O :attribute é necessário quando :values não for selecionado.',
    'required_without_all' => 'O :attribute é necessário quando nenhum dos valores (:values) forem selecionados.',
    'same'                 => 'O :attribute e :other devem ser iguais.',
    'size'                 => [
        'numeric' => 'O :attribute deve ter o tamanho de :size.',
        'file'    => 'O :attribute deve ter o tamanho de :size kilobytes.',
        'string'  => 'O :attribute deve ter o tamanho de :size caracteres.',
        'array'   => 'O :attribute deve conter :size itens.',
    ],
    'string'               => 'O :attribute deve ser uma string.',
    'timezone'             => 'O :attribute deve ser um timezone válido.',
    'unique'               => 'O :attribute informado já está sendo utilizado.',
    'url'                  => 'O :attribute não é uma url válida.',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes' => [],
];