<?php

// Home page
$app->get('/', function () use ($app) {
    $author = $app['dao.author']->findAll();
    return $app['twig']->render('index.html.twig', array('authors' => $authors));
})->bind('home');

// Article details with comments
$app->get('/author/{id}', function ($id) use ($app) {
    $author = $app['dao.author']->find($id);
    $comments = $app['dao.book']->findAllByAuthor($id);
    return $app['twig']->render('author.html.twig', array('author' => $author, 'books' => $authors));
})->bind('author');