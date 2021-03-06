<?php

namespace Drupal\commerce_gift;

use Drupal\commerce_gift\Entity\GiftAttributeInterface;

/**
 * Manages attribute fields.
 *
 * Attribute fields are entity reference fields storing values of a specific
 * attribute on the gift variation.
 */
interface GiftAttributeFieldManagerInterface {

  /**
   * Gets the attribute field definitions.
   *
   * The field definitions are not ordered.
   * Use the field map when the field order is important.
   *
   * @param string $variation_type_id
   *   The gift variation type ID.
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface[]
   *   The attribute field definitions, keyed by field name.
   */
  public function getFieldDefinitions($variation_type_id);

  /**
   * Gets a map of attribute fields across variation types.
   *
   * @param string $variation_type_id
   *   (Optional) The gift variation type ID.
   *   When given, used to filter the returned maps.
   *
   * @return array
   *   If a gift variation type ID was given, a list of maps.
   *   Otherwise, a list of maps grouped by gift variation type ID.
   *   Each map is an array with the following keys:
   *   - attribute_id: The attribute id;
   *   - field_name: The attribute field name.
   *   The maps are ordered by the weight of the attribute fields on the
   *   default gift variation form display.
   */
  public function getFieldMap($variation_type_id = NULL);

  /**
   * Clears the attribute field map and definition caches.
   */
  public function clearCaches();

  /**
   * Creates an attribute field for the given attribute.
   *
   * @param \Drupal\commerce_gift\Entity\GiftAttributeInterface $attribute
   *   The gift attribute.
   * @param string $variation_type_id
   *   The gift variation type ID.
   */
  public function createField(GiftAttributeInterface $attribute, $variation_type_id);

  /**
   * Checks whether the attribute field for the given attribute can be deleted.
   *
   * An attribute field is no longer deletable once it has data.
   *
   * @param \Drupal\commerce_gift\Entity\GiftAttributeInterface $attribute
   *   The gift attribute.
   * @param string $variation_type_id
   *   The gift variation type ID.
   *
   * @throws \InvalidArgumentException
   *   Thrown when the attribute field does not exist.
   *
   * @return bool
   *   TRUE if the attribute field can be deleted, FALSE otherwise.
   */
  public function canDeleteField(GiftAttributeInterface $attribute, $variation_type_id);

  /**
   * Deletes the attribute field for the given attribute.
   *
   * @param \Drupal\commerce_gift\Entity\GiftAttributeInterface $attribute
   *   The gift attribute.
   * @param string $variation_type_id
   *   The gift variation type ID.
   */
  public function deleteField(GiftAttributeInterface $attribute, $variation_type_id);

}
