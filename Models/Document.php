<?php declare(strict_types=1);

/**
 * Einrichtungshaus Ostermann GmbH & Co. KG - Article Documents
 *
 * @package   OstArticleDocuments
 *
 * @author    Eike Brandt-Warneke <e.brandt-warneke@ostermann.de>
 * @copyright 2019 Einrichtungshaus Ostermann GmbH & Co. KG
 * @license   proprietary
 */

namespace OstArticleDocuments\Models;

use Doctrine\ORM\Mapping as ORM;
use Shopware\Components\Model\ModelEntity;

/**
 * @ORM\Entity(repositoryClass="Repository")
 * @ORM\Table(name="ost_article_documents",uniqueConstraints={@ORM\UniqueConstraint(name="unique_files", columns={"directory", "file"})})
 */
class Document extends ModelEntity
{
    /**
     * ...
     */
    const TYPE_DOCUMENT = 1;
    const TYPE_DATA_SHEET = 2;
    const TYPE_ASSEMBLY_INSTRUCTIONS = 3;

    /**
     * Auto-generated id.
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * The type of the document.
     *
     * Available types:
     * 1 -> default .pdf
     * 2 -> data sheet
     * 3 -> assembly instructions
     *
     * @var int
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type;

    /**
     * The key.
     *
     * Example:
     * - bugatti titan
     * - mondo divo
     *
     * @var string
     *
     * @ORM\Column(name="`key`", type="string", nullable=false)
     */
    private $key;

    /**
     * The directory of the file relative to our home directory.
     *
     * Example:
     * - media/pdf
     * - media/pdf/montageanleitung
     * - media/pdf/pdf_name
     *
     * @var string
     *
     * @ORM\Column(name="directory", type="string", nullable=false)
     */
    private $directory;

    /**
     * The name of the file.
     *
     * Example:
     * - 103414.pdf
     * - bugatti titan.pdf
     * - mondo divo.pdf
     *
     * @var string
     *
     * @ORM\Column(name="`file`", type="string", nullable=false)
     */
    private $file;

    /**
     * Getter method for the property.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Getter method for the property.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Setter method for the property.
     *
     * @param int $type
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }

    /**
     * Getter method for the property.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Setter method for the property.
     *
     * @param string $key
     */
    public function setKey(string $key)
    {
        $this->key = $key;
    }

    /**
     * Getter method for the property.
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Setter method for the property.
     *
     * @param string $directory
     */
    public function setDirectory(string $directory)
    {
        $this->directory = $directory;
    }

    /**
     * Getter method for the property.
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Setter method for the property.
     *
     * @param string $file
     */
    public function setFile(string $file)
    {
        $this->file = $file;
    }
}
