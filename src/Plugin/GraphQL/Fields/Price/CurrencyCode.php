<?php

declare(strict_types = 1);

namespace Drupal\commerce_api_graphql\Plugin\GraphQL\Fields\Price;

use Drupal\graphql\Annotation\GraphQLField;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Get the currencyCode of a price.
 *
 * @GraphQLField(
 *   id = "price_currency_code",
 *   secure = false,
 *   name = "currencyCode",
 *   type = "String",
 *   parents = {"price"},
 * )
 */
class CurrencyCode extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $value->getCurrencyCode();
  }

}
