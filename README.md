# pixabay-helper
PHP helper for Pixabay free image service

Examples:

$photos = $pixabay->getPixAbayPhotos(); // get 200 random photos

or with using params

$photos = $pixabay->getPixAbayPhotos(
    ['cnt' => 5],
    [
        'cnt' => 5,
        'orientation' => 'vertical',
        'query' => 'computer',
    ],
    [
        'cnt' => 8,
        'query' => 'cars',
    ],
);
