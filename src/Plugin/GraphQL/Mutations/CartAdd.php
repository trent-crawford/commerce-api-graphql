<?php

declare(strict_types = 1);

namespace Drupal\commerce_api_graphql\Plugin\GraphQL\Mutations;

use Drupal\commerce_api\Resource\CartAddResource;
use Drupal\commerce_order\OrderStorage;
use Drupal\commerce_store\Entity\StoreInterface;
use Drupal\commerce_store\StoreStorage;
use Drupal\commerce_store\StoreStorageInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityType;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\RenderContext;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Url;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Mutations\MutationPluginBase;
use Drupal\graphql_core\GraphQL\EntityCrudOutputWrapper;
use GraphQL\Type\Definition\ResolveInfo;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

/**
 * Mutation to add items to the cart.
 *
 * Wraps CartAddResource.
 * @see Drupal\commerce_api\Resource\CartAddResource
 * @see https://www.drupal.org/docs/8/modules/commerce-api/cart-and-checkout/adding-items-to-cart
 *
 * This rest endpoint does not resolve the price of the item being added:
 * https://www.drupal.org/project/commerce_api/issues/3214148
 *
 * The store is also resolved out of the request and so the inputs will need
 * to be extended to enable the store to be set. There is a header based
 * resolver:
 * @see Drupal\commerce_api\Resolvers\CurrentStoreHeaderResolver.
 *
 * This currently only adds as an anonymous user as the
 * PHP session cookie is not being passed in the request.
 *
 * todo - Create input types.
 *
 * @GraphQLMutation(
 *   id = "cart_add",
 *   secure = false,
 *   name = "cartAdd",
 *   type = "EntityCrudOutput",
 *   arguments = {
 *      "input" = "AddCartInput"
 *   }
 * )
 */
class CartAdd extends MutationPluginBase implements ContainerFactoryPluginInterface {

  const END_POINT = '/jsonapi/cart/add?include';
  const METHOD = 'POST';

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected RendererInterface $renderer,
    protected ClientInterface $http_client,
    protected EntityTypeManagerInterface $entityTypeManager
  )
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static (
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('renderer'),
      $container->get('http_client'),
      $container->get('entity_type.manager')
    );
  }
  /**
   * {@inheritDoc}
   */
  public function resolve($value, array $args, ResolveContext $context, ResolveInfo $info) {
    return $this->renderer->executeInRenderContext(new RenderContext(), function () use ($args) {
      try {
        $response = $this->http_client->request(
          static::METHOD,
          Url::fromUserInput(static::END_POINT, ['absolute' => true])->toString(),
          [
            'headers' => [
              'Accept' => 'application/vnd.api+json',
              'Content-Type' => 'application/vnd.api+json',
              'Commerce-Cart-Token' => $args['commerceCartToken'],
              'Commerce-Current-Store' => $this->getStoreUuid($args)
            ],
            // Ensure http errors are enabled so we don't have to confirm
            // success.
            'http_errors' => true,
            // Avoid hard to debug errors.
            'allow_redirects' => false,
            // todo Make configurable. Verify = false required for local dev.
            'verify' => false,
            'json' => (object)[
              'data' => [(object)[
                'type' => $args['input']['entityType'],
                'id' => $args['input']['id'],
                'meta' => (object)[
                  'quantity' => $args['input']['meta']['quantity'],
                  'combine' => $args['input']['meta']['combine'],
                ]
              ]
              ]
            ],
          ]
          );
          $response_content = $response->getBody()->getContents();
          $response_decoded = json_decode($response_content, true, 512, JSON_THROW_ON_ERROR);
          /** @var OrderStorage $order_storage */
          $order_storage = $this->entityTypeManager->getStorage('commerce_order');
          $cart = $order_storage->load($this->getOrderId($response_decoded));
          assert($cart instanceof EntityInterface);
          return new EntityCrudOutputWrapper($cart, NULL, []);
      } catch (\Exception $e) {
        return new EntityCrudOutputWrapper(NULL, NULL, [$e->getMessage()]);
      }
    });
  }

  protected function getOrderId(array $response): null|int|string {
    [$order_item ] = $response['data'];
    return $order_item['relationships']['order_id']['data']['meta']['drupal_internal__target_id'] ?? NULL;
  }


  protected function getStoreUuid(array $args): string {
    // We don't attempt to load the store to validate it. We let the commerce
    // api do all validation.  If the store is not defined we use the
    // default store rather than the current store. Otherwise the incoming
    // request to the mutation would need to have headers set to resolve the
    // store, which we wish to avoid.
    return $args['input']['store'] ?? $this->getDefaultStore()->uuid();
  }


  protected function getDefaultStore(): StoreInterface {
    /** @var StoreStorageInterface $store_storage */
    $store_storage = $this->entityTypeManager->getStorage('commerce_store');
    $store = $store_storage->loadDefault();
    if(!$store) {
      throw new \Exception('The default store could not be loaded.');
    }
    return $store;
  }

}
