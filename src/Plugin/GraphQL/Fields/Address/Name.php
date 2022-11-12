<?php

declare(strict_types = 1);

namespace Drupal\commerce_api_graphql\Plugin\GraphQL\Fields\Address
;

use Drupal\graphql\Annotation\GraphQLField;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;

/**
 * Dummy field.
 *
 * @GraphQLField(
 *   id = "address_name",
 *   secure = false,
 *   name = "name",
 *   type = "String",
 *   parents = {"address"},
 * )
 */
class Name extends FieldPluginBase {

}
