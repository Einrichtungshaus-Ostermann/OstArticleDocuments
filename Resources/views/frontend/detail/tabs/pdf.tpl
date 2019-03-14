
{* set namespace *}
{namespace name="frontend/detail/tabs/pdf"}



{* offcanvas button *}
<div class="buttons--off-canvas">
    <a href="#" title="{"{s name="OffcanvasCloseMenu" namespace="frontend/detail/description"}{/s}"|escape}" class="close--off-canvas">
        <i class="icon--arrow-left"></i>
        {s name="OffcanvasCloseMenu" namespace="frontend/detail/description"}{/s}
    </a>
</div>

{* actual content *}
<div class="content--pdf">
    {assign var="file" value="{$pdf.directory}/{$pdf.file}"}
    <object width="100%" height="500px" data="{link file=$file}"></object>
</div>
