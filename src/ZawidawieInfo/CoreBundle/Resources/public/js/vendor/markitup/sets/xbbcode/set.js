// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
mySettings = {
    nameSpace:       "xbbcode", // Useful to prevent multi-instances CSS conflict
	previewParserPath:	'', // path to your XBBCode parser
	onShiftEnter:	{keepDefault:false, replaceWith:'[br /]\n'},
	onCtrlEnter:	{keepDefault:false, openWith:'\n[p]', closeWith:'[/p]\n'},
	onTab:			{keepDefault:false, openWith:'	 '},
	markupSet: [
		{name:'Pogrubienie', key:'B', openWith:'[b]', closeWith:'[/b]' },
		{name:'Kursywa', key:'I', openWith:'[i]', closeWith:'[/i]' },
		{name:'Przekreślenie', key:'S', openWith:'[s]', closeWith:'[/s]' },
		{separator:'---------------' },
		{name:'Lista', openWith:'[ul]\n', closeWith:'[/ul]\n' },
		{name:'Lista numerowana', openWith:'[ol]\n', closeWith:'[/ol]\n' },
		{name:'Element listy', openWith:'[li]', closeWith:'[/li]' },
		{separator:'---------------' },
		{name:'Cytat', key:'Q', openWith:'[quote(!(="[![author]!]")!)]', closeWith:'[/quote]' },
		{name:'Zdjęcie', key:'P', replaceWith:'[img][![Adres:!:http://]!][/img]' },
		{name:'Link', key:'L', openWith:'[url="[![Link:!:http://]!]"]', closeWith:'[/url]', placeHolder:'Opis linku...' },
		{name:'Film z YouTube', key:'Y', replaceWith:'[youtube][![Adres:!:http://]!][/youtube]' },
		{separator:'---------------' },
		{name:'Usuń formatowanie', className:'clean', replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } },
	]
}