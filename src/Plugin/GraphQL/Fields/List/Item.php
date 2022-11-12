<?php

declare(strict_types = 1);

namespace Drupal\commerce_api_graphql\Plugin\GraphQL\Fields\List;

use Drupal\graphql\Annotation\GraphQLField;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;

/**
 * Dummy field.
 *
 * @GraphQLField(
 *   id = "list_item",
 *   secure = false,
 *   name = "item",
 *   type = "String",
 *   parents = {"list"},
 * )
 */
class Item extends FieldPluginBase {

}
