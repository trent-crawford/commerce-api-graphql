<?php

declare(strict_types = 1);

namespace Drupal\commerce_api_graphql\Plugin\GraphQL\InputTypes;

use Drupal\graphql\Plugin\GraphQL\InputTypes\InputTypePluginBase;

/**
 * The input type for article mutations.
 *
 * @GraphQLInputType(
 *   id = "add_cart_input",
 *   secure = false,
 *   name = "AddCartInput",
 *   fields = {
 *     "commerceCartToken" = "String!",
 *     "entityType" = "String!",
 *     "id" = "String!",
 *     "store" = {
 *        "type": "String",
 *        "nullable": "TRUE"
 *      },
 *     "meta" =  "AddCartInputMeta!"
 *   }
 * )
 */
class AddCartInput extends InputTypePluginBase {
}

