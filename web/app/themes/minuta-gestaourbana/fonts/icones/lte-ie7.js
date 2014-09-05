/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'icomoon\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-youtube' : '&#xe000;',
			'icon-twitter' : '&#xe001;',
			'icon-text-return' : '&#xe002;',
			'icon-text-remove' : '&#xe003;',
			'icon-text-change' : '&#xe004;',
			'icon-text-add' : '&#xe005;',
			'icon-so-so' : '&#xe006;',
			'icon-quadro' : '&#xe007;',
			'icon-quadro-bg' : '&#xe008;',
			'icon-propose' : '&#xe009;',
			'icon-not-agree' : '&#xe00a;',
			'icon-map' : '&#xe00b;',
			'icon-map-bg' : '&#xe00c;',
			'icon-facebook' : '&#xe00d;',
			'icon-comment' : '&#xe00e;',
			'icon-comment-bg' : '&#xe00f;',
			'icon-book' : '&#xe010;',
			'icon-arrow-rigth' : '&#xe011;',
			'icon-agree' : '&#xe012;',
			'icon-pencil' : '&#xe013;',
			'icon-mail' : '&#xe014;',
			'icon-print' : '&#xe015;',
			'icon-cancel' : '&#xe016;',
			'icon-arrow-down' : '&#xe017;',
			'icon-close' : '&#xe018;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};