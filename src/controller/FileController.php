<?php

namespace PWBox\controller;

/**
 *
 */
class FileController
{

  protected $container;

  function __construct(ContainerInterface $container)
  {
    $this->container = $container;
  }
}
