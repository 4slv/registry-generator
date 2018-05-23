# registry-generator
Содержит 2 класса:

**RegistryGenerator** - генерирует код реестра

**RegistryFactoryGenerator** - генерирует код для фабрики реестра

Пример использования **RegistryGenerator**:

```php
use RegistryGenerator\RegistryGenerator;

(new RegistryGenerator())
    ->setProjectPath($rootDir) # $rootDir - путь к папке проекта
    ->setRegistryCodeRelativePath($registryCodeRelativePath); # $registryCodeRelativePath - относительный путь
                                                              # к папке генерации кода реестра
    ->setClassName('CreditAccountRegistry') # название класса реестра
    ->setNamespace($registryNamespace) # пространство имён реестра
    ->setClassComment('Реестр кредитных счетов') # комментарий к классу реестра
    ->setRegistryElementList($creditAccountRegistryElementList) # список элементов реестра типа RegistryElement[]
    ->generate();
```

сгенерирует реестр вида:
```php
<?php
namespace Registry\Generate;

use Modules\Account\Account;

/** Реестр кредитных счетов */
class CreditAccountRegistry
{
    /** @var Account Счёт :: Основной долг */
    protected $principal;

    /** @var Account Счёт :: Проценты на основной долг */
    protected $interest;

    /** @return Account Счёт :: Основной долг */
    public function getPrincipal()
    {
        return $this->principal;
    }

    /** @param Account $principal Счёт :: Основной долг
     * @return $this */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;
        return $this;
    }

    /** @return Account Счёт :: Проценты на основной долг */
    public function getInterest()
    {
        return $this->interest;
    }

    /** @param Account $interest Счёт :: Проценты на основной долг
     * @return $this */
    public function setInterest($interest)
    {
        $this->interest = $interest;
        return $this;
    }


}
```

Пример использования **RegistryFactoryGenerator**:

```php
use Registry\Generate\CreditAccountRegistry;
use RegistryGenerator\RegistryFactoryGenerator;

(new RegistryFactoryGenerator())
    ->setProjectPath($rootDir) # $rootDir - путь к папке проекта
    ->setRegistryFactoryCodeRelativePath($registryFactoryCodeRelativePath); # $registryFactoryCodeRelativePath - относительный путь
                                                                            # к папке генерации кода фабрики реестра
    ->setRegistryFullClassName(CreditAccountRegistry::class) # полное название  класса реестра (с неймспейсом)
    ->setFactoryClassName('CreditAccountRegistryFactory') # название класса фабрики реестра
    ->setNamespace($factoryNamespace) # пространство имён
    ->setFactoryClassComment('Фабрика реестра кредитных счетов') # комментарий к классу фабрики реестра
    ->setRegistryElementList($creditAccountRegistryElementList) # список элементов реестра типа RegistryElement[]
    ->generate();
```

сгенерирует фабрику для реестра **RegistryGenerator** вида:
```php
<?php
namespace Factory\Generate;

use Registry\Generate\CreditAccountRegistry;
use Modules\Account\Account;
use Enum\Generate\AccountType;

/** Фабрика реестра кредитных счетов */
class CreditAccountRegistryFactory
{
    /** @return CreditAccountRegistry */
    public function create()
    {
        $registry = new CreditAccountRegistry();
        $principal = new Account();
        $principalType = new AccountType('active');
        $principal->setType($principalType);
        $registry->setPrincipal($principal);
        $interest = new Account();
        $interestType = new AccountType('active');
        $interest->setType($interestType);
        $registry->setInterest($interest);
        return $registry;
    }
}
```
