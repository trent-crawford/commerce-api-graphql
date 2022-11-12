<?php

declare(strict_types = 1);

namespace Drupal\commerce_api_graphql\Plugin\GraphQL\Fields\Price;

use CommerceGuys\Intl\Formatter\CurrencyFormatterInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\graphql\Annotation\GraphQLField;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Get the formatted price.
 *
 * @GraphQLField(
 *   id = "price_foramtted",
 *   secure = false,
 *   name = "formatted",
 *   type = "String",
 *   parents = {"price"},
 * )
 */
class Formatted extends FieldPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The price formatter.
   *
   * @var \CommerceGuys\Intl\Formatter\CurrencyFormatterInterface
   */
  protected CurrencyFormatterInterface $currencyFormatter;

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
                       $pluginId,
                       $pluginDefinition
  ) {
    return new static(
      $configuration,
      $pluginId,
      $pluginDefinition,
      $container->get('commerce_price.currency_formatter')
    );
  }

  /**
   * Assets constructor.
   *
   * @param array $configuration
   *   The plugin configuration array.
   * @param string $pluginId
   *   The plugin id.
   * @param mixed $pluginDefinition
   *   The plugin definition.
   * @param \CommerceGuys\Intl\Formatter\CurrencyFormatterInterface $currencyFormatter
   *   The currency formatter service.
   */
  public function __construct(
    array $configuration,
          $pluginId,
          $pluginDefinition,
    CurrencyFormatterInterface $currencyFormatter
  ) {
    parent::__construct($configuration, $pluginId, $pluginDefinition);
    $this->currencyFormatter = $currencyFormatter;
  }

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $this->currencyFormatter->format($value->getNumber(), $value->getCurrencyCode());
  }

}
