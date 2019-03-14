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

namespace OstArticleDocuments\Listeners\Controllers\Frontend;

use Enlight_Controller_Action as Controller;
use Enlight_Event_EventArgs as EventArgs;

class Detail
{
    /**
     * ...
     *
     * @var array
     */
    protected $configuration;

    /**
     * ...
     *
     * @param array $configuration
     */
    public function __construct($configuration)
    {
        // set params
        $this->configuration = $configuration;
    }

    /**
     * ...
     *
     * @param EventArgs $arguments
     */
    public function onPostDispatch(EventArgs $arguments)
    {
        // get the controller
        /* @var $controller Controller */
        $controller = $arguments->get('subject');

        // get parameters
        $request = $controller->Request();
        $view = $controller->View();

        // only order action
        if (strtolower($request->getActionName()) !== 'index') {
            // nothing to do
            return;
        }

        // only valid article with valid attribute
        if ((!isset($view->getAssign('sArticle')['attributes']['core'])) || ((string) $view->getAssign('sArticle')['attributes']['core']->get($this->configuration['attributeDocuments']) === '')) {
            // stop
            return;
        }

        // get the ids
        $attribute = (string) $view->getAssign('sArticle')['attributes']['core']->get($this->configuration['attributeDocuments']);

        // get them
        $ids = explode(',', $attribute);

        // get them all
        // order by file to get the number first and name second as document to show in the pdf tab
        $query = '
            SELECT *
            FROM ost_article_documents
            WHERE id IN (' . implode(',', $ids) . ')
            ORDER BY type ASC, file ASC
        ';
        $arr = Shopware()->Db()->fetchAll($query);

        // all documents here
        $documents = [
            1 => [],
            2 => [],
            3 => []
        ];

        // loop them
        foreach ($arr as $document) {
            // set it
            $documents[(int) $document['type']][] = $document;
        }

        // assign them
        $view->assign('ostArticleDocuments', $documents);
    }
}
