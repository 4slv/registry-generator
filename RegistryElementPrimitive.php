<?php


namespace RegistryGenerator;


class RegistryElementPrimitive extends RegistryElement
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(?string $type)
    {
        $this->type = $type;
        return $this;
    }



}