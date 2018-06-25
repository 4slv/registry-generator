<?php

namespace RegistryGenerator;

/** Элмент реестра */
class RegistryElementClass extends RegistryElement
{
    /** @var string полное название класса (с неймспейсом) свойства */
    protected $propertyFullClassName;

    /**
     * @return string название класса (без неймспейса) свойства
     */
    public function getPropertyClassName(): string
    {
        $classParts = explode('\\', $this->propertyFullClassName);
        return array_pop($classParts);
    }

    /**
     * @param string $propertyFullClassName полное название класса (с неймспейсом) свойства
     * @return $this
     */
    public function setPropertyFullClassName(string $propertyFullClassName)
    {
        $this->propertyFullClassName = $propertyFullClassName;
        return $this;
    }

    /**
     * @return string полное название класса (с неймспейсом) свойства
     */
    public function getPropertyFullClassName(): string
    {
        return $this->propertyFullClassName;
    }


}