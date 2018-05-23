<?php

namespace RegistryGenerator;


use Slov\Helper\FileHelper;
use Slov\Helper\StringHelper;

class RegistryFactoryGenerator
{
    use TemplateContentGetter;

    /** @var string путь к папке проекта */
    protected $projectPath;

    /** @var string относительный путь к папке в которой будет сгенерирован код фабрики реестра */
    protected $registryFactoryCodeRelativePath;

    /** @var string пространство имён */
    protected $namespace;

    /** @var string полное (с неймспейсом) название класса реестра */
    protected $registryFullClassName;

    /** @var string название класса фабрики реестра */
    protected $factoryClassName;

    /** @var string коментарий класса фабрики реестра */
    protected $factoryClassComment;

    /** @var RegistryElement[] список элементов реестра */
    protected $registryElementList = [];

    /**
     * @return string путь к папке проекта
     */
    protected function getProjectPath(): string
    {
        return $this->projectPath;
    }

    /**
     * @param string $projectPath путь к папке проекта
     * @return $this
     */
    public function setProjectPath(string $projectPath)
    {
        $this->projectPath = $projectPath;
        return $this;
    }

    /**
     * @return string относительный путь к папке в которой будет сгенерирован код фабрики реестра
     */
    protected function getRegistryFactoryCodeRelativePath(): string
    {
        return $this->registryFactoryCodeRelativePath;
    }

    /**
     * @param string $registryFactoryCodeRelativePath относительный путь к папке в которой будет сгенерирован код фабрики реестра
     * @return $this
     */
    public function setRegistryFactoryCodeRelativePath(string $registryFactoryCodeRelativePath)
    {
        $this->registryFactoryCodeRelativePath = $registryFactoryCodeRelativePath;
        return $this;
    }

    /**
     * @return string пространство имён
     */
    protected function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace пространство имён
     * @return $this
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return string полное (с неймспейсом) название класса реестра
     */
    protected function getRegistryFullClassName(): string
    {
        return $this->registryFullClassName;
    }

    /**
     * @param string $registryFullClassName полное (с неймспейсом) название класса реестра
     * @return $this
     */
    public function setRegistryFullClassName(string $registryFullClassName)
    {
        $this->registryFullClassName = $registryFullClassName;
        return $this;
    }

    /**
     * @return string название класса реестра
     */
    protected function getRegistryClassName()
    {
        $registryClassPartList = explode(
            '\\',
            $this->registryFullClassName
        );
        return array_pop($registryClassPartList);
    }

    /**
     * @return string название класса фабрики реестра
     */
    protected function getFactoryClassName(): string
    {
        return $this->factoryClassName;
    }

    /**
     * @param string $factoryClassName название класса фабрики реестра
     * @return $this
     */
    public function setFactoryClassName(string $factoryClassName)
    {
        $this->factoryClassName = $factoryClassName;
        return $this;
    }

    /**
     * @return string коментарий класса фабрики реестра
     */
    protected function getFactoryClassComment(): string
    {
        return $this->factoryClassComment;
    }

    /**
     * @param string $factoryClassComment коментарий класса фабрики реестра
     * @return $this
     */
    public function setFactoryClassComment(string $factoryClassComment)
    {
        $this->factoryClassComment = $factoryClassComment;
        return $this;
    }

    /**
     * @return RegistryElement[] список элементов реестра
     */
    protected function getRegistryElementList(): array
    {
        return $this->registryElementList;
    }

    /**
     * @param RegistryElement[] $registryElementList список элементов реестра
     * @return $this
     */
    public function setRegistryElementList(array $registryElementList)
    {
        $this->registryElementList = $registryElementList;
        return $this;
    }

    /**
     * @return string полный путь к папке с фабрикой реестра реестрами
     */
    protected function getRegistryFactoryDirectoryPath()
    {
        return
            $this->getProjectPath().
            DIRECTORY_SEPARATOR.
            $this->getRegistryFactoryCodeRelativePath();
    }

