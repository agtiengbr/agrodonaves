<?php
namespace AGTI\Rodonaves\Entity;

use AGTI\Rodonaves\Exception\DataValidationException;

class Pack
{
    protected $amountPackages;
    protected $weight;
    protected $length;
    protected $height;
    protected $width;

    /**
     * Get the value of amountPackages
     */ 
    public function getAmountPackages()
    {
        return $this->amountPackages;
    }

    /**
     * Set the value of amountPackages
     *
     * @return  self
     */ 
    public function setAmountPackages($amountPackages)
    {
        if (!\Validate::isInt($amountPackages) || $amountPackages < 1) {
            throw new DataValidationException("Propriedade amountPackages inválida. Valor informado: {$amountPackages}.");
        }

        $this->amountPackages = $amountPackages;

        return $this;
    }

    /**
     * Get the value of weight
     */ 
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set the value of weight
     *
     * @return  self
     */ 
    public function setWeight($weight)
    {
        if (!\Validate::isUnsignedFloat($weight) || !$weight > 0) {
            throw new DataValidationException("Propriedade weight inválida. Valor informado: {$weight}.");
        }

        $this->weight = $weight;

        return $this;
    }

    /**
     * Get the value of length
     */ 
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set the value of length
     *
     * @return  self
     */ 
    public function setLength($length)
    {
        if (!\Validate::isUnsignedFloat($length) || !$length > 0) {
            throw new DataValidationException("Propriedade length inválida. Valor informado: {$length}.");
        }

        $this->length = $length;

        return $this;
    }

    /**
     * Get the value of height
     */ 
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set the value of height
     *
     * @return  self
     */ 
    public function setHeight($height)
    {
        if (!\Validate::isUnsignedFloat($height) || !$height > 0) {
            throw new DataValidationException("Propriedade height inválida. Valor informado: {$height}.");
        }

        $this->height = $height;

        return $this;
    }

    /**
     * Get the value of width
     */ 
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set the value of width
     *
     * @return  self
     */ 
    public function setWidth($width)
    {
        if (!\Validate::isUnsignedFloat($width) || !$width > 0) {
            throw new DataValidationException("Propriedade width inválida. Valor informado: {$width}.");
        }

        $this->width = $width;

        return $this;
    }
}