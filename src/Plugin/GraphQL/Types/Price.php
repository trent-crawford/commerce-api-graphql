<?php

declare(strict_types = 1);

namespace Drupal\commerce_api_graphql\Plugin\GraphQL\Types;

use Drupal\graphql\Annotation\GraphQLType;
use Drupal\graphql\Plugin\GraphQL\Types\TypePluginBase;

/**
 * Defines the 'price' type.
 *
 * This is a dummy type to prevent errors thrown with GraphQL 3.x
 * due to the type being missing.
 *
 * @GraphQLType(
 *   id = "price",
 *   name = "price",
 * )
 */
class Price extends TypePluginBase {}
