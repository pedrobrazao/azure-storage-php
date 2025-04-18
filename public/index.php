    <?php
    
use App\Handler\Blobs\BlobCreateHandler;
use App\Handler\Blobs\BlobUploadHandler;
use App\Handler\Blobs\BlobViewHandler;
use App\Handler\Containers\ContainerCreateHandler;
use App\Handler\Containers\ContainerListHandler;
use App\Handler\Containers\ContainerViewHandler;
use App\Handler\HomeHandler;
use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

// Create the Container builder.
$containerBuilder = new ContainerBuilder();

// Add service definitions to the Container.
    $containerBuilder->addDefinitions(include __DIR__ . '/../config/container.php');

    //Build the Container instance.
    $container = $containerBuilder->build();

    // Set Container into Factory before create a new App instance.
AppFactory::setContainer($container);

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
$app = AppFactory::create();

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $container->get(Twig::class)));

// Enable automatic body parsing for the most common MIME types.
$app->addBodyParsingMiddleware();

/**
  * The routing middleware should be added earlier than the ErrorMiddleware
  * Otherwise exceptions thrown from it will not be handled by the middleware
  */
$app->addRoutingMiddleware();

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Define app routes
$app->get('/', HomeHandler::class)->setName('home');
$app->get('/containers/list', ContainerListHandler::class)->setName('container_list');
$app->get('/containers/view/{name}', ContainerViewHandler::class)->setName('container_view');
$app->map(['GET', 'POST'], '/containers/create', ContainerCreateHandler::class)->setName('container_create');
$app->map(['GET', 'POST'], '/blobs/{container}/create', BlobCreateHandler::class)->setName('blob_create');
$app->map(['GET', 'POST'], '/blobs/{container}/upload', BlobUploadHandler::class)->setName('blob_upload');
$app->get('/blobs/{container}/view[/{blob:.+}]', BlobViewHandler::class)->setName('blob_view');


// Run app
$app->run();