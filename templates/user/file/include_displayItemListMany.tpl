{* purpose of this template: inclusion template for display of related files in user area *}
{if !isset($nolink)}
    {assign var='nolink' value=false}
{/if}
{if isset($items) && $items ne null && count($items) gt 0}
<ul class="relatedItemList file">
{foreach name='relLoop' item='item' from=$items}
    <li>
{strip}
{if !$nolink}
    <a href="{modurl modname='MUFiles' type='user' func='display' ot='file' id=$item.id}" title="{$item.title|replace:"\"":""}">
{/if}
{$item.title}
{if !$nolink}
    </a>
    <a id="fileItem{$item.id}Display" href="{modurl modname='MUFiles' type='user' func='display' ot='file' id=$item.id theme='Printer' forcelongurl=true}" title="{gt text='Open quick view window'}" class="z-hide">{icon type='view' size='extrasmall' __alt='Quick view'}</a>
{/if}
{/strip}
{if !$nolink}
<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        mufilesInitInlineWindow($('fileItem{{$item.id}}Display'), '{{$item.title|replace:"'":""}}');
    });
/* ]]> */
</script>
{/if}
    </li>
{/foreach}
</ul>
{/if}
