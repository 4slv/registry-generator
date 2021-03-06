<?php

namespace RegistryGenerator;


use Slov\Helper\ClassHelper;
use Slov\Helper\FileHelper;
use Slov\Helper\StringHelper;

/** Генератор фабрики реестра */
class RegistryFactoryGenerator
{
    use TemplateContentGetter;

    /** @var string полный путь к родительскому классу */
    protected $extendsBy;

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

    /** @var RegistryElementClass[] список элементов реестра */
    protected $registryElementList = [];

    /**
     * @return string|null полный путь к родительскому классу
     */
    public function getExtendsBy(): ?string
    {
        return $this->extendsBy;
    }

    /**
     * @param string $extendsBy полный путь к родительскому классу
     * @return $this
     */
    public function setExtendsBy(?string $extendsBy)
    {
        $this->extendsBy = $extendsBy;
        return $this;
    }

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
     * @return RegistryElementClass[] список элементов реестра
     */
    protected function getRegistryElementList(): array
    {
        return $this->registryElementList;
    }

    /**
     * @param RegistryElementClass[] $registryElementList список элементов реестра
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
                '%classExtends%' => $this->getExtends(),
            ]
        );
    }

    /**
     * @return string наследование родительского класса
     */
    protected function getExtends()
    {
        $parentClassName = ClassHelper::shortName($this->getParentClassFullName());
        return empty($parentClassName) ? '' : 'extends '. $parentClassName;
    }

    /**
     * @return string полное название родителького класса (с неймспейсом)
     */
    protected function getParentClassFullName()
    {
        return ltrim(trim($this->getExtendsBy()), '\\');
    }

    /**
     * @return string контент подключаемых классов
     */
    protected function getUseClassesContent()
    {
        $classList = [$this->getRegistryFullClassName()];
        $useClassesList = [];
        $parentClass = $this->getParentClassFullName();
        if(strlen($parentClass)){
            $classList[] = $parentClass;
        }

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
     * @param RegistryElementClass $registryElement элемент реестра
     * @return string контент установки свойства реестра
     */
    protected function getSetRegistryPropertyContent(RegistryElementClass $registryElement)
    {
        return StringHelper::replacePatterns(
            $this->getTemplateRegistryFactorySetProperty(),
            [
                '%propertyName%' => $registryElement->getPropertyName(),
                '%initProperty%' => $this->initProperty($registryElement),
                '%PropertyName%' => ucfirst($registryElement->getPropertyName()),
                '%setPropertyParameters%' => $this->getSetPropertyParametersContent($registryElement)
            ]
        );
    }

    /**
     * @param RegistryElementClass $registryElement
     * @return string
     */
    protected function initProperty(RegistryElementClass $registryElement):string
    {
        $param['%propertyClass%']  = $registryElement->getPropertyClassName();
        switch (true){
            case is_null($registryElement->getRegistryElementClassInitializationMethod()):
            $template =  $this->getTemplateRegistryFactorySetPropertyInitializationMethodConstruct();
                break;
            case $registryElement->getRegistryElementClassInitializationMethod()->isStatic() === true:
                $template =  $this->getTemplateRegistryFactorySetPropertyInitializationMethodStatic();
                $param['%method%']  = $registryElement->getRegistryElementClassInitializationMethod()->getMethod();
                break;
            case $registryElement->getRegistryElementClassInitializationMethod()->isStatic() === false:
                $template =  $this->getTemplateRegistryFactorySetPropertyInitializationMethodNotStatic();
                $param['%method%']  = $registryElement->getRegistryElementClassInitializationMethod()->getMethod();
                break;
        }
        return StringHelper::replacePatterns($template,$param);
    }

    /**
     * @param RegistryElementClass $registryElement элемент реестра
     * @return string контент установки параметров свойства реестра
     */
    protected function getSetPropertyParametersContent(RegistryElementClass $registryElement)
    {
        $propertyParametersContent = '';
        foreach ($registryElement->getParameterList() as $parameter) {
            $propertyParametersContent .= $this->getSetPropertyParameterContent($registryElement, $parameter);
        }
        return $propertyParametersContent;
    }

    /**
     * @param RegistryElementClass $registryElement элемент реестра
     * @param RegistryElementParameter $registryElementParameter параметр элемента реестра
     * @return string контент установки параметра свойства реестра
     */
    protected function getSetPropertyParameterContent(
        RegistryElementClass $registryElement, RegistryElementParameter $registryElementParameter
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