<?php

namespace RegistryGenerator;

/** Параметр элемента реестра */
class RegistryElementParameter
{
    /** @var string название параметра */
    protected $name;

    /** @var string значение параметра */
    protected $value;

    /** @var string полное название класса (с неймспейсом) параметра свойства реестра */
    protected $fullClassName;

    /**
     * @return string название параметра
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name название параметра
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string значение параметра
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value значение параметра
     * @return $this
     */
    public function setValue(string $value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string полное название класса (с неймспейсом) параметра свойства реестра
     */
    public function getFullClassName(): string
    {
        return $this->fullClassName;
    }

    /**
     * @param string $fullClassName полное название класса (с неймспейсом) параметра свойства реестра
     * @return $this
     */
    public function setFullClassName(string $fullClassName)
    {
        $this->fullClassName = $fullClassName;
        return $this;
    }

    /**
     * @return string название класса (без неймспейса) параметра свойства реестра
     */
    public function getClassName(): string
    {
        $classParts = explode('\\', $this->fullClassName);
        return array_pop($classParts);
    }

}