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

namespace OstArticleDocuments\Commands;

use Enlight_Components_Db_Adapter_Pdo_Mysql as Db;
use Shopware\Commands\ShopwareCommand;
use Shopware\Components\Model\ModelManager;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncDocumentsCommand extends ShopwareCommand
{
    /**
     * ...
     *
     * @var Db
     */
    private $db;

    /**
     * ...
     *
     * @var ModelManager
     */
    private $modelManager;

    /**
     * ...
     *
     * @var array
     */
    private $configuration;

    /**
     * @param Db           $db
     * @param ModelManager $modelManager
     * @param array        $configuration
     */
    public function __construct(Db $db, ModelManager $modelManager, array $configuration)
    {
        parent::__construct();
        $this->db = $db;
        $this->modelManager = $modelManager;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
        $output->writeln('syncing documents to database');

        // get every directory
        $directories = [
            1 => explode('<br>', nl2br($this->configuration['directoryDocuments'], false)),
            2 => explode('<br>', nl2br($this->configuration['directoryDataSheets'], false)),
            3 => explode('<br>', nl2br($this->configuration['directoryAssemblyInstructions'], false)),
        ];

        // loop every type
        foreach ([1 => 'document', 2 => 'data sheet', 3 => 'assembly instruction'] as $index => $name) {
            // output
            $output->writeln('reading ' . $name . ' directories');

            // loop the directories
            foreach ($directories[$index] as $directory) {
                // trim it
                $directory = trim($directory);

                // output
                $output->writeln('reading ' . $directory);

                // read every .pdf file
                $files = glob(rtrim($directory, '/') . '/*.pdf');

                // start the progress bar
                $progressBar = new ProgressBar($output, count($files));
                $progressBar->setRedrawFrequency(1);
                $progressBar->start();

                // loop them
                foreach ($files as $file) {
                    // remove everything
                    $file = str_replace(rtrim($directory, '/') . '/', '', $file);
                    $key = strtolower(str_replace('.pdf', '', $file));

                    // insert into with ignore on unique key
                    $query = '
                        INSERT IGNORE INTO `ost_article_documents` (`id`, `type`, `key`, `directory`, `file`)
                        VALUES (NULL, :type, :key, :directory, :file);
                    ';
                    $this->db->query($query, ['type' => $index, 'key' => $key, 'directory' => $directory, 'file' => $file]);

                    // advance progress bar
                    $progressBar->advance();
                }

                // done
                $progressBar->finish();
                $output->writeln('');
            }
        }

        // cleaning old documents
        $output->writeln('cleaning old documents');

        // ...
        $query = '
            SELECT *
            FROM ost_article_documents
            ORDER BY id ASC
        ';
        $documents = Shopware()->Db()->fetchAll($query);

        // start the progress bar
        $progressBar = new ProgressBar($output, count($documents));
        $progressBar->setRedrawFrequency(1);
        $progressBar->start();

        // counter
        $counter = 0;

        // ...
        foreach ($documents as $document) {
            // check if file exists
            if (!file_exists($document['directory'] . '/' . $document['file'])) {
                // remove it
                $query = '
                    DELETE FROM ost_article_documents
                    WHERE id = :id
                ';
                Shopware()->Db()->query($query, ['id' => $document['id']]);

                // and count
                ++$counter;
            }
        }

        // done
        $progressBar->finish();
        $output->writeln('');

        // ...
        $output->writeln('documents removed: ' . $counter);
    }
}
