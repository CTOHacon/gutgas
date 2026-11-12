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

    'accepted' => ':attribute өрісі қабылдануы тиіс.',
    'accepted_if' => ':other :value мәніне ие болғанда, :attribute өрісі қабылдануы тиіс.',
    'active_url' => ':attribute өрісі дұрыс URL болуы тиіс.',
    'after' => ':attribute өрісі :date күнінен кейінгі күн болуы тиіс.',
    'after_or_equal' => ':attribute өрісі :date күніне тең немесе одан кейінгі күн болуы тиіс.',
    'alpha' => ':attribute өрісі тек әріптерден тұруы тиіс.',
    'alpha_dash' => ':attribute өрісі тек әріптерден, сандардан, дефистерден және астын сызықтардан тұруы тиіс.',
    'alpha_num' => ':attribute өрісі тек әріптер мен сандардан тұруы тиіс.',
    'array' => ':attribute өрісі массив болуы тиіс.',
    'ascii' => ':attribute өрісі тек ASCII таңбалары мен белгілерінен тұруы тиіс.',
    'before' => ':attribute өрісі :date күнінен бұрынғы күн болуы тиіс.',
    'before_or_equal' => ':attribute өрісі :date күніне тең немесе одан бұрынғы күн болуы тиіс.',
    'between' => [
        'array' => ':attribute өрісінде :min мен :max аралығында элемент болуы тиіс.',
        'file' => ':attribute өрісі :min мен :max килобайт аралығында болуы тиіс.',
        'numeric' => ':attribute өрісі :min мен :max аралығында болуы тиіс.',
        'string' => ':attribute өрісі :min мен :max таңба аралығында болуы тиіс.',
    ],
    'boolean' => ':attribute өрісі true немесе false мәнін қабылдауы тиіс.',
    'confirmed' => ':attribute өрісі растаумен сәйкес келмейді.',
    'current_password' => 'Құпия сөз қате.',
    'date' => ':attribute өрісі дұрыс күн болуы тиіс.',
    'date_equals' => ':attribute өрісі :date күніне тең күн болуы тиіс.',
    'date_format' => ':attribute өрісі :format пішіміне сәйкес келмейді.',
    'decimal' => ':attribute өрісінде үтірден кейін :decimal таңба болуы тиіс.',
    'declined' => ':attribute өрісі қабылданбауы тиіс.',
    'declined_if' => ':other :value мәніне ие болғанда, :attribute өрісі қабылданбауы тиіс.',
    'different' => ':attribute және :other өрістері әртүрлі болуы тиіс.',
    'digits' => ':attribute өрісінде :digits цифр болуы тиіс.',
    'digits_between' => ':attribute өрісінде :min мен :max аралығындағы цифр болуы тиіс.',
    'dimensions' => ':attribute өрісінде суреттің жарамсыз өлшемдері бар.',
    'distinct' => ':attribute өрісінде қайталанатын мән бар.',
    'doesnt_end_with' => ':attribute өрісі мынадай мәндердің біреуімен аяқталмауы тиіс: :values.',
    'doesnt_start_with' => ':attribute өрісі мынадай мәндердің біреуінен басталмауы тиіс: :values.',
    'email' => ':attribute өрісі дұрыс электрондық пошта мекенжайы болуы тиіс.',
    'ends_with' => ':attribute өрісі мынадай мәндердің біреуімен аяқталуы тиіс: :values.',
    'enum' => 'Таңдалған :attribute мәні жарамсыз.',
    'exists' => 'Таңдалған :attribute мәні жарамсыз.',
    'file' => ':attribute өрісі файл болуы тиіс.',
    'filled' => ':attribute өрісінде мән болуы тиіс.',
    'gt' => [
        'array' => ':attribute өрісінде :value элементтен көп элемент болуы тиіс.',
        'file' => ':attribute өрісі :value килобайттан үлкен болуы тиіс.',
        'numeric' => ':attribute өрісі :value мәнінен үлкен болуы тиіс.',
        'string' => ':attribute өрісі :value таңбадан көп болуы тиіс.',
    ],
    'gte' => [
        'array' => ':attribute өрісінде :value элемент немесе одан көп элемент болуы тиіс.',
        'file' => ':attribute өрісі :value килобайтқа тең немесе одан үлкен болуы тиіс.',
        'numeric' => ':attribute өрісі :value мәніне тең немесе одан үлкен болуы тиіс.',
        'string' => ':attribute өрісі :value таңбаға тең немесе одан көп болуы тиіс.',
    ],
    'image' => ':attribute өрісі сурет болуы тиіс.',
    'in' => 'Таңдалған :attribute мәні жарамсыз.',
    'in_array' => ':attribute өрісі :other ішінде жоқ.',
    'integer' => ':attribute өрісі бүтін сан болуы тиіс.',
    'ip' => ':attribute өрісі дұрыс IP мекенжайы болуы тиіс.',
    'ipv4' => ':attribute өрісі дұрыс IPv4 мекенжайы болуы тиіс.',
    'ipv6' => ':attribute өрісі дұрыс IPv6 мекенжайы болуы тиіс.',
    'json' => ':attribute өрісі дұрыс JSON жолы болуы тиіс.',
    'lowercase' => ':attribute өрісі кіші әріптермен жазылуы тиіс.',
    'lt' => [
        'array' => ':attribute өрісінде :value элементтен аз элемент болуы тиіс.',
        'file' => ':attribute өрісі :value килобайттан кіші болуы тиіс.',
        'numeric' => ':attribute өрісі :value мәнінен кіші болуы тиіс.',
        'string' => ':attribute өрісі :value таңбадан аз болуы тиіс.',
    ],
    'lte' => [
        'array' => ':attribute өрісінде :value элементтен артық элемент болмауы тиіс.',
        'file' => ':attribute өрісі :value килобайтқа тең немесе одан кіші болуы тиіс.',
        'numeric' => ':attribute өрісі :value мәніне тең немесе одан кіші болуы тиіс.',
        'string' => ':attribute өрісі :value таңбаға тең немесе одан аз болуы тиіс.',
    ],
    'mac_address' => ':attribute өрісі дұрыс MAC мекенжайы болуы тиіс.',
    'max' => [
        'array' => ':attribute өрісінде :max элементтен артық элемент болмауы тиіс.',
        'file' => ':attribute өрісі :max килобайттан үлкен бола алмайды.',
        'numeric' => ':attribute өрісі :max мәнінен үлкен бола алмайды.',
        'string' => ':attribute өрісі :max таңбадан көп бола алмайды.',
    ],
    'max_digits' => ':attribute өрісінде :max цифрдан артық цифр болмауы тиіс.',
    'mimes' => ':attribute өрісі мынадай түрдегі файл болуы тиіс: :values.',
    'mimetypes' => ':attribute өрісі мынадай түрдегі файл болуы тиіс: :values.',
    'min' => [
        'array' => ':attribute өрісінде кемінде :min элемент болуы тиіс.',
        'file' => ':attribute өрісі кемінде :min килобайт болуы тиіс.',
        'numeric' => ':attribute өрісі кемінде :min болуы тиіс.',
        'string' => ':attribute өрісінде кемінде :min таңба болуы тиіс.',
    ],
    'min_digits' => ':attribute өрісінде кемінде :min цифр болуы тиіс.',
    'missing' => ':attribute өрісі жоқ болуы тиіс.',
    'missing_if' => ':other :value мәніне ие болғанда, :attribute өрісі жоқ болуы тиіс.',
    'missing_unless' => ':other :value мәнінде болмаса, :attribute өрісі жоқ болуы тиіс.',
    'missing_with' => ':values бар болғанда, :attribute өрісі жоқ болуы тиіс.',
    'missing_with_all' => ':values бар болғанда, :attribute өрісі жоқ болуы тиіс.',
    'multiple_of' => ':attribute өрісі :value санына еселі болуы тиіс.',
    'not_in' => 'Таңдалған :attribute мәні жарамсыз.',
    'not_regex' => ':attribute өрісінің пішімі жарамсыз.',
    'numeric' => ':attribute өрісі сан болуы тиіс.',
    'password' => [
        'letters' => ':attribute өрісінде кемінде бір әріп болуы тиіс.',
        'mixed' => ':attribute өрісінде кемінде бір бас әріп пен бір кіші әріп болуы тиіс.',
        'numbers' => ':attribute өрісінде кемінде бір сан болуы тиіс.',
        'symbols' => ':attribute өрісінде кемінде бір таңба болуы тиіс.',
        'uncompromised' => 'Бұл :attribute деректер ағынында пайда болған. Басқа :attribute таңдаңыз.',
    ],
    'present' => ':attribute өрісі болуы тиіс.',
    'prohibited' => ':attribute өрісіне тыйым салынған.',
    'prohibited_if' => ':other :value мәніне ие болғанда, :attribute өрісіне тыйым салынған.',
    'prohibited_unless' => ':other :values ішінде болмаса, :attribute өрісіне тыйым салынған.',
    'prohibits' => ':attribute өрісі :other өрісінің болуына тыйым салады.',
    'regex' => ':attribute өрісінің пішімі жарамсыз.',
    'required' => ':attribute өрісі міндетті.',
    'required_array_keys' => ':attribute өрісінде мынадай жазбалар болуы тиіс: :values.',
    'required_if' => ':other :value мәніне ие болғанда, :attribute өрісі міндетті.',
    'required_if_accepted' => ':other қабылданғанда, :attribute өрісі міндетті.',
    'required_unless' => ':other :values ішінде болмаса, :attribute өрісі міндетті.',
    'required_with' => ':values бар болғанда, :attribute өрісі міндетті.',
    'required_with_all' => ':values бар болғанда, :attribute өрісі міндетті.',
    'required_without' => ':values жоқ болғанда, :attribute өрісі міндетті.',
    'required_without_all' => ':values мәндерінің ешқайсысы жоқ болғанда, :attribute өрісі міндетті.',
    'same' => ':attribute өрісі :other өрісімен сәйкес келуі тиіс.',
    'size' => [
        'array' => ':attribute өрісінде :size элемент болуы тиіс.',
        'file' => ':attribute өрісі :size килобайт болуы тиіс.',
        'numeric' => ':attribute өрісі :size болуы тиіс.',
        'string' => ':attribute өрісі :size таңба болуы тиіс.',
    ],
    'starts_with' => ':attribute өрісі мынадай мәндердің біреуінен басталуы тиіс: :values.',
    'string' => ':attribute өрісі жол болуы тиіс.',
    'timezone' => ':attribute өрісі дұрыс уақыт белдеуі болуы тиіс.',
    'unique' => ':Attribute бұрын алынған.',
    'uploaded' => ':attribute жүктелмеді.',
    'uppercase' => ':attribute өрісі бас әріптермен жазылуы тиіс.',
    'url' => ':attribute өрісі дұрыс URL болуы тиіс.',
    'ulid' => ':attribute өрісі дұрыс ULID болуы тиіс.',
    'uuid' => ':attribute өрісі дұрыс UUID болуы тиіс.',

    'phone' => ':attribute өрісі жарамды телефон нөмірі болуы тиіс.',
    'phone_international' => 'Өріс :attribute халықаралық форматтағы телефон нөмірін қамтуы тиіс, мысалы +77011234567.',
    'cartProductUnavailable' => 'Себеттегі тауарлардың бірі тапсырыс беруге қолжетімсіз.',
    'pageExpired' => 'Бет мерзімі өтіп кетті. Деректерді тексеріп, «Жіберу» түймесін тағы басыңыз.',

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
        'client_name' => 'аты',
        'client_surname' => 'тегі',
        'client_phone' => 'телефон нөмірі',
        'client_email' => 'email',
        'client_message' => 'пікір',
        'shipping_city' => 'қала',
        'shipping_city_ref' => 'қала',
        'shipping_warehouse' => 'бөлімше',
        'shipping_warehouse_ref' => 'бөлімше',
        'shipping_address' => 'мекенжай',
        'shipping_message' => 'жеткізу тәсілі',
        'cart_content' => 'себет',
    ],

];
