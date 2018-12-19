<?php

declare(strict_types=1);


namespace MKebza\SonataExt\Service\Workflow;


use Elao\Enum\EnumInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\MarkingStore\MarkingStoreInterface;

class EnumMarkingStore implements MarkingStoreInterface
{
    private $property;
    private $propertyAccessor;

    public function __construct(string $property = null, PropertyAccessorInterface $propertyAccessor = null)
    {
        if (null === $property) {
            throw new \LogicException(sprintf('For marking store you need to set which property to use'));
        }
        $this->property = $property;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function getMarking($subject)
    {
        $placeName = $this->propertyAccessor->getValue($subject, $this->property);

        if (!$placeName) {
            return new Marking();
        }

        return new Marking(array($placeName->getValue() => 1));
    }

    /**
     * {@inheritdoc}
     */
    public function setMarking($subject, Marking $marking)
    {
        $refClass = new \ReflectionClass($subject);
        $methodName = sprintf('set%s', ucfirst($this->property));

        if (!$refClass->hasMethod($methodName)) {
            throw new \LogicException(sprintf('Enum marking store requires method %s for class %s. Based on service definition', $methodName, $refClass->getName()));
        }

        if ($refClass->getMethod($methodName)->getNumberOfParameters() < 0) {
            throw new \LogicException(sprintf("Function %s in %s must have at least one parameter", $methodName, $refClass->getName()));
        }

        $firstParam = $refClass->getMethod($methodName)->getParameters()[0];
        if (null === $firstParam->getType()->getName()) {
            throw new \LogicException(sprintf("First parameter of %s::%s does't have required type hint for class", $refClass->getName(), $methodName));
        }

        $refParam = new \ReflectionClass($firstParam->getType()->getName());
        if (!$refParam->implementsInterface(EnumInterface::class)) {
            throw new \LogicException(sprintf("Type hinted parameter in %s::%s have to implement %s", $refClass->getName(), $methodName, EnumInterface::class));
        }

        $value = $firstParam->getType()->getName()::get(key($marking->getPlaces()));

        $this->propertyAccessor->setValue($subject, $this->property, $value);
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }
}