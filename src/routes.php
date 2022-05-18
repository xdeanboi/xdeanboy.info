<?php

return [
    '~^$~' => [\xDeanBoy\Controllers\MainController::class, 'main'],

    /**
     * Articles links
     */
    '~^articles$~' => [\xDeanBoy\Controllers\ArticlesController::class, 'viewAll'],
    '~^articles/section/(.*)$~' => [\xDeanBoy\Controllers\ArticlesController::class, 'viewBySection'],
    '~^articles/(\d+)$~' => [\xDeanBoy\Controllers\ArticlesController::class, 'viewArticle'],
    '~^articles/(\d+)/edit$~' => [\xDeanBoy\Controllers\ArticlesController::class, 'editArticle'],
    '~^articles/(\d+)/delete$~' => [\xDeanBoy\Controllers\ArticlesController::class, 'deleteArticle'],
    '~^articles/offering$~' => [\xDeanBoy\Controllers\ArticlesController::class, 'offeringArticle'],

    /**
     * Books links
     */
    '~^books$~' => [\xDeanBoy\Controllers\BooksController::class, 'viewAll'],
    '~^books/search$~' => [\xDeanBoy\Controllers\BooksController::class, 'viewBySearch'],
    //Зробити редагування, додавання, видалення книг
    '~^books/filter/author/(.*)$~' => [\xDeanBoy\Controllers\BooksController::class, 'viewByAuthor'],
    '~^books/filter/genre/(.*)$~' => [\xDeanBoy\Controllers\BooksController::class, 'viewByGenre'],
    '~^books/filter/language/(.*)$~' => [\xDeanBoy\Controllers\BooksController::class, 'viewByLanguage'],
    '~^books/filter$~' => [\xDeanBoy\Controllers\BooksController::class, 'viewByFilter'],
    '~^books/(\d+)$~' => [\xDeanBoy\Controllers\BooksController::class, 'viewBook'],
    '~^books/(\d+)/edit$~' => [\xDeanBoy\Controllers\BooksController::class, 'editBook'],
    '~^books/(\d+)/delete$~' => [\xDeanBoy\Controllers\BooksController::class, 'deleteBook'],

    /**
     * Video links
     */
    '~^video$~' => [\xDeanBoy\Controllers\VideoController::class, 'viewAll'],
    '~^video/(\d+)$~' => [\xDeanBoy\Controllers\VideoController::class, 'viewVideo'],
    '~^video/(\d+)/edit$~' => [\xDeanBoy\Controllers\VideoController::class, 'editVideo'],
    '~^video/(\d+)/delete$~' => [\xDeanBoy\Controllers\VideoController::class, 'deleteVideo'],

    /**
     * About author link
     */
    '~^about-author$~' => [\xDeanBoy\Controllers\MainController::class, 'aboutAuthor'],

    /**
     * Users links
     * Profiles links
     */
    '~^login$~' => [\xDeanBoy\Controllers\UsersController::class, 'login'],
    '~^login/out$~' => [\xDeanBoy\Controllers\UsersController::class, 'loginOut'],
    '~^register$~' => [\xDeanBoy\Controllers\UsersController::class, 'register'],
    '~^users/(\d+)/code/(.*)$~' => [\xDeanBoy\Controllers\UsersController::class, 'activation'],
    '~^users/(\d+)/profile$~' => [\xDeanBoy\Controllers\UsersController::class, 'profile'],
    '~^users/(\d+)/profile/edit$~' => [\xDeanBoy\Controllers\UsersController::class, 'editProfile'],

    /**
     * Admin links
     */
    '~^admin/statistics$~' => [\xDeanBoy\Controllers\AdminControllers\AdminMainController::class, 'statistics'],
    '~^admin/articles$~' => [\xDeanBoy\Controllers\AdminControllers\AdminArticlesController::class, 'view'],
    '~^admin/articles/create$~' => [\xDeanBoy\Controllers\AdminControllers\AdminArticlesController::class, 'create'],
    '~^admin/articles/offered/(\d+)/delete$~' => [\xDeanBoy\Controllers\AdminControllers\AdminArticlesController::class, 'deleteOfferedArticle'],
    '~^admin/users$~' => [\xDeanBoy\Controllers\AdminControllers\AdminUsersController::class, 'view'],

];

/**
 * Filters for books - authors, genre, language
 * add pages edit, delete, add books
 */