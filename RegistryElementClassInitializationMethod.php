<?php


namespace RegistryGenerator;


class RegistryElementClassInitializationMethod
{
    /** @var  string */
    private $method;

    /** @var  bool */
    private $static = false;

    /**
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod(?string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function isStatic(): bool
    {
        return $this->static;
    }

    /**
     * @param bool $static
     * @return $this
     */
    public function setStatic(bool $static)
    {
        $this->static = $static;
        return $this;
    }


}