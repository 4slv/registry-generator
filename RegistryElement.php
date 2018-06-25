<?php


namespace RegistryGenerator;


abstract class RegistryElement
{
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

}