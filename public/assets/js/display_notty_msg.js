function generate(type, msg)
{
	var n = noty({
	text : msg,
	type : type,
	dismissQueue: true,
	timeout : 10000,
	closeWith : ['click'],
	layout : 'bottomRight',
	theme : 'defaultTheme',
	maxVisible : 10
	});
	console.log('html: ' + n.options.id);
}
