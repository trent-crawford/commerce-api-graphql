<?php

declare(strict_types = 1);

namespace Drupal\commerce_api_graphql\Plugin\GraphQL\Types;

use Drupal\graphql\Annotation\GraphQLType;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Types\TypePluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Defines the 'tax_number' type
 *
 * This is a dummy type to prevent errors thrown with GraphQL 3.x
 * due to the type being missing.
 *
 * @GraphQLType(
 *   id = "tax_number",
 *   name = "tax_number",
 * )
 */
class TaxNumber extends TypePluginBase {

  /**
   * {@inheritdoc}
   */
  public function applies($object, ResolveContext $context, ResolveInfo $info) {
    return FALSE;
  }
}
