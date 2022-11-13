<?php

declare(strict_types = 1);

namespace Drupal\commerce_api_graphql\Plugin\GraphQL\InputTypes;

use Drupal\graphql\Plugin\GraphQL\InputTypes\InputTypePluginBase;
use Drupal\graphql\Plugin\GraphQL\Types\TypePluginBase;

/**
 * The input type for article mutations.
 *
 * @GraphQLInputType(
 *   id = "add_cart_input_meta",
 *   secure = false,
 *   name = "AddCartInputMeta",
 *   fields = {
 *     "quantity" = "String!",
 *     "combine" = "Boolean!",
 *   }
 * )
 */
class AddCartInputMeta extends InputTypePluginBase {
}

