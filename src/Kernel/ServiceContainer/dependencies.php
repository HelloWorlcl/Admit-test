<?php

return [
    \App\Controllers\BookController::class => [
        \App\Repositories\BookRepository::class,
        \App\Repositories\AuthorRepository::class
    ],
    \App\Controllers\AuthorController::class => [
        \App\Repositories\AuthorRepository::class
    ],
    \App\Repositories\BookRepository::class => [
        \App\Kernel\Database\Connection\MySQLConnection::class,
        \App\Models\Factories\BookFactory::class
    ],
    \App\Repositories\AuthorRepository::class => [
        \App\Kernel\Database\Connection\MySQLConnection::class,
        \App\Models\Factories\AuthorFactory::class
    ],
    \App\Models\Factories\BookFactory::class => [
        \App\Models\Factories\AuthorFactory::class,
        \App\Repositories\AuthorRepository::class
    ]
];