    /**
     * @return string полный путь к классу
     */
    protected function getRegistryFactoryClassPath()
    {
        return
            $this->getRegistryFactoryDirectoryPath().
            DIRECTORY_SEPARATOR.
            $this->getFactoryClassName(). '.php';
    }

    protected function getClassContent()
    {
        return StringHelper::replacePatterns(
            $this->getTemplateRegistryFactoryClass(),
            [
                '%namespace%' => $this->getNamespace(),
                '%useClasses%' => $this->getUseClassesContent(),
                '%factoryClassComment%' => $this->formatComment($this->getFactoryClassComment()),
                '%factoryClassName%' => $this->getFactoryClassName(),
                '%registryClassName%' => $this->getRegistryClassName(),
                '%setRegistryProperties%' => $this->getSetRegistryPropertiesContent(),
            ]
        );
    }

    /**
     * @return string контент подключаемых классов
     */
    protected function getUseClassesContent()
    {
        $classList = [$this->getRegistryFullClassName()];
        $useClassesList = [];

        foreach ($this->getRegistryElementList() as $registryElement) {
            $classList[] = $registryElement->getPropertyFullClassName();
            foreach ($registryElement->getParameterList() as $parameter)
            {
                $classList[] = $parameter->getFullClassName();
            }
        }

        foreach($classList as $class) {
            $useClassesList[$class] = "use $class;";
        }
        return implode("\n", $useClassesList);

    }

    /**
     * @param string $commentText текст комментария
     * @return string комменарий к элементу перечисляемого типа
     */
    protected function formatComment($commentText)
    {
        return strlen($commentText) > 0 ? '/** '. $commentText. ' */' : '';
    }

    /**
     * @return string контент установки свойств реестра
     */
    protected function getSetRegistryPropertiesContent()
    {
        $registryPropertiesContent = '';
        foreach($this->getRegistryElementList() as $registryElement)
        {
            $registryPropertiesContent .=
                $this->getSetRegistryPropertyContent($registryElement);
        }
        return $registryPropertiesContent;
    }

    /**
     * @param RegistryElement $registryElement элемент реестра
     * @return string контент установки свойства реестра
     */
    protected function getSetRegistryPropertyContent(RegistryElement $registryElement)
    {
        return StringHelper::replacePatterns(
            $this->getTemplateRegistryFactorySetProperty(),
            [
                '%propertyName%' => $registryElement->getPropertyName(),
                '%propertyClass%' => $registryElement->getPropertyClassName(),
                '%PropertyName%' => ucfirst($registryElement->getPropertyName()),
                '%setPropertyParameters%' => $this->getSetPropertyParametersContent($registryElement)
            ]
        );
    }

    /**
     * @param RegistryElement $registryElement элемент реестра
     * @return string контент установки параметров свойства реестра
     */
    protected function getSetPropertyParametersContent(RegistryElement $registryElement)
    {
        $propertyParametersContent = '';
        foreach ($registryElement->getParameterList() as $parameter) {
            $propertyParametersContent .= $this->getSetPropertyParameterContent($registryElement, $parameter);
        }
        return $propertyParametersContent;
    }

    /**
     * @param RegistryElement $registryElement элемент реестра
     * @param RegistryElementParameter $registryElementParameter параметр элемента реестра
     * @return string контент установки параметра свойства реестра
     */
    protected function getSetPropertyParameterContent(
        RegistryElement $registryElement, RegistryElementParameter $registryElementParameter
    )
    {
        return StringHelper::replacePatterns(
            $this->getTemplateRegistryFactorySetPropertyParameter(),
            [
                '%propertyName%' => $registryElement->getPropertyName(),
                '%PropertyParameterName%' => ucfirst($registryElementParameter->getName()),
                '%parameterValue%' => $registryElementParameter->getValue()
            ]
        );
    }

    /** генерация кода класса фабрики реестра */
    public function generate()
    {
        $registryFactoryDirectory = $this->getRegistryFactoryDirectoryPath();
        FileHelper::createDirectory($registryFactoryDirectory);

        file_put_contents(
            $this->getRegistryFactoryClassPath(),
            $this->getClassContent()
        );
    }
}