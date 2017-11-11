<?php

namespace EurekaClient\Instance;

/**
 * Class Metadata
 *
 * @package EurekaClient\Instance
 */
class Metadata extends Parameters {

  /**
   * @param string $key
   * @param mixed $value
   *
   * @return $this
   */
  public function set($key, $value) {
    return parent::set($key, $value);
  }

}
