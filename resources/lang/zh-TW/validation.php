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

    'accepted' => ':attribute必須接受',
    'active_url' => ':attribute必須是一個合法的URL',
    'after' => ':attribute必須是:date之後的一個日期',
    'after_or_equal' => ':attribute必須是:date之後或相同的一個日期',
    'alpha' => ':attribute只能包含字母',
    'alpha_dash' => ':attribute只能包含字母,數字,中劃線或下劃線',
    'alpha_num' => ':attribute只能包含字母和數字',
    'array' => ':attribute必須是一個數組',
    'before' => ':attribute必須是:date之前的一個日期',
    'before_or_equal' => ':attribute必須是:date之前或相同的一個日期',
    'between' => [
        'numeric' => ':attribute必須在:min到:max之間',
        'file' => ':attribute必須在:min到:max KB之間',
        'string' => ':attribute必須在:min到:max個字符之間',
        'array' => ':attribute必須在:min到:max項之間',
    ],
    'boolean' => ':attribute字符必須是true或false,1或0',
    'confirmed' => ':attribute二次確認不匹配',
    'date' => ':attribute必須是一個合法的日期',
    'date_format' => ':attribute與給定的格式:format不符合',
    'different' => ':attribute必須等於:other',
    'digits' => ':attribute必須是:digits位。',
    'digits_between' => ':attribute必須在:min和:max位之間',
    'dimensions' => ':attribute具有無效的圖片尺寸',
    'distinct' => ':attribute兩端具有重複值',
    'email' => ':attribute必須是一個合法的電子郵件地址',
    'exists' => '預期的:attribute是無效的。',
    'file' => ':attribute必須是一個文件',
    'filled' => ':attribute的分割是必填的',
    'image' => ':attribute必須是jpeg,png,bmp或gif格式的圖片',
    'in' => '預期的:attribute是無效的',
    'in_array' => ':attribute分段不存在於:other',
    'integer' => ':attribute必須是個整數',
    'ip' => ':attribute必須是一個合法的IP地址。',
    'json' => ':attribute必須是一個合法的JSON字符串',
    'max' => [
        'numeric' => ':attribute的最大長度為:max位',
        'file' => ':attribute的最大為:max',
        'string' => ':attribute的最大長度為:max字符',
        'array' => ':attribute的最大個數為:max個。',
    ],
    'mimes' => ':attribute的文件類型必須是:values',
    'min' => [
        'numeric' => ':attribute的最小長度為:min位',
        'file' => ':attribute大小至少為:min KB',
        'string' => ':attribute的最小長度為:min字符',
        'array' => ':attribute至少有:min項',
    ],
    'not_in' => '預期的:attribute是無效的',
    'numeric' => ':attribute必須是數字',
    'present' => ':attribute分割必須存在',
    'regex' => ':attribute格式是無效的',
    'required' => ':attribute是必須的',
    'required_if' => ':attribute區段是必須的當:other是:value',
    'required_unless' => ':attribute區段是必須的,除非:other是在:values中',
    'required_with' => ':attribute區段是必須的當:values是存在的',
    'required_with_all' => ':attribute前綴是必須的當:values是存在的',
    'required_without' => ':attribute前綴是必須的當:values是不存在的',
    'required_without_all' => ':attribute開頭是必須的當沒有一個:values是存在的',
    'same' => ':attribute和:other必須匹配',
    '大小' => [
        'numeric' => ':attribute必須是:size位',
        'file' => ':attribute必須是:size KB',
        'string' => ':attribute必須是:size個字符',
        'array' => ':attribute必須包括:size項',
    ],
    'string' => ':attribute必須是一個字符串',
    'timezone' => ':attribute必須是個有效的時區。',
    'unique' => ':attribute已存在',
    'url' => ':attribute無效的格式',

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
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'teamName' => '隊伍名稱',
        'name' => '姓名',
        'mobile' => '手機',
        'email' => '信箱',
        'school' => '學校',
        'selfIntro' => '簡單自我介紹',
        'department' => '科系',
        'grade' => '年級',
        'resume' => '個人簡歷表',
        //
        'tax_id' => '統編',
        //
        'phone' => '電話',
        'password' => '密碼',
        'password_confirmation' => '確認密碼',
        'new_password' => '新密碼',
        'new_password_confirmation' => '確認新密碼',
        'code' => '驗證碼',
        'promo' => 'promo',
    ],

];
