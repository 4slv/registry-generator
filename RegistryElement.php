<?php

namespace RegistryGenerator;

/** Элмент реестра */
class RegistryElement
{
    /** @var string полное название класса (с неймспейсом) свойства */
    protected $propertyFullClassName;

    /** @var string название свойства */
    protected $propertyName;

    /** @var string комментарий свойства */
    protected $propertyComment;

    /** @var RegistryElementParameter[] список параметров элемента реестра */
    protected $parameterList = [];

    /**
     * @return string название свойства
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @param string $propertyName название свойства
     * @return $this
     */
    public function setPropertyName(string $propertyName)
    {
        $this->propertyName = $propertyName;
        return $this;
    }

    /**
     * @return string комментарий свойства
     */
    public function getPropertyComment()
    {
        return $this->propertyComment;
    }

    /**
     * @param string $propertyComment комментарий свойства
     * @return $this
     */
    public function setPropertyComment(string $propertyComment)
    {
        $this->propertyComment = $propertyComment;
        return $this;
    }

    /**
     * @return RegistryElementParameter[] список параметров элемента реестра
     */
    public function getParameterList(): array
    {
        return $this->parameterList;
    }

    /**
     * @param RegistryElementParameter[] $parameterList список параметров элемента реестра
     * @return $this
     */
    public function setParameterList(array $parameterList)
    {
        $this->parameterList = $parameterList;
        return $this;
    }

    /**
     * @return string полное название класса (с неймспейсом) свойства
     */
    public function getPropertyFullClassName(): string
    {
        return $this->propertyFullClassName;
    }

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


}