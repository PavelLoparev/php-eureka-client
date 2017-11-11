<?php

namespace EurekaClient\Instance;

abstract class Parameters {

  private $parameters = [];

  protected function set($key, $value) {
    $this->parameters[$key] = $value;

    return $this;
  }

  public function export() {
    return $this->parameters;
  }

}
