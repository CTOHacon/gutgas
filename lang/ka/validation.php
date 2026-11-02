<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | ქვემოთ მოცემული ენის ხაზები შეიცავს შეცდომის ტექსტურ შეტყობინებებს, რომლებსაც ვალიდატორის კლასი ავტომატურად იყენებს.
    | ზოგიერთ წესს აქვს რამდენიმე ვარიანტი, მაგალითად ზომის წესები. თითოეული ეს შეტყობინება საჭიროებისამებრ შეგიძლიათ შეცვალოთ.
    |
    */

    'accepted' => ':attribute ველი უნდა იყოს მიღებული.',
    'accepted_if' => ':attribute ველი უნდა იყოს მიღებული, როცა :other არის :value.',
    'active_url' => ':attribute ველი უნდა იყოს სწორი URL.',
    'after' => ':attribute ველი უნდა იყოს თარიღი :date-ის შემდეგ.',
    'after_or_equal' => ':attribute ველი უნდა იყოს თარიღი, რომელიც :date-ის ტოლია ან მის შემდეგაა.',
    'alpha' => ':attribute ველი უნდა შეიცავდეს მხოლოდ ასოებს.',
    'alpha_dash' => ':attribute ველი უნდა შეიცავდეს მხოლოდ ასოებს, ციფრებს, დეფისებსა და ქვედა ტირეებს.',
    'alpha_num' => ':attribute ველი უნდა შეიცავდეს მხოლოდ ასოებსა და ციფრებს.',
    'array' => ':attribute ველი უნდა იყოს მასივი.',
    'ascii' => ':attribute ველი უნდა შეიცავდეს მხოლოდ ASCII სიმბოლოებსა და ნიშნებს.',
    'before' => ':attribute ველი უნდა იყოს თარიღი :date-ზე ადრე.',
    'before_or_equal' => ':attribute ველი უნდა იყოს თარიღი, რომელიც :date-ზე ადრეა ან მის ტოლია.',
    'between' => [
        'array' => ':attribute ველი უნდა შეიცავდეს :min-დან :max-მდე ელემენტს.',
        'file' => ':attribute ველი უნდა იყოს :min-სა და :max კილობაიტს შორის.',
        'numeric' => ':attribute ველი უნდა იყოს :min-სა და :max-ს შორის.',
        'string' => ':attribute ველი უნდა შეიცავდეს :min-დან :max-მდე სიმბოლოს.',
    ],
    'boolean' => ':attribute ველი უნდა იყოს true ან false.',
    'confirmed' => ':attribute ველის დადასტურება არ ემთხვევა.',
    'current_password' => 'პაროლი არასწორია.',
    'date' => ':attribute ველი უნდა იყოს სწორი თარიღი.',
    'date_equals' => ':attribute ველი უნდა იყოს თარიღი, რომელიც უდრის :date-ს.',
    'date_format' => ':attribute ველი არ შეესაბამება ფორმატს :format.',
    'decimal' => ':attribute ველს უნდა ჰქონდეს :decimal ათწილადი ნიშანი.',
    'declined' => ':attribute ველი უნდა იყოს უარყოფილი.',
    'declined_if' => ':attribute ველი უნდა იყოს უარყოფილი, როცა :other არის :value.',
    'different' => ':attribute და :other ველები უნდა განსხვავდებოდეს.',
    'digits' => ':attribute ველი უნდა შეიცავდეს :digits ციფრს.',
    'digits_between' => ':attribute ველი უნდა შეიცავდეს :min-დან :max-მდე ციფრს.',
    'dimensions' => ':attribute ველს აქვს სურათის არასწორი ზომები.',
    'distinct' => ':attribute ველს აქვს დუბლირებული მნიშვნელობა.',
    'doesnt_end_with' => ':attribute ველი არ უნდა მთავრდებოდეს შემდეგი მნიშვნელობებიდან ერთ-ერთით: :values.',
    'doesnt_start_with' => ':attribute ველი არ უნდა იწყებოდეს შემდეგი მნიშვნელობებიდან ერთ-ერთით: :values.',
    'email' => ':attribute ველი უნდა იყოს სწორი ელფოსტის მისამართი.',
    'ends_with' => ':attribute ველი უნდა მთავრდებოდეს შემდეგი მნიშვნელობებიდან ერთ-ერთით: :values.',
    'enum' => 'არჩეული :attribute მნიშვნელობა არასწორია.',
    'exists' => 'არჩეული :attribute მნიშვნელობა არასწორია.',
    'file' => ':attribute ველი უნდა იყოს ფაილი.',
    'filled' => ':attribute ველი უნდა იყოს შევსებული.',
    'gt' => [
        'array' => ':attribute ველი უნდა შეიცავდეს :value-ზე მეტ ელემენტს.',
        'file' => ':attribute ველი უნდა იყოს :value კილობაიტზე მეტი.',
        'numeric' => ':attribute ველი უნდა იყოს :value-ზე მეტი.',
        'string' => ':attribute ველი უნდა შეიცავდეს :value-ზე მეტ სიმბოლოს.',
    ],
    'gte' => [
        'array' => ':attribute ველი უნდა შეიცავდეს :value ან მეტ ელემენტს.',
        'file' => ':attribute ველი უნდა იყოს :value კილობაიტი ან მეტი.',
        'numeric' => ':attribute ველი უნდა იყოს :value ან მეტი.',
        'string' => ':attribute ველი უნდა შეიცავდეს :value ან მეტ სიმბოლოს.',
    ],
    'image' => ':attribute ველი უნდა იყოს სურათი.',
    'in' => 'არჩეული :attribute მნიშვნელობა არასწორია.',
    'in_array' => ':attribute ველი არ არსებობს :other-ში.',
    'integer' => ':attribute ველი უნდა იყოს მთელი რიცხვი.',
    'ip' => ':attribute ველი უნდა იყოს სწორი IP მისამართი.',
    'ipv4' => ':attribute ველი უნდა იყოს სწორი IPv4 მისამართი.',
    'ipv6' => ':attribute ველი უნდა იყოს სწორი IPv6 მისამართი.',
    'json' => ':attribute ველი უნდა იყოს სწორი JSON სტრიქონი.',
    'lowercase' => ':attribute ველი უნდა იყოს პატარა ასოებით.',
    'lt' => [
        'array' => ':attribute ველი უნდა შეიცავდეს :value-ზე ნაკლებ ელემენტს.',
        'file' => ':attribute ველი უნდა იყოს :value კილობაიტზე ნაკლები.',
        'numeric' => ':attribute ველი უნდა იყოს :value-ზე ნაკლები.',
        'string' => ':attribute ველი უნდა შეიცავდეს :value-ზე ნაკლებ სიმბოლოს.',
    ],
    'lte' => [
        'array' => ':attribute ველი უნდა შეიცავდეს არაუმეტეს :value ელემენტს.',
        'file' => ':attribute ველი უნდა იყოს :value კილობაიტი ან ნაკლები.',
        'numeric' => ':attribute ველი უნდა იყოს :value ან ნაკლები.',
        'string' => ':attribute ველი უნდა შეიცავდეს :value ან ნაკლებ სიმბოლოს.',
    ],
    'mac_address' => ':attribute ველი უნდა იყოს სწორი MAC მისამართი.',
    'max' => [
        'array' => ':attribute ველი არ უნდა შეიცავდეს :max-ზე მეტ ელემენტს.',
        'file' => ':attribute ველი არ უნდა იყოს :max კილობაიტზე მეტი.',
        'numeric' => ':attribute ველი არ შეიძლება იყოს :max-ზე მეტი.',
        'string' => ':attribute ველი არ უნდა შეიცავდეს :max-ზე მეტ სიმბოლოს.',
    ],
    'max_digits' => ':attribute ველი არ უნდა შეიცავდეს :max-ზე მეტ ციფრს.',
    'mimes' => ':attribute ველი უნდა იყოს შემდეგი ტიპის ფაილი: :values.',
    'mimetypes' => ':attribute ველი უნდა იყოს შემდეგი ტიპის ფაილი: :values.',
    'min' => [
        'array' => ':attribute ველი უნდა შეიცავდეს მინიმუმ :min ელემენტს.',
        'file' => ':attribute ველი უნდა იყოს მინიმუმ :min კილობაიტი.',
        'numeric' => ':attribute ველი უნდა იყოს მინიმუმ :min.',
        'string' => ':attribute ველი უნდა შეიცავდეს მინიმუმ :min სიმბოლოს.',
    ],
    'min_digits' => ':attribute ველი უნდა შეიცავდეს მინიმუმ :min ციფრს.',
    'missing' => ':attribute ველი არ უნდა არსებობდეს.',
    'missing_if' => ':attribute ველი არ უნდა არსებობდეს, როცა :other არის :value.',
    'missing_unless' => ':attribute ველი არ უნდა არსებობდეს, თუ :other არ არის :value.',
    'missing_with' => ':attribute ველი არ უნდა არსებობდეს, როცა არსებობს :values.',
    'missing_with_all' => ':attribute ველი არ უნდა არსებობდეს, როცა არსებობს :values.',
    'multiple_of' => ':attribute ველი უნდა იყოს :value-ის ჯერადი.',
    'not_in' => 'არჩეული :attribute მნიშვნელობა არასწორია.',
    'not_regex' => ':attribute ველის ფორმატი არასწორია.',
    'numeric' => ':attribute ველი უნდა იყოს რიცხვი.',
    'password' => [
        'letters' => ':attribute ველი უნდა შეიცავდეს მინიმუმ ერთ ასოს.',
        'mixed' => ':attribute ველი უნდა შეიცავდეს მინიმუმ ერთ დიდ და ერთ პატარა ასოს.',
        'numbers' => ':attribute ველი უნდა შეიცავდეს მინიმუმ ერთ ციფრს.',
        'symbols' => ':attribute ველი უნდა შეიცავდეს მინიმუმ ერთ სიმბოლოს.',
        'uncompromised' => 'ეს :attribute გამოჩნდა მონაცემთა გაჟონვაში. გთხოვთ, აირჩიოთ სხვა :attribute.',
    ],
    'present' => ':attribute ველი უნდა არსებობდეს.',
    'prohibited' => ':attribute ველი აკრძალულია.',
    'prohibited_if' => ':attribute ველი აკრძალულია, როცა :other არის :value.',
    'prohibited_unless' => ':attribute ველი აკრძალულია, თუ :other არ არის :values-ში.',
    'prohibits' => ':attribute ველი კრძალავს :other-ის არსებობას.',
    'regex' => ':attribute ველის ფორმატი არასწორია.',
    'required' => ':attribute ველი სავალდებულოა.',
    'required_array_keys' => ':attribute ველი უნდა შეიცავდეს ჩანაწერებს შემდეგისთვის: :values.',
    'required_if' => ':attribute ველი სავალდებულოა, როცა :other არის :value.',
    'required_if_accepted' => ':attribute ველი სავალდებულოა, როცა :other მიღებულია.',
    'required_unless' => ':attribute ველი სავალდებულოა, თუ :other არ არის :values-ში.',
    'required_with' => ':attribute ველი სავალდებულოა, როცა არსებობს :values.',
    'required_with_all' => ':attribute ველი სავალდებულოა, როცა არსებობს :values.',
    'required_without' => ':attribute ველი სავალდებულოა, როცა არ არსებობს :values.',
    'required_without_all' => ':attribute ველი სავალდებულოა, როცა არც ერთი :values არ არსებობს.',
    'same' => ':attribute ველი უნდა ემთხვეოდეს :other-ს.',
    'size' => [
        'array' => ':attribute ველი უნდა შეიცავდეს :size ელემენტს.',
        'file' => ':attribute ველი უნდა იყოს :size კილობაიტი.',
        'numeric' => ':attribute ველი უნდა იყოს :size.',
        'string' => ':attribute ველი უნდა შეიცავდეს :size სიმბოლოს.',
    ],
    'starts_with' => ':attribute ველი უნდა იწყებოდეს შემდეგი მნიშვნელობებიდან ერთ-ერთით: :values.',
    'string' => ':attribute ველი უნდა იყოს სტრიქონი.',
    'timezone' => ':attribute ველი უნდა იყოს სწორი საათობრივი სარტყელი.',
    'unique' => ':Attribute უკვე დაკავებულია.',
    'uploaded' => ':attribute-ის ატვირთვა ვერ მოხერხდა.',
    'uppercase' => ':attribute ველი უნდა იყოს დიდი ასოებით.',
    'url' => ':attribute ველი უნდა იყოს სწორი URL.',
    'ulid' => ':attribute ველი უნდა იყოს სწორი ULID.',
    'uuid' => ':attribute ველი უნდა იყოს სწორი UUID.',

    'phone' => ':attribute ველი უნდა იყოს სწორი უკრაინული ტელეფონის ნომერი.',
    'cartProductUnavailable' => 'კალათაში ერთ-ერთი პროდუქტი შეკვეთისთვის მიუწვდომელია.',
    'pageExpired' => 'გვერდის ვადა ამოიწურა. შეამოწმეთ მონაცემები და ისევ დააჭირეთ «გაგზავნას».',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | აქ შეგიძლიათ მიუთითოთ საკუთარი შეცდომის ტექსტური შეტყობინებები ატრიბუტებისთვის, "attribute.rule" კონვენციის გამოყენებით
    | ენის ხაზების დასასახელებლად. ეს საშუალებას გაძლევთ სწრაფად დააყენოთ კონკრეტული ენის ხაზები ატრიბუტების წესებისთვის საჭიროებისამებრ.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'მორგებული შეტყობინება',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | ქვემოთ მოცემული ენის ხაზები გამოიყენება ატრიბუტის მომხმარებლისთვის უფრო გასაგები სახელით ჩასანაცვლებლად, მაგალითად "ელფოსტის მისამართი"
    | "email"-ის ნაცვლად. ეს უბრალოდ ხელს უწყობს შეტყობინების უფრო გასაგებად გახდომას.
    |
    */

    'attributes' => [
        'client_name' => 'სახელი',
        'client_surname' => 'გვარი',
        'client_phone' => 'ტელეფონი',
        'client_email' => 'ელფოსტა',
        'client_message' => 'კომენტარი',
        'shipping_city' => 'ქალაქი',
        'shipping_city_ref' => 'ქალაქი',
        'shipping_warehouse' => 'ფილიალი',
        'shipping_warehouse_ref' => 'ფილიალი',
        'shipping_address' => 'მისამართი',
        'shipping_message' => 'მიწოდების მეთოდი',
        'cart_content' => 'კალათა',
    ],

];
