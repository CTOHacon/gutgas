<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such as
    | the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Câmpul :attribute trebuie acceptat.',
    'accepted_if' => 'Câmpul :attribute trebuie acceptat când :other are valoarea :value.',
    'active_url' => 'Câmpul :attribute trebuie să fie un URL valid.',
    'after' => 'Câmpul :attribute trebuie să fie o dată după :date.',
    'after_or_equal' => 'Câmpul :attribute trebuie să fie o dată după sau egală cu :date.',
    'alpha' => 'Câmpul :attribute poate conține doar litere.',
    'alpha_dash' => 'Câmpul :attribute poate conține doar litere, cifre, cratime și liniuțe de subliniere.',
    'alpha_num' => 'Câmpul :attribute poate conține doar litere și cifre.',
    'array' => 'Câmpul :attribute trebuie să fie un tablou.',
    'ascii' => 'Câmpul :attribute poate conține doar caractere ASCII și simboluri.',
    'before' => 'Câmpul :attribute trebuie să fie o dată înainte de :date.',
    'before_or_equal' => 'Câmpul :attribute trebuie să fie o dată înainte sau egală cu :date.',
    'between' => [
        'array' => 'Câmpul :attribute trebuie să conțină între :min și :max elemente.',
        'file' => 'Câmpul :attribute trebuie să aibă între :min și :max kiloocteți.',
        'numeric' => 'Câmpul :attribute trebuie să fie între :min și :max.',
        'string' => 'Câmpul :attribute trebuie să aibă între :min și :max caractere.',
    ],
    'boolean' => 'Câmpul :attribute trebuie să aibă valoarea true sau false.',
    'confirmed' => 'Câmpul :attribute nu coincide cu confirmarea.',
    'current_password' => 'Parola este incorectă.',
    'date' => 'Câmpul :attribute trebuie să fie o dată validă.',
    'date_equals' => 'Câmpul :attribute trebuie să fie o dată egală cu :date.',
    'date_format' => 'Câmpul :attribute nu corespunde formatului :format.',
    'decimal' => 'Câmpul :attribute trebuie să aibă :decimal zecimale.',
    'declined' => 'Câmpul :attribute trebuie refuzat.',
    'declined_if' => 'Câmpul :attribute trebuie refuzat când :other are valoarea :value.',
    'different' => 'Câmpurile :attribute și :other trebuie să fie diferite.',
    'digits' => 'Câmpul :attribute trebuie să aibă :digits cifre.',
    'digits_between' => 'Câmpul :attribute trebuie să aibă între :min și :max cifre.',
    'dimensions' => 'Câmpul :attribute are dimensiuni de imagine nevalide.',
    'distinct' => 'Câmpul :attribute are o valoare duplicată.',
    'doesnt_end_with' => 'Câmpul :attribute nu poate să se termine cu niciuna dintre următoarele valori: :values.',
    'doesnt_start_with' => 'Câmpul :attribute nu poate începe cu niciuna dintre următoarele valori: :values.',
    'email' => 'Câmpul :attribute trebuie să fie o adresă de email validă.',
    'ends_with' => 'Câmpul :attribute trebuie să se termine cu una dintre următoarele valori: :values.',
    'enum' => 'Valoarea selectată pentru :attribute nu este validă.',
    'exists' => 'Valoarea selectată pentru :attribute nu este validă.',
    'file' => 'Câmpul :attribute trebuie să fie un fișier.',
    'filled' => 'Câmpul :attribute trebuie să aibă o valoare.',
    'gt' => [
        'array' => 'Câmpul :attribute trebuie să conțină mai mult de :value elemente.',
        'file' => 'Câmpul :attribute trebuie să fie mai mare de :value kiloocteți.',
        'numeric' => 'Câmpul :attribute trebuie să fie mai mare de :value.',
        'string' => 'Câmpul :attribute trebuie să conțină mai mult de :value caractere.',
    ],
    'gte' => [
        'array' => 'Câmpul :attribute trebuie să conțină :value elemente sau mai multe.',
        'file' => 'Câmpul :attribute trebuie să fie mai mare sau egal cu :value kiloocteți.',
        'numeric' => 'Câmpul :attribute trebuie să fie mai mare sau egal cu :value.',
        'string' => 'Câmpul :attribute trebuie să conțină cel puțin :value caractere.',
    ],
    'image' => 'Câmpul :attribute trebuie să fie o imagine.',
    'in' => 'Valoarea selectată pentru :attribute nu este validă.',
    'in_array' => 'Câmpul :attribute nu există în :other.',
    'integer' => 'Câmpul :attribute trebuie să fie un număr întreg.',
    'ip' => 'Câmpul :attribute trebuie să fie o adresă IP validă.',
    'ipv4' => 'Câmpul :attribute trebuie să fie o adresă IPv4 validă.',
    'ipv6' => 'Câmpul :attribute trebuie să fie o adresă IPv6 validă.',
    'json' => 'Câmpul :attribute trebuie să fie un șir JSON valid.',
    'lowercase' => 'Câmpul :attribute trebuie să fie scris cu litere mici.',
    'lt' => [
        'array' => 'Câmpul :attribute trebuie să conțină mai puțin de :value elemente.',
        'file' => 'Câmpul :attribute trebuie să fie mai mic de :value kiloocteți.',
        'numeric' => 'Câmpul :attribute trebuie să fie mai mic de :value.',
        'string' => 'Câmpul :attribute trebuie să conțină mai puțin de :value caractere.',
    ],
    'lte' => [
        'array' => 'Câmpul :attribute nu trebuie să conțină mai mult de :value elemente.',
        'file' => 'Câmpul :attribute trebuie să fie mai mic sau egal cu :value kiloocteți.',
        'numeric' => 'Câmpul :attribute trebuie să fie mai mic sau egal cu :value.',
        'string' => 'Câmpul :attribute trebuie să conțină cel mult :value caractere.',
    ],
    'mac_address' => 'Câmpul :attribute trebuie să fie o adresă MAC validă.',
    'max' => [
        'array' => 'Câmpul :attribute nu poate conține mai mult de :max elemente.',
        'file' => 'Câmpul :attribute nu poate fi mai mare de :max kiloocteți.',
        'numeric' => 'Câmpul :attribute nu poate fi mai mare de :max.',
        'string' => 'Câmpul :attribute nu poate fi mai lung de :max caractere.',
    ],
    'max_digits' => 'Câmpul :attribute nu poate avea mai mult de :max cifre.',
    'mimes' => 'Câmpul :attribute trebuie să fie un fișier de tipul: :values.',
    'mimetypes' => 'Câmpul :attribute trebuie să fie un fișier de tipul: :values.',
    'min' => [
        'array' => 'Câmpul :attribute trebuie să conțină cel puțin :min elemente.',
        'file' => 'Câmpul :attribute trebuie să aibă cel puțin :min kiloocteți.',
        'numeric' => 'Câmpul :attribute trebuie să fie cel puțin :min.',
        'string' => 'Câmpul :attribute trebuie să aibă cel puțin :min caractere.',
    ],
    'min_digits' => 'Câmpul :attribute trebuie să aibă cel puțin :min cifre.',
    'missing' => 'Câmpul :attribute trebuie să lipsească.',
    'missing_if' => 'Câmpul :attribute trebuie să lipsească atunci când :other are valoarea :value.',
    'missing_unless' => 'Câmpul :attribute trebuie să lipsească, dacă :other nu are valoarea :value.',
    'missing_with' => 'Câmpul :attribute trebuie să lipsească atunci când :values sunt prezente.',
    'missing_with_all' => 'Câmpul :attribute trebuie să lipsească atunci când :values sunt prezente.',
    'multiple_of' => 'Câmpul :attribute trebuie să fie multiplu de :value.',
    'not_in' => 'Valoarea selectată pentru :attribute nu este validă.',
    'not_regex' => 'Formatul câmpului :attribute nu este valid.',
    'numeric' => 'Câmpul :attribute trebuie să fie un număr.',
    'password' => [
        'letters' => 'Câmpul :attribute trebuie să conțină cel puțin o literă.',
        'mixed' => 'Câmpul :attribute trebuie să conțină cel puțin o literă mare și o literă mică.',
        'numbers' => 'Câmpul :attribute trebuie să conțină cel puțin o cifră.',
        'symbols' => 'Câmpul :attribute trebuie să conțină cel puțin un simbol.',
        'uncompromised' => 'Acest :attribute a apărut într-o scurgere de date. Vă rugăm să alegeți alt :attribute.',
    ],
    'present' => 'Câmpul :attribute trebuie să fie prezent.',
    'prohibited' => 'Câmpul :attribute este interzis.',
    'prohibited_if' => 'Câmpul :attribute este interzis când :other are valoarea :value.',
    'prohibited_unless' => 'Câmpul :attribute este interzis, dacă :other nu se află în :values.',
    'prohibits' => 'Câmpul :attribute împiedică :other să fie prezent.',
    'regex' => 'Formatul câmpului :attribute nu este valid.',
    'required' => 'Câmpul :attribute este obligatoriu.',
    'required_array_keys' => 'Câmpul :attribute trebuie să conțină înregistrări pentru: :values.',
    'required_if' => 'Câmpul :attribute este obligatoriu când :other are valoarea :value.',
    'required_if_accepted' => 'Câmpul :attribute este obligatoriu când :other este acceptat.',
    'required_unless' => 'Câmpul :attribute este obligatoriu, dacă :other nu se află în :values.',
    'required_with' => 'Câmpul :attribute este obligatoriu când :values sunt prezente.',
    'required_with_all' => 'Câmpul :attribute este obligatoriu când :values sunt prezente.',
    'required_without' => 'Câmpul :attribute este obligatoriu când :values nu sunt prezente.',
    'required_without_all' => 'Câmpul :attribute este obligatoriu când niciuna dintre :values nu este prezentă.',
    'same' => 'Câmpul :attribute trebuie să coincidă cu :other.',
    'size' => [
        'array' => 'Câmpul :attribute trebuie să conțină :size elemente.',
        'file' => 'Câmpul :attribute trebuie să aibă :size kiloocteți.',
        'numeric' => 'Câmpul :attribute trebuie să fie :size.',
        'string' => 'Câmpul :attribute trebuie să aibă :size caractere.',
    ],
    'starts_with' => 'Câmpul :attribute trebuie să înceapă cu una dintre următoarele valori: :values.',
    'string' => 'Câmpul :attribute trebuie să fie un șir de caractere.',
    'timezone' => 'Câmpul :attribute trebuie să fie un fus orar valid.',
    'unique' => ':Attribute este deja folosit.',
    'uploaded' => 'Nu s-a putut încărca :attribute.',
    'uppercase' => 'Câmpul :attribute trebuie să fie scris cu litere mari.',
    'url' => 'Câmpul :attribute trebuie să fie un URL valid.',
    'ulid' => 'Câmpul :attribute trebuie să fie un ULID valid.',
    'uuid' => 'Câmpul :attribute trebuie să fie un UUID valid.',

    'phone' => 'Câmpul :attribute trebuie să fie un număr de telefon valid.',
    'phone_international' => 'Câmpul :attribute trebuie să conțină un număr de telefon în format internațional, de exemplu +37369123456.',
    'cartProductUnavailable' => 'Unul dintre produsele din coș nu este disponibil pentru comandă.',
    'pageExpired' => 'Pagina a expirat. Verificați datele și apăsați din nou „Trimiteți”.',

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
        'client_name' => 'nume',
        'client_surname' => 'nume de familie',
        'client_phone' => 'telefon',
        'client_email' => 'email',
        'client_message' => 'comentariu',
        'shipping_city' => 'localitatea',
        'shipping_city_ref' => 'localitatea',
        'shipping_warehouse' => 'oficiul',
        'shipping_warehouse_ref' => 'oficiul',
        'shipping_address' => 'adresa',
        'shipping_message' => 'modalitatea de livrare',
        'cart_content' => 'coșul',
    ],

];
