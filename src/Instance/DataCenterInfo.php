<?php

namespace EurekaClient\Instance;

class DataCenterInfo extends Parameters {

  public function setName($name) {
    return $this->set('name', $name);
  }

  public function setClass($class) {
    return $this->set('@class', $class);
  }

  public function setMetadata(Metadata $metadata) {
    return $this->set('metadata', $metadata->export());
  }

}
