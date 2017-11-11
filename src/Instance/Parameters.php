<?php

namespace EurekaClient\Instance;

/**
 * Class Parameters
 *
 * @package EurekaClient\Instance
 */
abstract class Parameters {

  /**
   * @var array
   */
  private $parameters = [];

  /**
   * @param string $key
   * @param mixed $value
   *
   * @return $this
   */
  protected function set($key, $value) {
    $this->parameters[$key] = $value;

    return $this;
  }

  /**
   * @return array
   */
  public function export() {
    return $this->parameters;
  }

}
