<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Xml helper class.
 *
 * @author     Vinnovera (lilleman@vinnovera.se, jakob@vinnovera.se)
 */
class xml {

  /**
   * Creates a DOMNode or DOMDocument of your array or SQL
   *
   * Examples:
   * ===============================================================
   * Simple example of the two different return values.
   * As DOMDocument:
   * <?php
   * $doc = xml::toXML(array('root'=>array('fnupp'=>'dah')));
   * $doc->formatOutput = true;
   *
   * echo $doc->saveXML();
   * ?>
   *
   *
   * As DOMNode:
   * <?php
   * $doc = new DOMDocument();
   * $container = $doc->appendChild($doc->createElement('root'));
   *
   * xml::toXML(array('fnupp'=>'dah'), $container);
   *
   * echo $doc->saveXML();
   * ?>
   *
   * ===============================================================
   * An SQL-statement, will be grouped like this:
   * SQL-table (users):
   * ID | name  | address
   * -------------------------
   * 1  | Smith | Nowhere 2
   * 2  | Doe   | Somestreet 4
   *
   * $data = 'SELECT * FROM users';
   *
   * will be transformed to:
   *
   * $data = array(
   *  0 => array(
   *    'ID' => '1',
   *    'name' => 'Smith',
   *    'address' => 'Nowhere 2',
   *  ),
   *  1 => array(
   *    'ID' => '2',
   *    'name' => 'Doe',
   *    'address' => 'Somestreet 4',
   *  )
   * )
   * IMPORTANT! This needs Kohana database to be configured
   * ===============================================================
   * How the $container works:
   * xml::toXML(array('fnupp' => 'dah'))
   * will output:
   * <fnupp>dah</fnupp>
   *
   * xml::toXML(array('fnupp' => 'dah'), 'root')
   * will output:
   * <root>
   *   <fnupp>dah</fnupp>
   * </root>
   *
   * The $container can also be a DOMNode, see the examples with return values for more info
   * ===============================================================
   * How the $group works
   * IMPORTANT! $group requires $container
   *
   * SQL-table (users):
   * ID | name  | address
   * -------------------------
   * 1  | Smith | Nowhere 2
   * 2  | Doe   | Somestreet 4
   *
   * xml::toXML('SELECT * FROM users', 'users', 'user');
   *
   * will output:
   *
   *  <users>
   *    <user>
   *      <ID>1</ID>
   *      <name>Smith</name>
   *      <address>Nowhere 2</address>
   *    </user>
   *    <user>
   *      <ID>2</ID>
   *      <name>Doe</name>
   *      <address>Somestreet 4</address>
   *    </user>
   *  </users>
   * ===============================================================
   * How the $attributes works
   * xml::toXML(array('user'=>array('id'=>2,'name'=>'nisse'),null,null,array('id'));
   *
   * will output:
   *  <user id="2">
   *    <name>nisse</name>
   *  </user>
   *
   * This will work no matter how deep in the structure the attribute is
   *
   * Alternative to this is to begin the element name with "@", in this case the data would then be:
   * array('user'=>array('@id'=>2,'name'=>'nisse')
   * ===============================================================
   * How $textValues works
   * xml::toXML(array('user'=>array('id'=>2,'name'=>'nisse'),null,null,array('id'),array('name'));
   *
   * will output:
   *  <user id="2">nisse</user>
   *
   * This will also work no matter the depth of the element
   *
   * Alternative to this is to begin the element name with "$", in this case the data would then be:
   * array('user'=>array('id'=>2,'$name'=>'nisse')
   * ===============================================================
   * How the $alterCode works
   * This is very cool! For each element, you can execute a snippet of code on its data. For example:
   * $data = array(
   *    'blubb' => 'bla',
   *    'strangeness' => 5,
   * )
   *
   * xml::toXML($data, 'root', null, array(), array(), array(), array('strangeness' => '$str = $name . ' is at level ' . $value; return $str;'));
   *
   * will return:
   *  <root>
   *    <blubb>bla</blubb>
   *    <strangeness>strangeness is at level 5</strangeness>
   *  </root>
   *
   * $name and $value is loaded with the element name and element value.
   * The code snippet will work exactly as a function, hence the "return" in the example.
   *
   * To just use an existing function, this is the way to go:
   * xml::toXML($data, 'root', null, array(), array(), array('strangeness' => 'return substr($blubb,0,2);'));
   * (Will change "bla" to "bl" in the "blubb"-element)
   * ===============================================================
   * Rule for making several identical elements
   *
   * $data = array(
   *    '1blubb' => 233,
   *    '2blubb' => 993,
   * )
   *
   * xml::toXML($data, 'root');
   *
   * will output:
   *  <root>
   *    <blubb>233</blubb>
   *    <blubb>993</blubb>
   *  </root>
   *
   * $data = array(
   *    1 => 233,
   *    2 => 993,
   * )
   *
   * xml::toXML($data, 'root');
   *
   * will output:
   *  <root>233993</root>
   *
   *
   *
   * @param str or arr $data - if string, it will be treated as an SQL statement
   * @param obj $container
   * @param str $group - Container must be provided for this to work
   * @param arr $attributes - Array of keys that should always be treated as attributes
   * @param arr $textValues - Array of keys that should always have their value as value to the parent, ignoring the key
   * @param arr $xmlFragments - Array of keys that should always have their value interpreted as xml fragments
   * @param arr $alterCode - keys that should have their values altered by the code given as array value
   * @return obj - DOMElement
   */
  public static function toXML($data, $container = null, $group = null, $attributes = array(), $textValues = array(), $xmlFragments = array(), $alterCode = array()) {
    if (is_string($attributes)) {
      $attributes = array($attributes);
    }

    if (is_string($textValues)) {
      $textValues = array($textValues);
    }

    // Make sure the data is always an array
    if (is_string($data)) {
      // SQL statement - make it an array
      $db = Database::instance();
      $result = $db->query($data);
      $data = $result->result_array(false);
    } elseif (!is_array($data)) {
      // Neither string or array. Humbug!
      return false;
    }

    if ($container === null) {
      $DOMDocument = new DOMDocument();
    } elseif (is_string($container)) {
      $DOMDocument = new DOMDocument();
      $altContainer = $DOMDocument->appendChild($DOMDocument->createElement($container));
    } else {
      $DOMDocument = $container->ownerDocument;
    }

    foreach ($data as $key => $value) {

      // Fix the key to a tag
      $tag = null;
      $elementAttributes = array();
      foreach (explode(' ',$key) as $part) {
        if (!$tag) {
          $tag = $part;
          while (preg_match('/^[0-9]/',$tag)) {
            // The first character can not be a numeric char
            // So we strip them off
            $tag = substr($tag,1);
          }
        } else {
          // This should be an attribute
          $attributeName = null;
          $attributeValue = null;
          list($attributeName, $attributeValue) = explode('=', $part);
          if (($attributeName) && ($attributeValue)) {
            // Both must exist to make a valid attribute

            // Set the element attributes, strip " or ' from beginning and end of attribute value
            $elementAttributes[$attributeName] = substr($attributeValue, 1, strlen($attributeValue) - 2);
          }
        }
      }

      if ($container === null && !isset($altContainer)) {
        // If we have no container, the tag must be the root element
        if ($tag == '') {
          // And as such, it must be a valid tag
          $tag = 'root';
        }
        $DOMElement = $DOMDocument->createElement($tag);
        $DOMDocument->appendChild($DOMElement);
        if (!is_array($value)) {
          if (in_array($key,array_keys($alterCode))) {
            $funcName = create_function('$value,$name', $alterCode[$key]);
            $value = $funcName($value, $key);
          }
          $DOMElement->appendChild($DOMDocument->createTextNode($value));
        } else {
          $DOMElement = xml::toXML($value, $DOMElement, null, $attributes, $textValues, $xmlFragments, $alterCode);
        }
      } else {
        // Grouping is activated, lets group this up
        if (isset($group)) {
          if (isset($altContainer)) {
            $groupElement = $altContainer->appendChild($DOMDocument->createElement($group));
          } else {
            $groupElement = $container->appendChild($DOMDocument->createElement($group));
          }
        }

        // We have a container, create everything in it
        if ($tag != '') {
          // This is a tag, parse and create

          if (substr($tag, 0, 1) == '@' || in_array($tag, $attributes)) {
            // This is an attribute

            $tag = str_replace('@','',$tag);
            $attribute = $DOMDocument->createAttribute($tag);
            if (in_array($tag,array_keys($alterCode))) {
              $funcName = create_function('$value,$name', $alterCode[$tag]);
              $value = $funcName($value, $tag);
            }
            $attribute->appendChild($DOMDocument->createTextNode($value));

            if (isset($groupElement)) {
              $groupElement->appendChild($attribute);
            } elseif (isset($altContainer)) {
              $altContainer->appendChild($attribute);
            } else {
              $container->appendChild($attribute);
            }

          } elseif (substr($tag, 0, 1) == '$' || in_array($tag, $textValues)) {
            // This tag should be ignored, and its value should be inline text instead
            if (in_array($tag,array_keys($alterCode))) {
              $funcName = create_function('$value,$name', $alterCode[$tag]);
              $value = $funcName($value, $tag);
            }
            if (isset($groupElement)) {
              $groupElement->appendChild($DOMDocument->createTextNode($value));
            } elseif (isset($altContainer)) {
              $altContainer->appendChild($DOMDocument->createTextNode($value));
            } else {
              $container->appendChild($DOMDocument->createTextNode($value));
            }
          } elseif (substr($tag, 0, 1) == '?' || in_array($tag, $xmlFragments)) {
            // This tag should be interpreted as an XML fragment
            $tag = str_replace('?','',$tag);
            $DOMElement = $DOMDocument->createElement($tag);

            if (in_array($tag,array_keys($alterCode))) {
              $funcName = create_function('$value,$name', $alterCode[$tag]);
              $value = $funcName($value, $tag);
            }

            $fragment = $DOMDocument->createDocumentFragment();
            $fragment->appendXML($value);
            $DOMElement->appendChild($fragment);

            if (isset($groupElement)) {
              $groupElement->appendChild($DOMElement);
            } elseif (isset($altContainer)) {
              $altContainer->appendChild($DOMElement);
            } else {
              $container->appendChild($DOMElement);
            }

          } else {
            // This is just a normal tag

            $DOMElement = $DOMDocument->createElement($tag);
            if (in_array($tag,array_keys($alterCode))) {
              $funcName = create_function('$value,$name', $alterCode[$tag]);
              $value = $funcName($value, $tag);
            }
            if (!is_array($value)) {
              $DOMElement->appendChild($DOMDocument->createTextNode($value));
            } else {
              $DOMElement = xml::toXML($value, $DOMElement, null, $attributes, $textValues, $xmlFragments, $alterCode);
            }

            if (isset($groupElement)) {
              $groupElement->appendChild($DOMElement);
            } elseif (isset($altContainer)) {
              $altContainer->appendChild($DOMElement);
            } else {
              $container->appendChild($DOMElement);
            }

          }

        } else {
          /**
           * When the tag is an empty string (can also be cuz of the array being non-associative i.e. numbers as keys),
           * it should fold down to the above tag as inline text:
           * array(
           *    'foo' => array('blubb')
           * )
           * produces:
           * <foo>blubb</foo>
           */
          if (!is_array($value)) {
            // This is a simple string value, just add it
            if (isset($groupElement)) {
              $groupElement->appendChild($DOMDocument->createTextNode($value));
            } elseif (isset($altContainer)) {
              $altContainer->appendChild($DOMDocument->createTextNode($value));
            } else {
              $container->appendChild($DOMDocument->createTextNode($value));
            }
          } else {
            // This is children-stuff :)
            if (isset($groupElement)) {
              $groupElement = xml::toXML($value, $groupElement, null, $attributes, $textValues, $xmlFragments, $alterCode);
            } elseif (isset($altContainer)) {
              $altContainer = xml::toXML($value, $altContainer, null, $attributes, $textValues, $xmlFragments, $alterCode);
            } else {
              $container = xml::toXML($value, $container, null, $attributes, $textValues, $xmlFragments, $alterCode);
            }
          }
        }
      }

      // Add the attributes
      foreach ($elementAttributes as $attribute => $value) {
        $attribute = $DOMElement->appendChild($DOMDocument->createAttribute($attribute));
        $attribute->appendChild($DOMDocument->createTextNode($value));
      }

    }

    if (is_object($container)) {
      return $container;
    } else {
      return $DOMDocument;
    }

  }

  /**
   * Load an XML file and attach it to a DOMNode
   * Important! Just adds the data within the root-node in the XML document, not the root tag itself
   *
   * @param str $xmlFile
   * @param obj $DOMNode
   */
  public static function xmlFileToDOMNode($xmlFile, $DOMNode) {
    // Load a static XML file into a DOM Node

    $xml_inc = new DOMDocument;
    $xml_inc->resolveExternals    = true;
    $xml_inc->substituteEntities  = true;
    $xml_inc->preserveWhiteSpace  = false;
    $xml_inc->formatOutput        = true;

    $xml_inc->load(Kohana::find_file('xml', $xmlFile, true, 'xml'));

    foreach($xml_inc->documentElement->childNodes as $xml_child) {
      $xml_child = $DOMNode->ownerDocument->importNode($xml_child, true);
      $DOMNode->appendChild($xml_child);
    }
  }


}
