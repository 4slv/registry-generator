<?php

namespace RegistryGenerator;

use Slov\Helper\FileHelper;
use Slov\Helper\StringHelper;

/** Генератор кода класса реестра */
class RegistryGenerator
{
    use TemplateContentGetter;

    /** @var string путь к папке проекта */
    protected $projectPath;

    /** @var string относительный путь к папке в которой будет сгенерирован код реестра */
    protected $registryCodeRelativePath;

    /** @var string пространство имён */
    protected $namespace;

    /** @var string коментарий класса реестра */
    protected $classComment;

    /** @var string название класса реестра */
    protected $className;

    /** @var RegistryElement[] список элементов реестра */
    protected $registryElementList = [];

    /**
     * @return string путь к папке проекта
     */
    public function getProjectPath(): string
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
     * @return string относительный путь к папке в которой будет сгенерирован код реестра
     */
    public function getRegistryCodeRelativePath(): string
    {
        return $this->registryCodeRelativePath;
    }

    /**
     * @param string $registryCodeRelativePath относительный путь к папке в которой будет сгенерирован код реестра
     * @return $this
     */
    public function setRegistryCodeRelativePath(string $registryCodeRelativePath)
    {
        $this->registryCodeRelativePath = $registryCodeRelativePath;
        return $this;
    }

    /**
     * @return string пространство имён
     */
    public function getNamespace(): string
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
     * @return string коментарий класса реестра
     */
    public function getClassComment(): string
    {
        return $this->classComment;
    }

    /**
     * @param string $classComment коментарий класса реестра
     * @return $this
     */
    public function setClassComment(string $classComment)
    {
        $this->classComment = $classComment;
        return $this;
    }

    /**
     * @return string название класса реестра
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className название класса реестра
     * @return $this
     */
    public function setClassName(string $className)
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @return RegistryElement[]
     */
    public function getRegistryElementList(): array
    {
        return $this->registryElementList;
    }

    /**
     * @param RegistryElement[] $registryElementList
     * @return $this
     */
    public function setRegistryElementList(array $registryElementList)
    {
        $this->registryElementList = $registryElementList;
        return $this;
    }

    /**
     * @return string полный путь к папке с реестрами
     */
    protected function getRegistryDirectoryPath()
    {
        return
            $this->getProjectPath().
            DIRECTORY_SEPARATOR.
            $this->getRegistryCodeRelativePath();
    }

    /**
     * @return string полный путь к классу
     */
    protected function getRegistryClassPath()
    {
        return
            $this->getRegistryDirectoryPath().
            DIRECTORY_SEPARATOR.
            $this->getClassName(). '.php';
    }

    /**
     * @return string контент сгенерированного класса
     */
    protected function getClassContent()
    {
        return StringHelper::replacePatterns(
            $this->getTemplateRegistryClass(),
            [
                '%namespace%' => $this->getNamespace(),
                '%useClasses%' => $this->getUseClassesContent(),
                '%classComment%' => $this->formatComment($this->getClassComment()),
                '%className%' => $this->getClassName(),
                '%classProperties%' => $this->getPropertiesContent(),
                '%classFunctions%' => $this->getFunctionsListContent()
            ]
        );
    }

    /**
     * @return string контент подключаемых классов
     */
    protected function getUseClassesContent()
    {
        $useClassesList = [];
        foreach ($this->getRegistryElementList() as $registryElement) {
            $fullClassName = $registryElement->getPropertyFullClassName();
            $useClassesList[$fullClassName] = "use $fullClassName;";
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
     * @return string контент свойств класса
     */
    protected function getPropertiesContent()
    {
        $propertiesContent = '';
        foreach ($this->getRegistryElementList() as $registryElement) {
            $propertiesContent .= $this->getPropertyContent($registryElement);
        }
        return $propertiesContent;
    }

    /**
     * @param RegistryElement $registryElement элемент реестра
     * @return string контент свойства класса
     */
    protected function getPropertyContent(RegistryElement $registryElement)
    {
        return StringHelper::replacePatterns(
            $this->getTemplateRegistryProperty(),
            [
                '%propertyClass%' => $registryElement->getPropertyClassName(),
                '%propertyComment%' => $registryElement->getPropertyComment(),
                '%propertyName%' => $registryElement->getPropertyName()
            ]
        );
    }

    /**
     * @return string контент функций доступа к свойствам реестра
     */
    protected function getFunctionsListContent()
    {
        $functionsListContent = '';
        foreach ($this->getRegistryElementList() as $registryElement) {
            $functionsListContent .= $this->getFunctionsContent($registryElement);
        }
        return $functionsListContent;
    }

    /**
     * @param RegistryElement $registryElement
     * @return string контент функций доступа к свойству реестра
     */
    protected function getFunctionsContent(RegistryElement $registryElement)
    {
        return StringHelper::replacePatterns(
            $this->getTemplateRegistryFunction(),
            [
                '%propertyClass%' => $registryElement->getPropertyClassName(),
                '%propertyComment%' => $registryElement->getPropertyComment(),
                '%PropertyName%' => ucfirst($registryElement->getPropertyName()),
                '%propertyName%' => $registryElement->getPropertyName(),

            ]
        );
    }

    /** генерация кода класса реестра */
    public function generate()
    {
        $registryDirectory = $this->getRegistryDirectoryPath();
        FileHelper::createDirectory($registryDirectory);

        file_put_contents(
            $this->getRegistryClassPath(),
            $this->getClassContent()
        );
    }
}