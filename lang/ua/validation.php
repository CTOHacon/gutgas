<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Нижче наведені рядки мови містять текстові повідомлення про помилки, які за замовчуванням використовує клас валідатора.
    | Деякі з цих правил мають кілька варіантів, таких як правила для розміру. Ви можете змінювати кожне з цих повідомлень за потреби.
    |
    */

    'accepted' => 'Поле :attribute повинно бути прийняте.',
    'accepted_if' => 'Поле :attribute повинно бути прийняте, коли :other має значення :value.',
    'active_url' => 'Поле :attribute повинно бути правильним URL.',
    'after' => 'Поле :attribute повинно бути датою після :date.',
    'after_or_equal' => 'Поле :attribute повинно бути датою після або рівною :date.',
    'alpha' => 'Поле :attribute повинно містити лише букви.',
    'alpha_dash' => 'Поле :attribute повинно містити лише букви, цифри, дефіси та підкреслення.',
    'alpha_num' => 'Поле :attribute повинно містити лише букви та цифри.',
    'array' => 'Поле :attribute повинно бути масивом.',
    'ascii' => 'Поле :attribute повинно містити лише ASCII символи та символи.',
    'before' => 'Поле :attribute повинно бути датою до :date.',
    'before_or_equal' => 'Поле :attribute повинно бути датою до або рівною :date.',
    'between' => [
        'array' => 'Поле :attribute повинно містити від :min до :max елементів.',
        'file' => 'Поле :attribute повинно бути між :min та :max кілобайт.',
        'numeric' => 'Поле :attribute повинно бути між :min та :max.',
        'string' => 'Поле :attribute повинно бути між :min та :max символами.',
    ],
    'boolean' => 'Поле :attribute повинно мати значення true або false.',
    'confirmed' => 'Поле :attribute не збігається з підтвердженням.',
    'current_password' => 'Пароль невірний.',
    'date' => 'Поле :attribute повинно бути правильною датою.',
    'date_equals' => 'Поле :attribute повинно бути датою, рівною :date.',
    'date_format' => 'Поле :attribute не відповідає формату :format.',
    'decimal' => 'Поле :attribute повинно мати :decimal знаки після коми.',
    'declined' => 'Поле :attribute повинно бути відхилено.',
    'declined_if' => 'Поле :attribute повинно бути відхилено, коли :other має значення :value.',
    'different' => 'Поля :attribute та :other повинні бути різними.',
    'digits' => 'Поле :attribute повинно мати :digits цифр.',
    'digits_between' => 'Поле :attribute повинно бути між :min та :max цифрами.',
    'dimensions' => 'Поле :attribute має недійсні розміри зображення.',
    'distinct' => 'Поле :attribute має дублююче значення.',
    'doesnt_end_with' => 'Поле :attribute не може закінчуватись на одне з наступних значень: :values.',
    'doesnt_start_with' => 'Поле :attribute не може починатись з одного з наступних значень: :values.',
    'email' => 'Поле :attribute повинно бути правильною адресою електронної пошти.',
    'ends_with' => 'Поле :attribute повинно закінчуватись одним із наступних значень: :values.',
    'enum' => 'Вибране значення :attribute недійсне.',
    'exists' => 'Вибране значення :attribute недійсне.',
    'file' => 'Поле :attribute повинно бути файлом.',
    'filled' => 'Поле :attribute повинно мати значення.',
    'gt' => [
        'array' => 'Поле :attribute повинно містити більше ніж :value елементів.',
        'file' => 'Поле :attribute повинно бути більше ніж :value кілобайт.',
        'numeric' => 'Поле :attribute повинно бути більше ніж :value.',
        'string' => 'Поле :attribute повинно містити більше ніж :value символів.',
    ],
    'gte' => [
        'array' => 'Поле :attribute повинно містити :value елементів або більше.',
        'file' => 'Поле :attribute повинно бути більше або рівне :value кілобайт.',
        'numeric' => 'Поле :attribute повинно бути більше або рівне :value.',
        'string' => 'Поле :attribute повинно містити більше або рівне :value символів.',
    ],
    'image' => 'Поле :attribute повинно бути зображенням.',
    'in' => 'Вибране значення :attribute недійсне.',
    'in_array' => 'Поле :attribute не існує в :other.',
    'integer' => 'Поле :attribute повинно бути цілим числом.',
    'ip' => 'Поле :attribute повинно бути правильною IP адресою.',
    'ipv4' => 'Поле :attribute повинно бути правильною IPv4 адресою.',
    'ipv6' => 'Поле :attribute повинно бути правильною IPv6 адресою.',
    'json' => 'Поле :attribute повинно бути правильним JSON рядком.',
    'lowercase' => 'Поле :attribute повинно бути у нижньому регістрі.',
    'lt' => [
        'array' => 'Поле :attribute повинно містити менше ніж :value елементів.',
        'file' => 'Поле :attribute повинно бути менше ніж :value кілобайт.',
        'numeric' => 'Поле :attribute повинно бути менше ніж :value.',
        'string' => 'Поле :attribute повинно містити менше ніж :value символів.',
    ],
    'lte' => [
        'array' => 'Поле :attribute повинно містити не більше ніж :value елементів.',
        'file' => 'Поле :attribute повинно бути менше або рівне :value кілобайт.',
        'numeric' => 'Поле :attribute повинно бути менше або рівне :value.',
        'string' => 'Поле :attribute повинно містити менше або рівне :value символів.',
    ],
    'mac_address' => 'Поле :attribute повинно бути правильною MAC адресою.',
    'max' => [
        'array' => 'Поле :attribute не може містити більше ніж :max елементів.',
        'file' => 'Поле :attribute не може бути більше ніж :max кілобайт.',
        'numeric' => 'Поле :attribute не може бути більше ніж :max.',
        'string' => 'Поле :attribute не може бути більше ніж :max символів.',
    ],
    'max_digits' => 'Поле :attribute не може мати більше ніж :max цифр.',
    'mimes' => 'Поле :attribute повинно бути файлом типу: :values.',
    'mimetypes' => 'Поле :attribute повинно бути файлом типу: :values.',
    'min' => [
        'array' => 'Поле :attribute повинно містити принаймні :min елементів.',
        'file' => 'Поле :attribute повинно бути принаймні :min кілобайт.',
        'numeric' => 'Поле :attribute повинно бути принаймні :min.',
        'string' => 'Поле :attribute повинно містити принаймні :min символів.',
    ],
    'min_digits' => 'Поле :attribute повинно мати принаймні :min цифр.',
    'missing' => 'Поле :attribute повинно бути відсутнім.',
    'missing_if' => 'Поле :attribute повинно бути відсутнім, коли :other має значення :value.',
    'missing_unless' => 'Поле :attribute повинно бути відсутнім, якщо :other не має значення :value.',
    'missing_with' => 'Поле :attribute повинно бути відсутнім, коли :values присутні.',
    'missing_with_all' => 'Поле :attribute повинно бути відсутнім, коли :values присутні.',
    'multiple_of' => 'Поле :attribute повинно бути кратним :value.',
    'not_in' => 'Вибране значення :attribute недійсне.',
    'not_regex' => 'Формат поля :attribute недійсний.',
    'numeric' => 'Поле :attribute повинно бути числом.',
    'password' => [
        'letters' => 'Поле :attribute повинно містити принаймні одну букву.',
        'mixed' => 'Поле :attribute повинно містити принаймні одну прописну літеру та одну малу літеру.',
        'numbers' => 'Поле :attribute повинно містити принаймні одну цифру.',
        'symbols' => 'Поле :attribute повинно містити принаймні один символ.',
        'uncompromised' => 'Даний :attribute з\'явився в витоку даних. Будь ласка, виберіть інше :attribute.',
    ],
    'present' => 'Поле :attribute повинно бути присутнім.',
    'prohibited' => 'Поле :attribute заборонено.',
    'prohibited_if' => 'Поле :attribute заборонено, коли :other має значення :value.',
    'prohibited_unless' => 'Поле :attribute заборонено, якщо :other не знаходиться в :values.',
    'prohibits' => 'Поле :attribute забороняє :other бути присутнім.',
    'regex' => 'Формат поля :attribute недійсний.',
    'required' => 'Поле :attribute обов\'язкове.',
    'required_array_keys' => 'Поле :attribute повинно містити записи для: :values.',
    'required_if' => 'Поле :attribute обов\'язкове, коли :other має значення :value.',
    'required_if_accepted' => 'Поле :attribute обов\'язкове, коли :other прийнято.',
    'required_unless' => 'Поле :attribute обов\'язкове, якщо :other не знаходиться в :values.',
    'required_with' => 'Поле :attribute обов\'язкове, коли :values присутні.',
    'required_with_all' => 'Поле :attribute обов\'язкове, коли :values присутні.',
    'required_without' => 'Поле :attribute обов\'язкове, коли :values не присутні.',
    'required_without_all' => 'Поле :attribute обов\'язкове, коли немає жодного з :values.',
    'same' => 'Поле :attribute повинно співпадати з :other.',
    'size' => [
        'array' => 'Поле :attribute повинно містити :size елементів.',
        'file' => 'Поле :attribute повинно бути :size кілобайт.',
        'numeric' => 'Поле :attribute повинно бути :size.',
        'string' => 'Поле :attribute повинно бути :size символів.',
    ],
    'starts_with' => 'Поле :attribute повинно починатись одним із наступних значень: :values.',
    'string' => 'Поле :attribute повинно бути рядком.',
    'timezone' => 'Поле :attribute повинно бути правильним часовим поясом.',
    'unique' => ':Attribute вже зайнято.',
    'uploaded' => 'Не вдалося завантажити :attribute.',
    'uppercase' => 'Поле :attribute повинно бути у верхньому регістрі.',
    'url' => 'Поле :attribute повинно бути правильним URL.',
    'ulid' => 'Поле :attribute повинно бути правильним ULID.',
    'uuid' => 'Поле :attribute повинно бути правильним UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Тут ви можете вказати власні текстові повідомлення про помилки для атрибутів, використовуючи конвенцію "attribute.rule"
    | для іменування ліній. Це дозволяє швидко вказувати специфічні мовні рядки для правил атрибутів за потреби.
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
    | Нижче наведені рядки мови використовуються для заміни атрибута на щось більш зрозуміле користувачу, наприклад, "E-Mail Address"
    | замість "email". Це просто допомагає зробити повідомлення більш зрозумілим.
    |
    */

    'attributes' => [],

];
