<?php

return array(
    'pointer_events' => 'cursor: not-allowed !important; pointer-events: none',
    'link_not_available' => 'Link is not available or URL is broken for this resource.',
    "asset_per_page" => 12,
    "platform_per_page" => 10,
    'success' => 200,
    'error' => 400,
    'server_error' => 500,
    'not_available' => 404,
    'not_exist' => 'records not exist',
    'exist' => 'already exist',
    'insert_success_msg' => 'Record inserted successfully.',
    'insert' => [
        'success' => 'Successfully inserted the data.',
        'error' => 'Error while inserting the data.',
    ],
    'fetch' => [
        'success' => 'Successfully fetched the data.',
        'error' => 'Error while fetching the data.',
    ],
    'update' => [
        'success' => 'Successfully updated the data.',
        'error' => 'Error while updating the data.',
    ],
    'delete' => [
        'success' => 'Successfully deleted the data.',
        'error' => 'Error while deleting the data.',
    ],
    'restore' => [
        'success' => 'Successfully restore the data.',
        'error' => 'Error while restoring the data.',
    ],
    'no_data_found' => [
        'success' => 'There is no data found.',
    ],
    "KID" => [
        "canvas" => "2050-12-01T00:00:00Z_84e5c3f4-c72e-4823-9b7c-1ef9e7d9e0d9",
        "blackboard" => "00a694ea-cf73-4d15-b40b-6768b0f1bd78",
    ],

    "search_API" => [
        "domain" => env('SEARCH_API_DOMAIN'),
        "path" => env('SEARCH_API_PATH'),
        "apiKey" => env('SEARCH_API_KEY'),
    ],

    "filterDisplayNameEngliash" => [
        'Textbooks', 'Book+Chapter', 'Multimedia',
        'Images', 'Tables', 'Quick+Reference+Resources',
        'Patient+Education', 'Case', 'Author',
    ],

    "filterDisplayNameSpanish" => [
        ['id' => 'Capítulo', 'name' => 'Capítulo'], ['id' => 'Capítulo+de+Livro', 'name' => 'Capítulo+de+Livro'],
        ['id' => 'Cuadros', 'name' => 'Cuadros'], ['id' => 'Tabelas', 'name' => 'Tabelas'], ['id' => 'Libros', 'name' => 'Libros'], ['id' => 'Livros', 'name' => 'Livros'],
    ],

    "cspDefaultSrc" => [
        'https://mghrc.silverchair.com', 'https://mhmedical.com','https://www.gstatic.com','https://gstatic.com'
    ],
    "cspScriptSrc" => [
        'http://*.cloudflare.com', 'https://*.datatables.net', 'https://*.cloudflare.com', 'https://google.com', 'https://www.google.com','https://www.gstatic.com','https://gstatic.com' 
    ],
    "cspFontSrc" => [
        'https://*.cloudflare.com','http://*.bootstrapcdn.com','https://fonts.gstatic.com', 'https://fonts.bunny.net','https://www.gstatic.com','https://gstatic.com'
    ],
    "cspStyleSrc" => [
        'http://*.bootstrapcdn.com', 'http://*.cloudflare.com', 'https://*.datatables.net', 'https://*.cloudflare.com', 'https://fonts.bunny.net', 'http://*.bootstrapcdn.com','https://www.gstatic.com','https://gstatic.com' 
    ],
    "cspImgSrc" => [
        'https://*.cloudfront.net', 'https://mgh.silverchair-cdn.com', 'https://mghcdn.silverchair-staging.com', 'https://d1ql8nt6detejj.cloudfront.net', 'https://*.silverchair-cdn.com','https://www.gstatic.com','https://gstatic.com'
    ],
    "cspFormAction" => [
        'https://mghcdn.silverchair-staging.com', 'https://mghrc.silverchair.com', 'https://mhmedical.com','https://www.gstatic.com','https://gstatic.com'
    ],
    
    

    'record_per_page' => 20,
);