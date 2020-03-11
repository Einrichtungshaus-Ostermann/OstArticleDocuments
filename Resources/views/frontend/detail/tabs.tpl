
{* file to extend *}
{extends file="parent:frontend/detail/tabs.tpl"}



{* tab navigation *}
{block name="frontend_detail_tabs_description"}

    {* smarty parent *}
    {$smarty.block.parent}

    {* do we have a document? *}
    {if count($ostArticleDocuments[1]) > 0}
        {*
        <a href="#" class="tab--link" title="PDF" data-tabName="pdf">PDF</a>
        *}
    {/if}

{/block}



{* tab content *}
{block name="frontend_detail_tabs_content_description"}

    {* smarty parent *}
    {$smarty.block.parent}

    {* do we have a document? *}
    {if count($ostArticleDocuments[1]) > 0}
        {* tab container for shipping details *}
        {*
        <div class="tab--container">
            <div class="tab--header">
                <a href="#" class="tab--title" title="PDF">PDF</a>
            </div>
            <div class="tab--preview">
                PDF hier...
            </div>
            <div class="tab--content">
                {include file="frontend/detail/tabs/pdf.tpl" pdf=$ostArticleDocuments[1][0]}
            </div>
        </div>
        *}
    {/if}

{/block}
