<?php
namespace App\Pet\Models;

use JMS\Serializer\Annotation as JMS;

class Tag
{
    /**
     * @JMS\Type("int")
     */
    public int $id;

    /**
     * @JMS\Type("string")
     */
    public string $name;
}