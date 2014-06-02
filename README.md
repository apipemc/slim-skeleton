SLIM SKELETON
=============

Proyecto basico para comenzar cualquier aplicación rapida y ligera con slim framework.

Documentacion dependecias

**SLIM FRAMEWORK :**  http://docs.slimframework.com/

**SLIM CONTROLLER:**  https://github.com/fortrabbit/slimcontroller

    $app->addRoutes(array(
        '/'         => 'Home:index'
        '/posts'    => array('get'  => 'Posts:index',
                         'post' => array('Posts:create', function() { echo 'Middleware!'; })),
        '/posts:id' => 'Posts:show'
    ));

**TWIG :**  http://twig.sensiolabs.org/documentation
			https://github.com/codeguy/Slim-Views

**ORM :**  http://idiorm.readthedocs.org/en/latest/

**SWIFTMAILER :** http://swiftmailer.org/docs/introduction.html


##Create project composer

`composer create-project apipemc/slim-skeleton --stability=dev project-name`
