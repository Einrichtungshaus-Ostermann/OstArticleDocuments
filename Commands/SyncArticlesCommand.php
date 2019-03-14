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
use OstArticleDocuments\Models\Document;
use Shopware\Commands\ShopwareCommand;
use Shopware\Components\Model\ModelManager;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncArticlesCommand extends ShopwareCommand
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
        // output
        $output->writeln('syncing articles to documents');

        // read every article with every group id
        /*
        $query = "
            SELECT article.name, detail.ordernumber, detail.id, GROUP_CONCAT(document.id)
            FROM s_articles_details AS detail
                LEFT JOIN s_articles AS article
                    ON detail.articleID = article.id
                LEFT JOIN ost_article_documents AS document
                    ON detail.ordernumber = document.`key`
                        OR article.name LIKE CONCAT('%',document.`key`,'%')
            GROUP BY detail.id
        ";
        */

        // ...
        $query = '
            SELECT article.name, detail.ordernumber AS number, detail.id
            FROM s_articles_details AS detail
                LEFT JOIN s_articles AS article
                    ON detail.articleID = article.id
        ';
        $articles = $this->db->fetchAll($query);

        // start the progress bar
        $progressBar = new ProgressBar($output, count($articles));
        $progressBar->setRedrawFrequency(10);
        $progressBar->start();

        // loop them
        foreach ($articles as $article) {
            // read every document
            $query = "
                SELECT document.id, document.id
                FROM ost_article_documents AS document
                WHERE document.`key` LIKE :number
                    OR :name LIKE CONCAT('%',document.`key`,'%')
            ";
            $documents = $this->db->fetchPairs($query, ['number' => $article['number'], 'name' => $article['name']]);

            // every key
            $ids = (string) implode(',', $documents);

            // update the attributes
            $query = '
                UPDATE s_articles_attributes
                SET ' . $this->configuration['attributeDocuments'] . ' = :documents
                WHERE articledetailsID = :id
            ';
            $this->db->query($query, ['documents' => $ids, 'id' => $article['id']]);

            // advance progress bar
            $progressBar->advance();
        }

        // done
        $progressBar->finish();
        $output->writeln('');
    }
}
