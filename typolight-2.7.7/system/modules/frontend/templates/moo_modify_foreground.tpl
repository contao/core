
<script type="text/javascript">
<!--//--><![CDATA[//><!--
window.addEvent('domready', function()
{
	new Accordion($$('div.toggler'), $$('div.accordion'), 
	{
		display: false,
		alwaysHide: true,
		opacity: false,

		onActive: function(toggler, i)
		{
			toggler.getFirst() ? toggler.getFirst().setStyle('color', '#000000') : toggler.setStyle('color', '#000000');
		},

		onBackground: function(toggler, i)
		{
			toggler.getFirst() ? toggler.getFirst().setStyle('color', '#a84204') : toggler.setStyle('color', '#a84204');
		}
	});
});
//--><!]]>
</script>
