<?php

declare(strict_types = 1);

namespace Drupal\commerce_api_graphql\Plugin\GraphQL\Fields\TaxNumber;

use Drupal\graphql\Annotation\GraphQLField;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Get the currencyCode of a price.
 *
 * @GraphQLField(
 *   id = "tax_number_number",
 *   secure = false,
 *   name = "number",
 *   type = "String",
 *   parents = {"tax_number"},
 * )
 */
class Number extends FieldPluginBase {

}
