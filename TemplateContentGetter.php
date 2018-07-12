<?php

namespace RegistryGenerator;


trait TemplateContentGetter
{
    /**
     * @return string шаблон класса
     */
    protected function getTemplateRegistryClass()
    {
        return $this->getTemplateContent(
            'registryClass.txt'
        );
    }

    /**
     * @return string шаблон свойства реестра
     */
    protected function getTemplateRegistryProperty()
    {
        return $this->getTemplateContent(
            'registryProperty.txt'
        );
    }

    /**
     * @return string шаблон функций доступа к свойствам реестра
     */
    protected function getTemplateRegistryFunction()
    {
        return $this->getTemplateContent(
            'registryFunction.txt'
        );
    }

    /**
     * @return string шаблон класса фабрики
     */
    protected function getTemplateRegistryFactoryClass()
    {
        return $this->getTemplateContent(
            'registryFactoryClass.txt'
        );
    }

    /**
     * @return string шаблон установки свойства реестра
     */
    protected function getTemplateRegistryFactorySetProperty()
    {
        return $this->getTemplateContent(
            'registryFactorySetProperty.txt'
        );
    }

    /**
     * @return string шаблон установки параметра свойства реестра
     */
    protected function getTemplateRegistryFactorySetPropertyParameter()
    {
        return $this->getTemplateContent(
            'registryFactorySetPropertyParameter.txt'
        );
    }

    /**
     * @param string $templateFileName имя файла шаблона
     * @return string содержимое шаблона
     */
    protected function getTemplateContent($templateFileName)
    {
        return file_get_contents(
            __DIR__ .
            DIRECTORY_SEPARATOR.
            'Templates'.
            DIRECTORY_SEPARATOR.
            $templateFileName
        );
    }

    /**
     * @return string шаблон создания класса параметра
     */
    protected function getTemplateRegistryFactorySetPropertyInitializationMethodConstruct()
    {
        return $this->getTemplateContentRegistryFactorySetProperty(
            'initializationMethodConstruct.txt'
        );
    }

    /**
     * @return string шаблон создания класса параметра
     */
    protected function getTemplateRegistryFactorySetPropertyInitializationMethodStatic()
    {
        return $this->getTemplateContentRegistryFactorySetProperty(
            'initializationMethodStatic.txt'
        );
    }

    /**
     * @return string шаблон создания класса параметра
     */
    protected function getTemplateRegistryFactorySetPropertyInitializationMethodNotStatic()
    {
        return $this->getTemplateContentRegistryFactorySetProperty(
            'initializationMethodNotStatic.txt'
        );
    }

    /**
     * @param string $templateFileName имя файла шаблона
     * @return string содержимое шаблона
     */
    protected function getTemplateContentRegistryFactorySetProperty($templateFileName)
    {
        return file_get_contents(implode(DIRECTORY_SEPARATOR,[
            __DIR__,
            'Templates',
            'RegistryFactorySetProperty',
            $templateFileName
        ]));
    }
}