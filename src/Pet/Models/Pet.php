<?php
namespace App\Pet\Models;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\SerializedName;


class Pet
{
    /**
     * @JMS\Type("int")
     */
    public int $id;

    /**
     * @JMS\Type("Category")
     */
    public Category $category;

    /**
     * @JMS\Type("string")
     */
    public string $name;

    /**
     * @JMS\Type("array<string>")
     * @JMS\SerializedName("photoUrls")
     * 
     */
    public array $photoUrls;

    /**
     * @JMS\Type("array<Tag>")
     */
    public array $tags;

    /**
     * @JMS\Type("string")
     */
    public string $status;
}