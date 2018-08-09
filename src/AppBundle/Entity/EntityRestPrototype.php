<?php
namespace AppBundle\Entity;
use Symfony\Component\HttpFoundation\Request;

abstract class EntityRestPrototype {
    abstract public function bindRequestParams(Request $request);
}