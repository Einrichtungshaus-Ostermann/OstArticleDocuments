
{* file to extend *}
{extends file="parent:frontend/detail/content.tpl"}



{* append our icons below the images *}
{block name="frontend_detail_index_image"}

    {* parent block *}
    {$smarty.block.parent}

    {* do we have icons? *}
    {if count($ostArticleDocuments[1]) > 0 || count($ostArticleDocuments[2]) > 0 || count($ostArticleDocuments[3]) > 0}
        <div class="ost-article-documents">
            {foreach $ostArticleDocuments as $type => $documents}
                {foreach $documents as $document}

                    {* set the file type *}
                    {if $type == 1}
                        {assign var="file" value="model"}
                    {elseif $type == 2}
                        {assign var="file" value="data-sheet"}
                    {else}
                        {assign var="file" value="assembly-instructions"}
                    {/if}

                    {* create filename with directory *}
                    {assign var="image" value="frontend/_public/src/img/icon--pdf-{$file}.png"}
                    {assign var="pdf" value="{$document.directory}/{$document.file}"}

                    {* the document*}
                    <div class="document">
                        <a href="{$pdf}" target="_blank"><img src="{link file=$image}"></a>
                    </div>

                {/foreach}
            {/foreach}
        </div>
    {/if}

{/block}
